<?php

// Define APP_ROOT and autoloader
define('APP_ROOT', dirname(__DIR__));
require APP_ROOT . '/vendor/autoload.php';
require APP_ROOT . '/lib/midi2mp3.php';

// Includes env defined constant (generated through docker image)
@require_once APP_ROOT . '/lib/const.php';

// Creates app
$app = new \Slim\App();


// ------------------------
// INFO ROUTE
// ------------------------
$app->get('/info', function ($request, $response, $args) {

    // gets API Information
    $lp = new Midi2Mp3();
    $result = $lp->info();

    // returns json response
    return $response->withJson($result, 200);

});

// ------------------------
// CONVERT ROUTE
// ------------------------
$app->post('/convert', function ($request, $response, $args) {

    // Gets midiData in request
    $id = $request->getParsedBody()['id'];
    $midiDataA = $request->getParsedBody()['base64MidiDataA'];
    $midiDataB = $request->getParsedBody()['base64MidiDataB'];
    $midiDataC = $request->getParsedBody()['base64MidiDataC'];
    $midiDataD = $request->getParsedBody()['base64MidiDataD'];

    // Convertion
    $lp = new Midi2Mp3();
    $result = $lp->convert($midiDataA, $midiDataB, $midiDataC, $midiDataD);

    try {
        $client = new \GuzzleHttp\Client(["base_uri" => "https://api.musicmonkey.cz"]);
        $options = [
            'form_params' => [
                'acces_key' => 'zmrdevoletohlejeacceskey',
                'id' => $id,
                'mp3' => $result,
            ]
        ];
        $client->post("/api/v1/midi2mp3", $options);


    } catch (Exception $ex) {


        return $response->withJson($ex->getMessage(), 500);


    }

    // retrun results
    return $response->withJson($result, 200);
    //return $response->withJson($midiData, 200);

});

// Execution
$app->run();