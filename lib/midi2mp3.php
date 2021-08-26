<?php

class Midi2Mp3
{

    // dossier temporaire pour le traitement lilypond
    const TMP_DIR = '/tmp/midi2mp3';

    // Bass
    const A = 'BasBassLongs/.sf2';
    const B = 'Bass/BassPizz.sf2';
    const C = 'Bass/BassShort.sf2';
    const D = 'Bass/BassStreet.sf2';
    const E = 'Bass/BassSynthClassic.SF2';
    const F = 'Bass/BassTremolo.sf2';
    const G = 'Bass/EPBass2.sf2';
    const H = 'Bass/fenderjazz.sf2';
// Guitars
    const CH = 'Guitars/3_lead_guitars.sf2';
    const I = 'Guitars/AI-Guitarish01.SF2';
    const J = 'Guitars/GuitarAcoustic.sf2';
    const K = 'Guitars/IbanezElectricGuitar.SF2';
    const L = 'Guitars/PowerGuitar1.sf2';
// klavesyHipHop
    const M = 'klavesyHipHop/Organ.SF2';
    const N = 'klavesyHipHop/organ26-Organ26.sf2';
// Percussions
    const O = 'Percussions/KhaliagiDrums.sf2';
// Piano
    const P = 'Piano/ConcertPiano.SF2';
    const Q = 'Piano/GrandPiano.sf2';
    const R = 'Piano/HipHopKeyz1.sf2';
    const S = 'Piano/KAWAI-GoodPiano.sf2';

// Strings
    const T = 'Strings/obsyn-OberheimSynth.sf2';
    const U = 'Strings/MassiveStrings.sf2';
    const V = 'Strings/P5Strings.sf2';
    const W = 'Strings/PlasticStrings.sf2';
    const X = 'Strings/RolandMarcatoStrings.sf2';

// Synths
    const Y = 'Synths/FMModulator.sf2';
    const Z = 'Synths/frezze2.sf2';
    const AA = 'Synths/HappyMellow.sf2';
    const AB = 'Synths/PlasticStrings.sf2';
    const AC = 'Synths/PulseWobbler.sf2';
// Unsorted
    const AD = 'Unsorted/_trap_lead.sf2';
    const AE = 'Unsorted/SFT_115_D_Mood_Chord.sf2';

    const MIDITEST = 'music.midi';
    //const SOUNDFONT = 'TimGM6mb.sf2';

    // Id de session unique
    private $id;

    // Dossier de travail pour la session
    private $dir;

    // Chemin complet du fichier d'entrée
    private $inputFile;

    // Chemin complet du fichier de log FluidSynth
    private $logFileFS;

    // Chemin complet du fichier de log Lame
    private $logFileLame;

    // MP3S
    private $songs;


    //-----------------------------------------
    // INFO
    //-----------------------------------------	

    public function info()
    {

        // Compose le message retour
        return array(
            'apiName' => 'midi2mp3',
            'version' => array(
                'api' => '2.0',
                'fluildsynth' => FLUIDSYNTH_VERSION,
                'lame' => LAME_VERSION
            ),
            'description' => 'Midi to Mp3 file conversion with queue and CDN SF2',
        );
    }

    public function showSF()
    {
        $db = new SQLite3('../lib/db.sqlite');

        $db->query('CREATE TABLE if not exists "sf" ("id" integer, "name" varchar FIRST, "uuid" varchar FIRST, "type" varchar FIRST, "location" varchar FIRST, "source" text FIRST, "downloaded" varchar FIRST, PRIMARY KEY (id))');;
        $array = array();
        $results = $db->query('SELECT * FROM "sf"');


        while ($row = $results->fetchArray()) {
            array_push($array, $row);
        }

        return $array;

    }


    //-----------------------------------------
    // CONVERTION
    //-----------------------------------------

