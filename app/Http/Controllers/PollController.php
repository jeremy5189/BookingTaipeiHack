<?php

namespace App\Http\Controllers;

use App\Http\BookingAPI;
use App\Poll;
use App\Hotel;

class PollController extends Controller
{
    public function getPoll($id) 
    {
        $poll = Poll::where('id', $id)->with('author')->first();
        $asso_hotel = Hotel::where('poll_id', $id)->get();
        $poll->data = $asso_hotel;
        return $poll;
    }

    public function postPoll() 
    {

    }

    public function deletePoll() 
    {

    }
}
