<?php

namespace App\Http\Controllers;

use App\Http\BookingAPI;
use App\Poll;
use App\Hotel;
use Illuminate\Http\Response;

class PollController extends Controller
{
    public function getPoll($id) 
    {
        $poll = Poll::where('id', $id)->with('author')->first();
        $asso_hotel = Hotel::where('poll_id', $id)->get();
        
        $hotel_id_arr = [];
        foreach($asso_hotel as $hotel) {
            $hotel_id_arr[] = $hotel->booking_id;
        }

        $hotel_data = BookingAPI::getHotelDataAsync($hotel_id_arr);

        foreach($asso_hotel as $hotel) {
            $hotel->hotelData = $hotel_data[$hotel->booking_id];
        }
 
        $poll->data = $asso_hotel;
         return response($poll)
                  ->header('Access-Control-Allow-Origin', '*');
    }

    public function postPoll() 
    {

    }

    public function deletePoll() 
    {

    }
}
