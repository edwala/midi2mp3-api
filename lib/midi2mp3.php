<?php

class Midi2Mp3
{

    // dossier temporaire pour le traitement lilypond
    const TMP_DIR = '/tmp/midi2mp3';
    const SOUNDFONT = 'TR-808.sf2';
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
                'api' => '1.1',
                'fluildsynth' => FLUIDSYNTH_VERSION,
                'lame' => LAME_VERSION
            ),
            'description' => 'Midi to Mp3 file convertion',
        );
    }


    //-----------------------------------------
    // CONVERTION
    //-----------------------------------------

    public function convert($midiDataA, $midiDataB)
    {

        // Mode optimiste
        $success = true;
        $message = '';

        try {


            $tmp_dir = self::TMP_DIR;
            mkdir($tmp_dir . "/output", 0777, true);
            $output_dir = $tmp_dir . "/output";

            if (isset($midiDataA) && $midiDataA != "") {
                $this->songs["A"] = $this->convertor($midiDataA, "A");
            }
            if (isset($midiDataB) && $midiDataB != "") {
                $this->songs["B"] = $this->convertor($midiDataB, "B");
            }

            if (isset($this->songs["B"])) {
                $cmdMix = "ffmpeg -i /tmp/midi2mp3/A/A.mp3 -i /tmp/midi2mp3/B/B.mp3 -filter_complex amix=inputs=2:duration=longest /tmp/midi2mp3/output/output.mp3";
                //$cmdMix = "ffmpeg -i /tmp/midi2mp3/A/A.mp3 -i /tmp/midi2mp3/B/B.mp3 -filter_complex amerge=inputs=2 -ac 2 /tmp/midi2mp3/output/output.mp3";
                $file = "/tmp/midi2mp3/output/output.mp3";
            } else {
                $file = "/tmp/midi2mp3/A/A.mp3";
                //$cmdMix = "ffmpeg -i /tmp/midi2mp3/A/A.mp3 -filter_complex amerge=inputs=1 -ac 2 /tmp/midi2mp3/output/output.mp3";
            }
            //$cmdMix = "ffmpeg -i /tmp/midi2mp3/A/A.mp3 -i /tmp/midi2mp3/B/B.mp3 -filter_complex amerge=inputs=2 -ac 2 /tmp/midi2mp3/output/output.mp3";
            //$cmdMix = "ffmpeg -i $this->songs[0] -i $this->songs[1] -filter_complex amix=inputs=2:duration=longest $output_dir/output.mp3";
            exec($cmdMix, $op, $retVal);

            // Compose la réponse
            //$result = $this->getConvertResponse($success, $message);

            // Efface les données de session
            //$this->deleteSessionData();

            // Retourne le résultat


            if (is_file($file)) {
                $file = base64_encode(file_get_contents($file));
            }

        } catch (Exception $ex) {

            // Compose le retour ERREUR
            $success = false;
            $message = $ex->getMessage();

        }

        $cmd = "rm -rf " . self::TMP_DIR;
        exec($cmd, $op, $retVal);

        //return base64_encode(file_get_contents($this->songs[0]));

        return array(
            '$success' => $success,
            '$message' => $message,
            '$op' => $op,
            '$retVal' => $retVal,
            //'1encode' => base64_encode(file_get_contents($this->songs[0])),
            //'2encode' => base64_encode(file_get_contents($this->songs[1])),
            //'1' => $this->songs[0],
            //'2' => $this->songs[1],
            'mp3' => base64_encode(file_get_contents($file)),
            '$file' => $file
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

            // Initialisation
            $this->initPath($type);
            mkdir($this->dir, 0777, true);
            file_put_contents($this->inputFile, base64_decode($midiData, true));

            // Execution FluidSynth
            $soundfont = dirname(__DIR__) . '/soundfonts/' . self::SOUNDFONT;
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