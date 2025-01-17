# midi2mp3-api

REST API (Docker, PHP, Slim, FluidSynth, Lame) for MIDI to MP3 convertion.

## Prerequisite

- Install [composer](https://getcomposer.org/)
- Install [docker](https://www.docker.com/)

## Start

sqlite3 -init /var/www/lib/db.sqlite

#### Installation

```bash
composer install
```

#### Build

```bash
docker image build -t midi2mp3-api .

docker build -t musicmonkey .
docker tag musicmonkey:latest 775008567290.dkr.ecr.eu-central-1.amazonaws.com/musicmonkey:latest
aws lightsail push-container-image --region eu-central-1 --service-name mm-midi2mp3 --label 1 --image 775008567290.dkr.ecr.eu-central-1.amazonaws.com/musicmonkey:latest


```

#### Run

```bash
docker run -p 8999:80 midi2mp3-api
docker run -p 8999:80 775008567290.dkr.ecr.eu-central-1.amazonaws.com/musicmonkey 
```

## API Usage

### Endpoint http://[docker-machine]/info

#### Request

- Verb : GET
- No parameter

#### Response

- Content-Type : Application/json

```json
{
  "apiName": "midi2mp3",
  "version": "1",
  "description": "Midi to MP3 Convertion"
}
```  

### Endpoint http://[docker-machine]/convert

#### Request

- Verb : POST
- Content-Type : Application/json
- Parameters :
  -- midiData : Base64 encoded Midi file

```json
{
  "midiData": "TVRoZAAAAAYAAQACAYBNVHJrAAAAUwD/Aw1jb250cm.....AP8BC"
}
```

#### Response

- Content-Type : Application/json

```json  
{
  "statusCode": "OK|ERROR",
  "message": "Information complement on error",
  "base64Mp3Data": "oAsAdkAJA8WoMAkDwAAJA+WoMAkD4AAJBAWoMAkEAAAJB....vAA==",
  "logs": [
    {
      "title": "FluidSynth : midi -> wav convertion",
      "content": "FluidSynth log details"
    },
    {
      "title": "Lame : wav -> mp3 convertion",
      "content": "Lame log details"
    }
  ]
}
```

## Also read

- [How to contribute](CONTRIBUTING.md)
- [Code of conduct](CODE_OF_CONDUCT.md)
