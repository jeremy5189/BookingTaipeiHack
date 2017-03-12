<?php

namespace App\Http;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Promise\EachPromise;
use Psr\Http\Message\ResponseInterface;
use Log;
use Illuminate\Http\Response;

class BookingAPI 
{
    public static function getHotelId($url) 
    {
        $client = new Client(); // GuzzleHttp\Client
        $hotel_id = false;

        try {
            // Make get request
            $result = $client->get($url);
            $html = $result->getBody();

            // Get hotel id
            $re = '/name="hotel_id" value="([0-9]+)"/';
            preg_match_all($re, $html, $matches);

            // Should get 2 match
            if ( $matches[1][0] === $matches[1][1] ) {
                $hotel_id = $matches[1][0];
            }
        }
        catch( Exception $es) {
            Log::error($es);
        }

        return $hotel_id;
    }

    public static function getHotelByUrl($url) 
    {
        $hotel_id = BookingAPI::getHotelId($url);
        $data = null;
        
        if($hotel_id) {
            $data = BookingAPI::getHotelData($hotel_id);
        }
        
        return response([
            'hotel_id' => $hotel_id,
            'data' => $data
        ], 200)->header('Access-Control-Allow-Origin', '*');;
    }

    public static function getHotelData($hotel_id) 
    {
        $data = null;
        $client = new Client(); // GuzzleHttp\Client

        try {
            // Make get request
            $result = $client->get('https://distribution-xml.booking.com/json/bookings.getHotels?hotel_ids=' . $hotel_id, [
                'auth' => [
                    env('API_USERNAME'), 
                    env('API_PASSWORD')
                ]
            ]);
            $data = json_decode($result->getBody()->getContents());
        }
        catch( Exception $es) {
            Log::error($es);
        }
    
        return $data;
    }

    public static function getHotelDataAsync($hotel_id_arr) 
    {
        $client = new Client();
        $ret = [];

        $promise = BookingAPI::reqPromise($client, $hotel_id_arr);

        $pool = (new EachPromise($promise, [
            'concurrency' => 5,
            'fulfilled' => function ($response) use (&$ret){      
                // Get if array isset
                // Dealing with false hotel_id (empty resp)
                if(isset($response[0])){
                     $ret[$response[0]->hotel_id] = $response[0];
                }              
            }
        ]))->promise()->wait(); 
    
        return $ret;
    }

    // Promise function 
    private static function reqPromise($client, $hotel_id_arr)
    {
        $url = 'https://distribution-xml.booking.com/json/bookings.getHotels?hotel_ids=';

        foreach ( $hotel_id_arr as $hotel_id ) {

            $uu = $url . $hotel_id;        
            yield $client->requestAsync('GET', $uu, [
                'auth' => [
                    env('API_USERNAME'), 
                    env('API_PASSWORD')
                ]
            ])->then(function(ResponseInterface $response) {
                return json_decode($response->getBody());
            });
        }
    }

    public static function getAvailability($checkIn, $checkOut, $hotels_id, $personAmount) 
    {  
        $data = null;
        $client = new Client();

        try {

            $result = $client->get(sprintf('https://distribution-xml.booking.com/json/getHotelAvailabilityV2?checkin=%s&checkout=%s&hotel_ids=%s&room1=%s&output=%s',
                $checkIn,   // 2016-11-11
                $checkOut,  // 2016-11-12
                $hotels_id, // 123,456,789
                BookingAPI::getAmountStr($personAmount), // A,A (Two adults)
                'hotel_details,hotel_amenities,room_details,room_amenities,room_policies'
            ), [
                'auth' => [
                    env('API_USERNAME'), 
                    env('API_PASSWORD')
                ]
            ]);

            $data = $result->getBody()->getContents();
        }
        catch( Exception $es ) {
            Log::error($es);
        }

        return $data;
    }

    public static function getAmountStr($personAmount) 
    {
        $personAmount = intval($personAmount);
        
        if( $personAmount > 4 )
            $personAmount = 4;
        else if( $personAmount < 1 )
            $personAmount = 1;
        
        $room_str = 'A';

        for( $i = 0; $i < ($personAmount - 1); $i++ ) {
            $room_str .= ',A';
        }
        
        return $room_str;
    }
}