<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Entity\Room;
use App\Entity\RoomBooking;
use App\Entity\Building;

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

        $base_url = 'http://stusyswww.flinders.edu.au/roombook.taf';

        $parameters = array(
            '_function' => 'all',
            'bldg' => $buildingId,
            'weekday' => strtoupper(date('d-M-Y'))
        );

        $request_url = $base_url . '?' . http_build_query($parameters);


        $client = new Client();

        $crawler = $client->request('GET', $request_url);

        if ($client->getResponse()->getStatus() != 200) {
            var_dump($client->getResponse());
            throw new \Exception("Invalid response when retrieving room bookings");
        }

        $building = $this->getContainer()->get('app.entity.building_repository')->findOneById($buildingId);

        if ($building == null) {
            $building = new Building();
            $building->setId($buildingId);
        }

        $building->setName($buildingId); // todo: use actual name

        $em->persist($building);

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


        $roomRepository = $this->getContainer()->get('app.entity.room_repository');


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

            continue;

            foreach ($columns as $i => $column) {
                // Skip the first three columns
                if ($i < 3) {
                    continue;
                }

                $pattern = '$<a href="/?(?P<type>roombook|topic)\.taf\?(?P<htmlQuery>.*?)">(<i>)?(?P<id>[\/A-z0-9]+)(</i>)?</a> (?P<name>[A-z0-9 \(\)]+)<br/>$';

                $bookingHtml = get_inner_html($column);
                if (preg_match_all($pattern, $bookingHtml, $matches, PREG_SET_ORDER) == 0) {
                    continue;
                }

                $bookingsData = array();
                foreach ($matches as $match) {
                    $bookingData = array();

                    $bookingData['time'] = $times[$i - 3];

                    $bookingData['id'] = $match['id'];
                    $bookingData['name'] = $match['name'];
                    $htmlQuery = htmlspecialchars_decode($match['htmlQuery']);
                    parse_str($htmlQuery, $bookingData['queryString']);

                    $bookingData['type'] = $match['type'];

                    $bookingsData[] = $bookingData;

                }

                $room->bookings[$i - 3] = $bookingData;
            }
        }

        $em->flush();
    }
}