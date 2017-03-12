<?php

namespace App\Http\Controllers;

use App\Http\BookingAPI;
use App\Poll;
use App\Hotel;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    public function getPoll($id) 
    {
        
        $poll = Poll::where('id', $id)->with('author')->first();

        $asso_hotel =  DB::table('hotels')->where('poll_id', '=', $poll->id)->get();
       
        $hotel_id_arr = [];
        foreach($asso_hotel as $hotel) {           
            $hotel_id_arr[] = $hotel->booking_id;
        }
      

        $hotel_data = BookingAPI::getHotelDataAsync($hotel_id_arr);
       
        foreach($asso_hotel as $hotel) {
            $hotel->hotelData = $hotel_data[$hotel->booking_id];
            $hotel->vote = DB::table('votes')->where('hotel_id', '=', $hotel->id)->get();

        }
          
         $poll->data = $asso_hotel;
         
         return response($poll)->header('Access-Control-Allow-Origin', '*');
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
            $value['id'] = sha1(time()+$key);         
            $hotel = Hotel::Create($value);
        }
       
        return response(array("poll id" => $pollReq['id']), 200)->header('Access-Control-Allow-Origin', '*');
    }

    public function deletePoll() 
    {

    }
}