    public function convert($midiDataA,
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
    )
    {

        // Mode optimiste
        $success = true;
        $message = '';

        try {


            $tmp_dir = self::TMP_DIR;
            mkdir($tmp_dir . "/output", 0777, true);
            $output_dir = $tmp_dir . "/output";


            /*
             *
            if (isset($midiDataA) && $midiDataA != "") {
                $this->songs["A"] = $this->convertor($midiDataA, "A");
            }
            if (isset($midiDataB) && $midiDataB != "") {
                $this->songs["B"] = $this->convertor($midiDataB, "B");
            }
            if (isset($midiDataC) && $midiDataC != "") {
                $this->songs["C"] = $this->convertor($midiDataC, "C");
            }
            if (isset($midiDataD) && $midiDataD != "") {
                $this->songs["D"] = $this->convertor($midiDataD, "D");
            }
            if (isset($midiDataE) && $midiDataE != "") {
                $this->songs["E"] = $this->convertor($midiDataE, "E");
            }
            if (isset($midiDataF) && $midiDataF != "") {
                $this->songs["F"] = $this->convertor($midiDataF, "F");
            }
            if (isset($midiDataG) && $midiDataG != "") {
                $this->songs["G"] = $this->convertor($midiDataG, "G");
            }
            if (isset($midiDataH) && $midiDataH != "") {
                $this->songs["H"] = $this->convertor($midiDataH, "H");
            }
            if (isset($midiDataCH) && $midiDataCH != "") {
                $this->songs["CH"] = $this->convertor($midiDataCH, "CH");
            }
            if (isset($midiDataI) && $midiDataI != "") {
                $this->songs["I"] = $this->convertor($midiDataI, "I");
            }
            if (isset($midiDataJ) && $midiDataJ != "") {
                $this->songs["J"] = $this->convertor($midiDataJ, "J");
            }
            if (isset($midiDataK) && $midiDataK != "") {
                $this->songs["K"] = $this->convertor($midiDataK, "K");
            }
            if (isset($midiDataL) && $midiDataL != "") {
                $this->songs["L"] = $this->convertor($midiDataL, "L");
            }
            if (isset($midiDataM) && $midiDataM != "") {
                $this->songs["M"] = $this->convertor($midiDataM, "M");
            }
            if (isset($midiDataN) && $midiDataN != "") {
                $this->songs["N"] = $this->convertor($midiDataN, "N");
            }
            if (isset($midiDataO) && $midiDataO != "") {
                $this->songs["O"] = $this->convertor($midiDataO, "O");
            }
            if (isset($midiDataP) && $midiDataP != "") {
                $this->songs["P"] = $this->convertor($midiDataP, "P");
            }
            if (isset($midiDataQ) && $midiDataQ != "") {
                $this->songs["Q"] = $this->convertor($midiDataQ, "Q");
            }
            if (isset($midiDataR) && $midiDataR != "") {
                $this->songs["R"] = $this->convertor($midiDataR, "R");
            }
            if (isset($midiDataS) && $midiDataS != "") {
                $this->songs["S"] = $this->convertor($midiDataS, "S");
            }
            if (isset($midiDataT) && $midiDataT != "") {
                $this->songs["T"] = $this->convertor($midiDataT, "T");
            }
            if (isset($midiDataU) && $midiDataU != "") {
                $this->songs["U"] = $this->convertor($midiDataU, "U");
            }
            if (isset($midiDataV) && $midiDataV != "") {
                $this->songs["V"] = $this->convertor($midiDataV, "V");
            }
            if (isset($midiDataW) && $midiDataW != "") {
                $this->songs["W"] = $this->convertor($midiDataW, "W");
            }
            if (isset($midiDataX) && $midiDataX != "") {
                $this->songs["X"] = $this->convertor($midiDataX, "X");
            }
            if (isset($midiDataY) && $midiDataY != "") {
                $this->songs["Y"] = $this->convertor($midiDataY, "Y");
            }
            if (isset($midiDataZ) && $midiDataZ != "") {
                $this->songs["Z"] = $this->convertor($midiDataZ, "Z");
            }
            if (isset($midiDataAA) && $midiDataAA != "") {
                $this->songs["AA"] = $this->convertor($midiDataAA, "AA");
            }
            if (isset($midiDataAB) && $midiDataAB != "") {
                $this->songs["AB"] = $this->convertor($midiDataAB, "AB");
            }
            if (isset($midiDataAC) && $midiDataAC != "") {
                $this->songs["AC"] = $this->convertor($midiDataAC, "AC");
            }
            if (isset($midiDataAD) && $midiDataAD != "") {
                $this->songs["AD"] = $this->convertor($midiDataAD, "AD");
            }
            if (isset($midiDataAE) && $midiDataAE != "") {
                $this->songs["AE"] = $this->convertor($midiDataAE, "AE");
            }
            if (isset($midiDataAF) && $midiDataAF != "") {
                $this->songs["AF"] = $this->convertor($midiDataAF, "AF");
            }
            if (isset($midiDataAG) && $midiDataAG != "") {
                $this->songs["AG"] = $this->convertor($midiDataAG, "AG");
            }
            if (isset($midiDataAH) && $midiDataAH != "") {
                $this->songs["AH"] = $this->convertor($midiDataAH, "AH");
            }
            if (isset($midiDataACH) && $midiDataACH != "") {
                $this->songs["ACH"] = $this->convertor($midiDataACH, "ACH");
            }
            if (isset($midiDataAI) && $midiDataAI != "") {
                $this->songs["AI"] = $this->convertor($midiDataAI, "AI");
            }
            if (isset($midiDataAJ) && $midiDataAJ != "") {
                $this->songs["AJ"] = $this->convertor($midiDataAJ, "AJ");
            }
            if (isset($midiDataAK) && $midiDataAK != "") {
                $this->songs["AK"] = $this->convertor($midiDataAK, "AK");
            }
            if (isset($midiDataAL) && $midiDataAL != "") {
                $this->songs["AL"] = $this->convertor($midiDataAL, "AL");
            }
            if (isset($midiDataAM) && $midiDataAM != "") {
                $this->songs["AM"] = $this->convertor($midiDataAM, "AM");
            }
            if (isset($midiDataAN) && $midiDataAN != "") {
                $this->songs["AN"] = $this->convertor($midiDataAN, "AN");
            }
            if (isset($midiDataAO) && $midiDataAO != "") {
                $this->songs["AO"] = $this->convertor($midiDataAO, "AO");
            }

            */


            $this->songs["A"] = $this->convertor($midiDataA, "A");


            $this->songs["B"] = $this->convertor($midiDataB, "B");


            $this->songs["C"] = $this->convertor($midiDataC, "C");


            $this->songs["D"] = $this->convertor($midiDataD, "D");


            $this->songs["E"] = $this->convertor($midiDataE, "E");


            $this->songs["F"] = $this->convertor($midiDataF, "F");


            $this->songs["G"] = $this->convertor($midiDataG, "G");


            $this->songs["H"] = $this->convertor($midiDataH, "H");


            $this->songs["CH"] = $this->convertor($midiDataCH, "CH");


            $this->songs["I"] = $this->convertor($midiDataI, "I");


            $this->songs["J"] = $this->convertor($midiDataJ, "J");


            $this->songs["K"] = $this->convertor($midiDataK, "K");


            $this->songs["L"] = $this->convertor($midiDataL, "L");


            $this->songs["M"] = $this->convertor($midiDataM, "M");


            $this->songs["N"] = $this->convertor($midiDataN, "N");


            $this->songs["O"] = $this->convertor($midiDataO, "O");


            $this->songs["P"] = $this->convertor($midiDataP, "P");


            $this->songs["Q"] = $this->convertor($midiDataQ, "Q");


            $this->songs["R"] = $this->convertor($midiDataR, "R");


            $this->songs["S"] = $this->convertor($midiDataS, "S");


            $this->songs["T"] = $this->convertor($midiDataT, "T");


            $this->songs["U"] = $this->convertor($midiDataU, "U");


            $this->songs["V"] = $this->convertor($midiDataV, "V");


            $this->songs["W"] = $this->convertor($midiDataW, "W");


            $this->songs["X"] = $this->convertor($midiDataX, "X");


            $this->songs["Y"] = $this->convertor($midiDataY, "Y");


            $this->songs["Z"] = $this->convertor($midiDataZ, "Z");


            $this->songs["AA"] = $this->convertor($midiDataAA, "AA");


            $this->songs["AB"] = $this->convertor($midiDataAB, "AB");


            $this->songs["AC"] = $this->convertor($midiDataAC, "AC");


            $this->songs["AD"] = $this->convertor($midiDataAD, "AD");


            $this->songs["AE"] = $this->convertor($midiDataAE, "AE");


            /*

            if (isset($this->songs["B"])) {
                $cmdMix = "ffmpeg -i /tmp/midi2mp3/A/A.mp3 -i /tmp/midi2mp3/B/B.mp3 -filter_complex amix=inputs=2:duration=longest /tmp/midi2mp3/output/output.mp3";
                //$cmdMix = "ffmpeg -i /tmp/midi2mp3/A/A.mp3 -i /tmp/midi2mp3/B/B.mp3 -filter_complex amerge=inputs=2 -ac 2 /tmp/midi2mp3/output/output.mp3";
                $file = "/tmp/midi2mp3/output/output.mp3";
            } elseif (isset($this->songs["C"])) {
                $cmdMix = "ffmpeg -i /tmp/midi2mp3/A/A.mp3 -i /tmp/midi2mp3/B/B.mp3 -i /tmp/midi2mp3/C/C.mp3 -filter_complex amix=inputs=3:duration=longest /tmp/midi2mp3/output/output.mp3";
                //$cmdMix = "ffmpeg -i /tmp/midi2mp3/A/A.mp3 -i /tmp/midi2mp3/B/B.mp3 -filter_complex amerge=inputs=2 -ac 2 /tmp/midi2mp3/output/output.mp3";
                $file = "/tmp/midi2mp3/output/output.mp3";
            } elseif (isset($this->songs["D"])) {
                $cmdMix = "ffmpeg -i /tmp/midi2mp3/A/A.mp3 -i /tmp/midi2mp3/B/B.mp3 -i /tmp/midi2mp3/C/C.mp3 -i /tmp/midi2mp3/D/D.mp3 -filter_complex amix=inputs=4:duration=longest /tmp/midi2mp3/output/output.mp3";
                //$cmdMix = "ffmpeg -i /tmp/midi2mp3/A/A.mp3 -i /tmp/midi2mp3/B/B.mp3 -filter_complex amerge=inputs=2 -ac 2 /tmp/midi2mp3/output/output.mp3";
                $file = "/tmp/midi2mp3/output/output.mp3";
            } //TODO doplnit další else asi?

            */

            $cmdMix = "ffmpeg";
            $cmdMix .= " -i /tmp/midi2mp3/A/A.mp3";
            $cmdMix .= " -i /tmp/midi2mp3/B/B.mp3 ";
            $cmdMix .= " -i /tmp/midi2mp3/C/C.mp3 ";
            $cmdMix .= " -i /tmp/midi2mp3/D/D.mp3 ";
            $cmdMix .= " -i /tmp/midi2mp3/E/E.mp3 ";
            $cmdMix .= " -i /tmp/midi2mp3/F/F.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/G/G.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/H/H.mp3  ";
            //8

            $cmdMix .= " -i /tmp/midi2mp3/CH/CH.mp3 ";
            $cmdMix .= " -i /tmp/midi2mp3/I/I.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/J/J.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/K/K.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/L/L.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/M/M.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/N/N.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/O/O.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/P/P.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/Q/Q.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/R/R.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/S/S.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/T/T.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/U/U.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/V/V.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/W/W.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/X/X.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/Y/Y.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/Z/Z.mp3  ";
            //19

            $cmdMix .= " -i /tmp/midi2mp3/AA/AA.mp3 ";
            //1

            $cmdMix .= " -i /tmp/midi2mp3/AB/AB.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/AC/AC.mp3  ";
            $cmdMix .= " -i /tmp/midi2mp3/AD/AD.mp3  ";
            //3

            $cmdMix .= " -i /tmp/midi2mp3/AE/AE.mp3  ";


            $cmdMix .= " -filter_complex amix=inputs=32:duration=longest -vol 2825";
            $cmdMix .= " /tmp/midi2mp3/output/output.mp3";
            //TODO VOLUME vychytat

            //$cmdMix = "ffmpeg -i /tmp/midi2mp3/A/A.mp3  -i /tmp/midi2mp3/B/B.mp3  -i /tmp/midi2mp3/C/C.mp3  -i /tmp/midi2mp3/D/D.mp3  -i /tmp/midi2mp3/E/E.mp3  -i /tmp/midi2mp3/F/F.mp3  -i /tmp/midi2mp3/G/G.mp3  -i /tmp/midi2mp3/H/H.mp3 -filter_complex amix=inputs=8:duration=longest /tmp/midi2mp3/output/output.mp3";
            //$cmdMix = "ffmpeg -i /tmp/midi2mp3/A/A.mp3 -filter_complex amerge=inputs=1 -ac 2 /tmp/midi2mp3/output/output.mp3";
            $file = "/tmp/midi2mp3/output/output.mp3";

            //$cmdMix = "ffmpeg -i /tmp/midi2mp3/A/A.mp3 -i /tmp/midi2mp3/B/B.mp3 -filter_complex amerge=inputs=2 -ac 2 /tmp/midi2mp3/output/output.mp3";
            //$cmdMix = "ffmpeg -i $this->songs[0] -i $this->songs[1] -filter_complex amix=inputs=2:duration=longest $output_dir/output.mp3";
            exec($cmdMix, $opM, $retValM);

            // Compose la réponse
            //$result = $this->getConvertResponse($success, $message);

            // Efface les données de session
            //$this->deleteSessionData();

            // Retourne le résultat


            if (is_file($file)) {
                $file = base64_encode(file_get_contents($file));
                //MP3 duration
                exec('mp3info -p "%S" /tmp/midi2mp3/output/output.mp3', $opD, $retValD);
            }

        } catch (Exception $ex) {

            // Compose le retour ERREUR
            $success = false;
            $message = $ex->getMessage();

        }

        $cmd = "rm -rf " . self::TMP_DIR;
        exec($cmd, $op, $retVal);

        //return base64_encode(file_get_contents($this->songs[0]));

        //return $file;

        return array(
            'file' => $file,
            'duration' => $opD,
            'output' => $opM,
            'ret' => $retValM,
            'cmd' => $cmdMix,
        );


    }

