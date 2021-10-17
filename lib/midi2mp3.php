<?php

class Midi2Mp3
{

    // dossier temporaire pour le traitement lilypond
    const TMP_DIR = '/tmp/midi2mp3';

    // Bass
    const FONT_1 = 'BasBassLongs/.sf2';
    const FONT_2  = 'Bass/BassPizz.sf2';
    const FONT_3  = 'Bass/BassShort.sf2';
    const FONT_4  = 'Bass/BassStreet.sf2';
    const FONT_5  = 'Bass/BassSynthClassic.SF2';
    const FONT_6  = 'Bass/BassTremolo.sf2';
    const FONT_7  = 'Bass/EPBass2.sf2';
    const FONT_8  = 'Bass/fenderjazz.sf2';
    const FONT_9  = 'Bass/ORGBS1-OrganBass1.sf2';
    const FONT_10  = 'Bass/RockBasses.sf2';
// Guitars
    const FONT_11 = 'Guitars/3_lead_guitars.sf2';
    const FONT_12  = 'Guitars/AI-Guitarish01.SF2';
    const FONT_13  = 'Guitars/GuitarAcoustic.sf2';
    const FONT_14  = 'Guitars/IbanezElectricGuitar.SF2';
    const FONT_15  = 'Guitars/PowerGuitar1.sf2';
// klavesyHipHop
    const FONT_16  = 'klavesyHipHop/Organ.SF2';
    const FONT_17  = 'klavesyHipHop/organ26-Organ26.sf2';
// Percussions
    const FONT_18  = 'Percussions/KhaliagiDrums.sf2';

    const FONT_44  = 'Percussions/KhaliagiDrums.sf2';
    const FONT_45  = 'Percussions/KhaliagiDrums.sf2';
    const FONT_46  = 'Percussions/KhaliagiDrums.sf2';
    const FONT_47  = 'Percussions/KhaliagiDrums.sf2';
    const FONT_48  = 'Percussions/KhaliagiDrums.sf2';

// Piano
    const FONT_19  = 'Piano/ConcertPiano.SF2';
    const FONT_20  = 'Piano/GrandPiano.sf2';
    const FONT_21  = 'Piano/HipHopKeyz1.sf2';
    const FONT_22  = 'Piano/KAWAI-GoodPiano.sf2';
    const FONT_23  = 'Piano/MotifES6ConcertPiano.SF2';
    const FONT_24  = 'Piano/MotifPiano.SF2';
    const FONT_25  = 'Piano/PorterGrandPiano.sf2';
    const FONT_26  = 'Piano/YamahaPiano.sf2';

// Strings
    const FONT_27  = 'Strings/MassiveStrings.sf2';
    const FONT_28  = 'Strings/obsyn-OberheimSynth.sf2';
    const FONT_29  = 'Strings/P5Strings.sf2';
    const FONT_30  = 'Strings/PlasticStrings.sf2';
    const FONT_31  = 'Strings/RolandMarcatoStrings.sf2';
    const FONT_32  = 'Strings/Strings.sf2';
    const FONT_33  = 'Strings/StringsLegatoKorgTriton.SF2';
    const FONT_34  = 'Strings/StringsLyrical.SF2';
    const FONT_35  = 'Strings/vlnsyn-ViolinSynth.sf2';

// Synths
    const FONT_36  = 'Synths/FMModulator.sf2';
    const FONT_37  = 'Synths/frezze2.sf2';
    const FONT_38 = 'Synths/HappyMellow.sf2';
    const FONT_39 = 'Synths/PlasticStrings.sf2';
    const FONT_40 = 'Synths/PulseWobbler.sf2';
// Unsorted
    const FONT_41 = 'Unsorted/_trap_lead.sf2';
    const FONT_42 = 'Unsorted/SFT_115_D_Mood_Chord.sf2';

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
    )
    {

        // Mode optimiste
        $success = true;
        $message = '';

        try {
            $tmp_dir = self::TMP_DIR;
            mkdir($tmp_dir . "/output", 0777, true);
            $output_dir = $tmp_dir . "/output";


            $this->songs["A"] = $this->convertor($midiDataA, $midiDataA_sfID);
            $this->songs["B"] = $this->convertor($midiDataB, $midiDataB_sfID);
            $this->songs["C"] = $this->convertor($midiDataC, $midiDataC_sfID);
            $this->songs["D"] = $this->convertor($midiDataD, $midiDataD_sfID);
            $this->songs["E"] = $this->convertor($midiDataE, $midiDataE_sfID);
            $this->songs["F"] = $this->convertor($midiDataF, $midiDataF_sfID);
            $this->songs["G"] = $this->convertor($midiDataG, $midiDataG_sfID);
            $this->songs["H"] = $this->convertor($midiDataH, $midiDataH_sfID);
            $this->songs["CH"] = $this->convertor($midiDataCH, $midiDataCH_sfID);
            $this->songs["I"] = $this->convertor($midiDataI, $midiDataI_sfID);
            $this->songs["J"] = $this->convertor($midiDataJ, $midiDataJ_sfID);
            $this->songs["K"] = $this->convertor($midiDataK, $midiDataK_sfID);
            $this->songs["L"] = $this->convertor($midiDataL, $midiDataL_sfID);
            $this->songs["M"] = $this->convertor($midiDataM, $midiDataM_sfID);
            $this->songs["N"] = $this->convertor($midiDataN, $midiDataN_sfID);
            $this->songs["O"] = $this->convertor($midiDataO, $midiDataO_sfID);
            $this->songs["P"] = $this->convertor($midiDataP, $midiDataP_sfID);
            $this->songs["Q"] = $this->convertor($midiDataQ, $midiDataQ_sfID);
            $this->songs["R"] = $this->convertor($midiDataR, $midiDataR_sfID);
            $this->songs["S"] = $this->convertor($midiDataS, $midiDataS_sfID);
            $this->songs["T"] = $this->convertor($midiDataT, $midiDataT_sfID);
            $this->songs["U"] = $this->convertor($midiDataU, $midiDataU_sfID);
            $this->songs["V"] = $this->convertor($midiDataV, $midiDataV_sfID);
            $this->songs["W"] = $this->convertor($midiDataW, $midiDataW_sfID);
            $this->songs["X"] = $this->convertor($midiDataX, $midiDataX_sfID);
            $this->songs["Y"] = $this->convertor($midiDataY, $midiDataY_sfID);
            $this->songs["Z"] = $this->convertor($midiDataZ, $midiDataZ_sfID);
            $this->songs["AA"] = $this->convertor($midiDataAA, $midiDataAA_sfID);
            $this->songs["AB"] = $this->convertor($midiDataAB, $midiDataAB_sfID);
            $this->songs["AC"] = $this->convertor($midiDataAC, $midiDataAC_sfID);
            $this->songs["AD"] = $this->convertor($midiDataAD, $midiDataAD_sfID);
            $this->songs["AE"] = $this->convertor($midiDataAE, $midiDataAE_sfID);


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

            $soundfont = dirname(__DIR__) . '/soundfonts/' . constant('self::FONT_' . $type);

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