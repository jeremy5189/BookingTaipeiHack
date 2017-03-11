<?php

namespace App\Http\Controllers;

use App\Http\BookingAPI;
use Illuminate\Http\Request;

class APIProxy extends Controller
{
    function getHotelByUrl(Request $request)
    {
        return BookingAPI::getHotelByUrl($request->input('url'));
    }

    function getAvailabilityById(Request $request)
    {
        return BookingAPI::getAvailability(
            $request->input('checkIn'),
            $request->input('checkOut'),
            $request->input('hotelId'),
            $request->input('personAmount')
        );
    }
}
