<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) 
{
    return $app->version();
});

// Booking API Proxy Endpoint
$app->get('/getHotelByUrl',            'APIProxy@getHotelByUrl');
$app->get('/getAvailabilityById',      'APIProxy@getAvailabilityById');

// CURD API for poll
$app->get('/poll/{id}',    'PollController@getPoll');
$app->post('/poll',        'PollController@postPoll');
$app->delete('/poll/{id}', 'PollController@deletePoll');

// API for vote
$app->post('/vote',   'VoteController@postVote');
$app->get('/vote',   'VoteController@getVote');