    public function merge()
    {
        $result = base64_encode(file_get_contents($file));
    }

    public function convertor($midiData, $type)
    {

        // Mode optimiste
        $success = true;
        $message = '';

        try {

            /*
            if ($type == "A") {
                $soundfont = dirname(__DIR__) . '/soundfonts/' . self::A;
            } elseif ($type == "B") {
                $soundfont = dirname(__DIR__) . '/soundfonts/' . self::B;
            } elseif ($type == "C") {
                $soundfont = dirname(__DIR__) . '/soundfonts/' . self::C;
            } elseif ($type == "D") {
                $soundfont = dirname(__DIR__) . '/soundfonts/' . self::D;
            } else {
                $soundfont = dirname(__DIR__) . '/soundfonts/Piano.sf2';
            }
            */

            $soundfont = dirname(__DIR__) . '/soundfonts/' . constant('self::' . $type);

            // Initialisation
            $this->initPath($type);
            mkdir($this->dir, 0777, true);
            file_put_contents($this->inputFile, base64_decode($midiData, true));

            // Execution FluidSynth

            $midi = dirname(__DIR__) . '/soundfonts/' . self::MIDITEST;
            $cmd = "fluidsynth -F $this->dir/$this->id.wav $soundfont  $this->inputFile > $this->logFileFS 2>&1";
            exec($cmd, $op, $retVal);
            if ($retVal != 0) throw new Exception("Error while running fluidSynth");

            // Execution Lame
            $cmd = "lame $this->dir/$this->id.wav > $this->logFileLame 2>&1";
            exec($cmd, $op, $retVal);
            if ($retVal != 0) throw new Exception("Error while running lame");

        } catch (Exception $ex) {

            // Compose le retour ERREUR
            $success = false;
            $message = $ex->getMessage();

        }

        // Compose la réponse
        //$result = $this->getConvertResponse($success, $message);

        $result = "$this->dir/$this->id.mp3";

        // Efface les données de session
        //$this->deleteSessionData();

        // Retourne le résultat
        return $result;


    }

