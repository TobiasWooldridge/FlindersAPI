<?php

namespace Tobias\FlindersBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use Tobias\FlindersBundle\Entity\Room;
use Tobias\FlindersBundle\Entity\RoomBooking;
use Tobias\FlindersBundle\Entity\Building;

class PullRoomBookingsCommand extends ContainerAwareCommand 
{
    protected function getInnerHtml( $node ) {
        $innerHTML= '';
        $children = $node->childNodes;
        foreach ($children as $child) {
            $innerHTML .= $child->ownerDocument->saveXML( $child );
        }

        $innerHTML = preg_replace('/\xA0/u', ' ', $innerHTML);

        return $innerHTML;
    } 


    protected function configure()
    {
        $this
            ->setName('flinders:pull:room-bookings')
            ->setDescription('Scrape room bookings data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();


        $buildingId = 'IST';
        $scrapeDate = new \DateTime(date('c'), new \DateTimeZone('Australia/Adelaide'));
        $scrapeDateEnd = clone $scrapeDate;
        $scrapeDateEnd->modify('+24 hours');


        $base_url = 'http://stusyswww.flinders.edu.au/roombook.taf';

        $parameters = array(
            '_function' => 'all',
            'bldg' => $buildingId,
            'weekday' => strtoupper($scrapeDate->format('d-M-Y'))
        );

        $request_url = $base_url . '?' . http_build_query($parameters);


        $client = new Client();

        $crawler = $client->request('GET', $request_url);

        if ($client->getResponse()->getStatus() != 200) {
            var_dump($client->getResponse());
            throw new \Exception("Invalid response when retrieving room bookings");
        }

        $building = $em->getRepository('TobiasFlindersBundle:Building')->findOneById($buildingId);

        if ($building == null) {
            $building = new Building();
            $building->setId($buildingId);
        }

        $building->setName($buildingId); // todo: use actual name

        $em->persist($building);
        $em->flush();

        $rows = $crawler->filter('tr');

        $headerRow = $rows->eq(0);
        $headerColumns = $headerRow->children();
        $times = array();

        foreach ($headerColumns as $column) {
            // <a href="/?(roombook|topic)\.taf\?(.*)">(<i>)?(.*)(</i>)?</a> (.*)<br/>
            $times[] = trim($column->textContent, " \t\n\r\0\x0B\xC2\xA0");
        }
        array_shift($times);

        // Drop the header row
        $rows = $rows->eq(0)->siblings();


        $roomRepository = $em->getRepository('TobiasFlindersBundle:Room');


        foreach ($rows as $i => $row) {
            $columns = $rows->eq($i)->children();

            $roomCode = trim($columns->eq(0)->text());

            $room = $roomRepository->findOneBy(array('code' => $roomCode, 'building' => $building));

            if (!$room) {
                $room = new Room();
                $room->setCode($roomCode);
            }

            $room->setName(trim($columns->eq(1)->text()));
            $room->setCapacity(trim($columns->eq(2)->text()));
            $room->setBuilding($building);

            $em->persist($room);
            $em->flush();

            $deleteQuery = $em->createQuery('DELETE Tobias\FlindersBundle\Entity\RoomBooking b WHERE b.room = :room AND b.start BETWEEN :day_start and :day_end');
            $deleteQuery->setParameters(array(
                'room' => $room,
                'day_start' => $scrapeDate,
                'day_end' => $scrapeDateEnd
            ));
            $deleteQuery->execute();

            foreach ($columns as $j => $booking) {
                // First three columns are the metadata for the rooms
                if ($j < 3) {
                    continue;
                }

                $pattern = '$<a href="/?(?P<type>roombook|topic)\.taf\?(?P<htmlQuery>.*?)">(<i>)?(?P<id>[\/A-z0-9]+)(</i>)?</a> (?P<name>[A-z0-9 \(\)]+)(?P<cancelled>[\* ]*)<br/>$';

                $bookingHtml = $this->getInnerHtml($booking);
                if (preg_match_all($pattern, $bookingHtml, $matches, PREG_SET_ORDER) == 0) {
                    continue;
                }
                
                if ($times[$j - 3] == '12 noon') {
                    $times[$j - 3] = '12 pm';
                }

                $bookingStart = new \DateTime(date('c', strtotime($scrapeDate->format('Y-m-d') . ' ' . $times[$j - 3])), new \DateTimeZone('Australia/Adelaide'));

                $bookingEnd = clone $bookingStart;
                $bookingEnd->modify('+1 hour');

                foreach ($matches as $match) {
                    $booking = new RoomBooking();
                    $booking->setRoom($room);
                    $booking->setStart($bookingStart);
                    $booking->setEnd($bookingEnd);
                    $booking->setDescription($match['id'] . ' ' . $match['name'] . ' (' . $match['type'] . ')');
                    $booking->setCancelled($match['cancelled'] == '*');

                    $em->persist($booking);
                }

            }
            $em->flush();
        }

    }
}