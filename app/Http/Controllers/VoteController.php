<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Vote;

class VoteController extends Controller
{
    public function postVote(Request $request){

        $allReq = $request->all();
        $user = User::Create(["name" => $allReq["name"]]);

        $voteReq = $request->only("name", "reaction", "hotel_id", "note");
          
        $vote = Vote::Create([
            "name"      => $allReq["name"], 
            "reaction"  => $allReq["reaction"], 
            "hotel_id"  => $allReq["hotel_id"],
            "note"      => $allReq["note"]
        ]);
        
        return response("vote created", 200);      
        
    }
}
