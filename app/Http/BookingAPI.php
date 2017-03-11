<?php

namespace App\Http;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

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
        
        return [
            'hotel_id' => $hotel_id,
            'data' => $data
        ];
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
            
        }
    
        return $data;
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