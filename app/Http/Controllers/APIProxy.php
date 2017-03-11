<?php

namespace App\Http\Controllers;

use App\Http\BookingAPI;

class APIProxy extends Controller
{
    function getHotelByUrl($url) 
    {
        return BookingAPI::getHotelIdByUrl($url);
    }
}
