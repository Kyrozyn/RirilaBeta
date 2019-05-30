<?php

/** @noinspection PhpUndefinedMethodInspection */
require __DIR__.'/vendor/autoload.php';
require 'def.php';
require 'db.php';
//include add class..
include "controller/gacha.php";

//
use Controller\gacha;
use LINE\LINEBot;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

$app = new App(['settings' => ['displayErrorDetails' => true]]);
//
$httpClient = new CurlHTTPClient($channel_access_token);
$bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);
//

$app->post('/bot', function (Request $req, Response $res) use ($bot, $db) {
    try {
        //include things
        include 'textParser.php';

        //Lets make a log..
        file_put_contents('php://stderr', 'Body : '.file_get_contents('php://input'));

        //Doing Magic
        $signature = $req->getHeader(HTTPHeader::LINE_SIGNATURE);
        $result = null;
        if (empty($signature)) {
            return $res->withStatus(400, 'Bad Request');
        }
        $events = $bot->parseEventRequest($req->getBody(), $signature[0]);
        foreach ($events as $event) {
            $text = new textParser($event->getText());
            $reply = null;
            if ($event->isUserEvent()) {
                $bot->replyText($event->getReplyToken(), 'Hai!');
            }
            if ($event->isGroupEvent()) {
                switch ($text->textKecil) {
                    case 'gacha':
                        $reply = gacha::gachaSatu();
                        break;
                    case 'gacha banyak':
                    case 'gacha kontol':
                        $reply = gacha::gachaBanyak();
                        break;
                }
                $bot->replyMessage($event->getReplyToken(), $reply);
            }
        }
    } catch (Exception $e) {
        file_put_contents('php://stderr', 'Exception : '.$e->getMessage());
    }

    return true;
});

try {
    $app->run();
} catch (Exception $e) {
    file_put_contents('php://stderr', 'Exception while run : '.$e->getMessage());
}
