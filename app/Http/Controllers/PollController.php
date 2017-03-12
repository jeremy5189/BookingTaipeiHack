<?php

namespace App\Http\Controllers;

use App\Http\BookingAPI;
use App\Poll;
use App\Hotel;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

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

    public function postPoll(Request $request) 
    {        
        //create user
        $authorReq = $request->input('author');
        $user = User::firstOrCreate($authorReq[0]);
       
        //create poll
        $pollReq = $request->only(['title', 'startDate','endDate','personAmount']);
        $pollReq['id'] = sha1(time());
        $pollReq['author'] = $user->id;      
        $poll = Poll::Create($pollReq);
        
        //create hotel
        $hotelReq = $request->input('hotels');
        foreach ($hotelReq as $key => $value) {
            $value['poll_id'] = $pollReq['id'];
            $value['id'] = sha1(time());
            $hotel = Hotel::Create($value);
        }
       
        return response(array("poll id" => $pollReq['id']), 200);
    }

    public function deletePoll() 
    {

    }
}
