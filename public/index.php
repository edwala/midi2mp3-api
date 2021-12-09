<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define APP_ROOT and autoloader
define('APP_ROOT', dirname(__DIR__));
require APP_ROOT . '/vendor/autoload.php';
require APP_ROOT . '/lib/midi2mp3.php';

// Includes env defined constant (generated through docker image)
@require_once APP_ROOT . '/lib/const.php';

// Creates app
$app = new \Slim\App();

/*
$container = $app->getContainer();

$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        return $response->withStatus(500)->withHeader('Content-type', 'text/html')->write('Something wrong');
    };
};
*/

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
    $jobId = $request->getParsedBody()['jobId'];
    $id = $request->getParsedBody()['id'];

    // Gets midiData in request

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


    $midiDataA_sfID = $request->getParsedBody()['base64MidiDataA_sfID'];
    $midiDataB_sfID = $request->getParsedBody()['base64MidiDataB_sfID'];
    $midiDataC_sfID = $request->getParsedBody()['base64MidiDataC_sfID'];
    $midiDataD_sfID = $request->getParsedBody()['base64MidiDataD_sfID'];
    $midiDataE_sfID = $request->getParsedBody()['base64MidiDataE_sfID'];
    $midiDataF_sfID = $request->getParsedBody()['base64MidiDataF_sfID'];
    $midiDataG_sfID = $request->getParsedBody()['base64MidiDataG_sfID'];
    $midiDataH_sfID = $request->getParsedBody()['base64MidiDataH_sfID'];
    $midiDataCH_sfID = $request->getParsedBody()['base64MidiDataCH_sfID'];
    $midiDataI_sfID = $request->getParsedBody()['base64MidiDataI_sfID'];
    $midiDataJ_sfID = $request->getParsedBody()['base64MidiDataJ_sfID'];
    $midiDataK_sfID = $request->getParsedBody()['base64MidiDataK_sfID'];
    $midiDataL_sfID = $request->getParsedBody()['base64MidiDataL_sfID'];
    $midiDataM_sfID = $request->getParsedBody()['base64MidiDataM_sfID'];
    $midiDataN_sfID = $request->getParsedBody()['base64MidiDataN_sfID'];
    $midiDataO_sfID = $request->getParsedBody()['base64MidiDataO_sfID'];
    $midiDataP_sfID = $request->getParsedBody()['base64MidiDataP_sfID'];
    $midiDataQ_sfID = $request->getParsedBody()['base64MidiDataQ_sfID'];
    $midiDataR_sfID = $request->getParsedBody()['base64MidiDataR_sfID'];
    $midiDataS_sfID = $request->getParsedBody()['base64MidiDataS_sfID'];
    $midiDataT_sfID = $request->getParsedBody()['base64MidiDataT_sfID'];
    $midiDataU_sfID = $request->getParsedBody()['base64MidiDataU_sfID'];
    $midiDataV_sfID = $request->getParsedBody()['base64MidiDataV_sfID'];
    $midiDataW_sfID = $request->getParsedBody()['base64MidiDataW_sfID'];
    $midiDataX_sfID = $request->getParsedBody()['base64MidiDataX_sfID'];
    $midiDataY_sfID = $request->getParsedBody()['base64MidiDataY_sfID'];
    $midiDataZ_sfID = $request->getParsedBody()['base64MidiDataZ_sfID'];
    $midiDataAA_sfID = $request->getParsedBody()['base64MidiDataAA_sfID'];
    $midiDataAB_sfID = $request->getParsedBody()['base64MidiDataAB_sfID'];
    $midiDataAC_sfID = $request->getParsedBody()['base64MidiDataAC_sfID'];
    $midiDataAD_sfID = $request->getParsedBody()['base64MidiDataAD_sfID'];
    $midiDataAE_sfID = $request->getParsedBody()['base64MidiDataAE_sfID'];

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
        $midiDataAE,

        $midiDataA_sfID,
        $midiDataB_sfID,
        $midiDataC_sfID,
        $midiDataD_sfID,
        $midiDataE_sfID,
        $midiDataF_sfID,
        $midiDataG_sfID,
        $midiDataH_sfID,
        $midiDataCH_sfID,
        $midiDataI_sfID,
        $midiDataJ_sfID,
        $midiDataK_sfID,
        $midiDataL_sfID,
        $midiDataM_sfID,
        $midiDataN_sfID,
        $midiDataO_sfID,
        $midiDataP_sfID,
        $midiDataQ_sfID,
        $midiDataR_sfID,
        $midiDataS_sfID,
        $midiDataT_sfID,
        $midiDataU_sfID,
        $midiDataV_sfID,
        $midiDataW_sfID,
        $midiDataX_sfID,
        $midiDataY_sfID,
        $midiDataZ_sfID,
        $midiDataAA_sfID,
        $midiDataAB_sfID,
        $midiDataAC_sfID,
        $midiDataAD_sfID,
        $midiDataAE_sfID
    );


    try {

        $client = new \GuzzleHttp\Client(["base_uri" => "$callback_base", 'verify' => false]);
        //$client = new \GuzzleHttp\Client(["base_uri" => "https://api.musicmonkey.cz"]);
        //$client = new \GuzzleHttp\Client(["base_uri" => "http://127.0.0.1:8001"]);
        $options = [
            'form_params' => [
                'acces_key' => 'zmrdevoletohlejeacceskey',
                'id' => $id,
                'mp3' => $result["file"],
                'duration' => $result["duration"],
                'jobId' => $jobId,
            ]
        ];

        //return $options;


        try {
            $client->post($callback_uri, $options);
        } catch (Exception $ex) {
            return $response->withJson($ex->getResponse()->getBody()->getContents(), 500);
        }

        //$client->post("/api/v1/midi2mp3", $options);


    } catch (Exception $ex) {


        //return $response->getBody();

        return $response->withJson($ex->getResponse()->getBody()->getContents(), 500);


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
    //$midisSerialized = json_encode($midis);
    //$midisSerialized = implode(",", $midis);
    $midisSerialized = $midis;


    $db = new SQLite3('../lib/db.sqlite');

    $db->query('CREATE TABLE "queue" ("id" integer,"state" varchar DEFAULT waiting, PRIMARY KEY (id))');

    $db->enableExceptions(true);

    try {
        $result = $db->exec("INSERT into 'queue'('state', 'project_id', 'callback_uri', 'callback_base', 'midis') values('waiting', '" . $id . "', '" . $callback_uri . "', '" . $callback_base . "', '" . $midisSerialized . "')");
    } catch (Exception $ex) {
        die($ex->getMessage());
    }


    return $response->withJson([$midis, $midisSerialized, $result], 200);
    //return $response->withJson($result, 200);
    //return $response->withJson($midiData, 200);

});

// Execution
$app->run();