    /**
     * Initialise les chemins
     */
    private function initPath($type)
    {
        if (!isset($type)) {
            $this->id = uniqid();
            $this->dir = self::TMP_DIR . '/' . $this->id;
            $this->inputFile = $this->dir . '/' . $this->id . ".mid";
            $this->logFileFS = $this->dir . '/' . $this->id . ".fluidSynth.log";
            $this->logFileLame = $this->dir . '/' . $this->id . ".lame.log";
        } else {
            $this->id = $type;
            $this->dir = self::TMP_DIR . '/' . $this->id;
            $this->inputFile = $this->dir . '/' . $this->id . ".mid";
            $this->logFileFS = $this->dir . '/' . $this->id . ".fluidSynth.log";
            $this->logFileLame = $this->dir . '/' . $this->id . ".lame.log";
        }

    }

    /**
     * Prepare la réponse du Convert
     * @param $success
     * @param $message
     * @return array
     */
    private function getConvertResponse($success, $message)
    {
        return array(
            'statusCode' => $success ? 'OK' : 'ERROR',
            'message' => $message,
            'base64Mp3Data' => $this->getResultFile(),
            'logs' => $this->getLogFiles()
        );
    }


    /**
     * Charge les données du fichier resultat
     * @return string
     */
    private function getResultFile()
    {
        $result = '';
        $file = "$this->dir/$this->id.mp3";
        if (is_file($file)) {
            $result = base64_encode(file_get_contents($file));
        }
        return $result;
    }

    private function getMixedResult()
    {
        $result = '';
        $file = "$this->dir/output.mp3";
        if (is_file($file)) {
            $result = base64_encode(file_get_contents($file));
        }
        return $result;
    }

    /**
     * Charge les données du fichier de log
     * @return array
     */
    private function getLogFiles()
    {
        $logs = array();
        if (is_file($this->logFileFS)) {
            $logs[] = array(
                'title' => 'FluidSynth : MIDI to WAV convertion',
                'content' => file_get_contents($this->logFileFS)
            );
        }
        if (is_file($this->logFileLame)) {
            $logs[] = array(
                'title' => 'Lame : WAV to MP3 convertion',
                'content' => file_get_contents($this->logFileLame)
            );
        }
        return $logs;
    }

    /**
     * Efface les données de session
     */
    private function deleteSessionData()
    {
        $cmd = "rm -rf " . $this->dir;
        exec($cmd, $op, $retVal);
    }

}