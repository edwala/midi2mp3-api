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
// INFO ROUTE
// ------------------------
$app->get('/sf', function ($request, $response, $args) {

    // gets API Information
    $lp = new Midi2Mp3();
    $result = $lp->showSF();

    // returns json response
    return $response->withJson($result, 200);

});

$app->get('/queue', function ($request, $response, $args) {

    $db = new SQLite3('../lib/db.sqlite');
    $array = array();
    $results = $db->query('SELECT * FROM "queue"');


    while ($row = $results->fetchArray()) {
        array_push($array, $row);
    }

    return $response->withJson($array, 200);

});

// ------------------------
// CONVERT ROUTE
// ------------------------
$app->post('/convert', function ($request, $response, $args) {

    $callback_base = $request->getParsedBody()['callback_base'];
    $callback_uri = $request->getParsedBody()['callback_uri'];

    // Gets midiData in request
    $id = $request->getParsedBody()['id'];
    $midiDataA = $request->getParsedBody()['base64MidiDataA'];
    $midiDataB = $request->getParsedBody()['base64MidiDataB'];
    $midiDataC = $request->getParsedBody()['base64MidiDataC'];
    $midiDataD = $request->getParsedBody()['base64MidiDataD'];
    $midiDataE = $request->getParsedBody()['base64MidiDataE'];
    $midiDataF = $request->getParsedBody()['base64MidiDataF'];
    $midiDataG = $request->getParsedBody()['base64MidiDataG'];
    $midiDataH = $request->getParsedBody()['base64MidiDataH'];
    $midiDataCH = $request->getParsedBody()['base64MidiDataCH'];
    $midiDataI = $request->getParsedBody()['base64MidiDataI'];
    $midiDataJ = $request->getParsedBody()['base64MidiDataJ'];
    $midiDataK = $request->getParsedBody()['base64MidiDataK'];
    $midiDataL = $request->getParsedBody()['base64MidiDataL'];
    $midiDataM = $request->getParsedBody()['base64MidiDataM'];
    $midiDataN = $request->getParsedBody()['base64MidiDataN'];
    $midiDataO = $request->getParsedBody()['base64MidiDataO'];
    $midiDataP = $request->getParsedBody()['base64MidiDataP'];
    $midiDataQ = $request->getParsedBody()['base64MidiDataQ'];
    $midiDataR = $request->getParsedBody()['base64MidiDataR'];
    $midiDataS = $request->getParsedBody()['base64MidiDataS'];
    $midiDataT = $request->getParsedBody()['base64MidiDataT'];
    $midiDataU = $request->getParsedBody()['base64MidiDataU'];
    $midiDataV = $request->getParsedBody()['base64MidiDataV'];
    $midiDataW = $request->getParsedBody()['base64MidiDataW'];
    $midiDataX = $request->getParsedBody()['base64MidiDataX'];
    $midiDataY = $request->getParsedBody()['base64MidiDataY'];
    $midiDataZ = $request->getParsedBody()['base64MidiDataZ'];
    $midiDataAA = $request->getParsedBody()['base64MidiDataAA'];
    $midiDataAB = $request->getParsedBody()['base64MidiDataAB'];
    $midiDataAC = $request->getParsedBody()['base64MidiDataAC'];
    $midiDataAD = $request->getParsedBody()['base64MidiDataAD'];
    $midiDataAE = $request->getParsedBody()['base64MidiDataAE'];

    // Convertion
    $lp = new Midi2Mp3();
    $result = $lp->convert(
        $midiDataA,
        $midiDataB,
        $midiDataC,
        $midiDataD,
        $midiDataE,
        $midiDataF,
        $midiDataG,
        $midiDataH,
        $midiDataCH,
        $midiDataI,
        $midiDataJ,
        $midiDataK,
        $midiDataL,
        $midiDataM,
        $midiDataN,
        $midiDataO,
        $midiDataP,
        $midiDataQ,
        $midiDataR,
        $midiDataS,
        $midiDataT,
        $midiDataU,
        $midiDataV,
        $midiDataW,
        $midiDataX,
        $midiDataY,
        $midiDataZ,
        $midiDataAA,
        $midiDataAB,
        $midiDataAC,
        $midiDataAD,
        $midiDataAE
    );


    try {

        $client = new \GuzzleHttp\Client(["base_uri" => "$callback_base"]);
        //$client = new \GuzzleHttp\Client(["base_uri" => "https://api.musicmonkey.cz"]);
        //$client = new \GuzzleHttp\Client(["base_uri" => "http://127.0.0.1:8001"]);
        $options = [
            'form_params' => [
                'acces_key' => 'zmrdevoletohlejeacceskey',
                'id' => $id,
                'mp3' => $result[file],
                'duration' => $result[duration],
            ]
        ];

        $client->post($callback_uri, $options);
        //$client->post("/api/v1/midi2mp3", $options);


    } catch (Exception $ex) {


        return $response->withJson($ex->getMessage(), 500);


    }

    // retrun results
    return $response->withJson($result, 200);
    //return $response->withJson($midiData, 200);

});


// ------------------------
// CONVERT ROUTE
// ------------------------
$app->post('/queue', function ($request, $response, $args) {

    $callback_base = $request->getParsedBody()['callback_base'];
    $callback_uri = $request->getParsedBody()['callback_uri'];
    $id = $request->getParsedBody()['id'];
    $midis = $request->getParsedBody()['midis'];


    $db = new SQLite3('../lib/db.sqlite');

    $db->query('CREATE TABLE "queue" ("id" integer,"state" varchar DEFAULT waiting, PRIMARY KEY (id))');


    try {
        $result = $db->query('INSERT into "queue"("state") values("waiting")');
    } catch (Exception $ex) {
        return $response->withJson($ex->getMessage(), 500);
    }


    return $response->withJson($result, 200);
    //return $response->withJson($result, 200);
    //return $response->withJson($midiData, 200);

});

// Execution
$app->run();