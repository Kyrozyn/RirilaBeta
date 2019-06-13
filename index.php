<?php

/** @noinspection PhpUnusedParameterInspection */
/** @noinspection PhpUndefinedMethodInspection */
require __DIR__.'/vendor/autoload.php';
//include add class..
foreach (glob('controller/*.php') as $filename) {
    include $filename;
}
foreach (glob('settings/*.php') as $filename) {
    include $filename;
}
foreach (glob('model/*.php') as $filename) {
    include $filename;
}
//
use Controller\admin;
use Controller\anime;
use Controller\debug;
use Controller\gacha;
use Controller\textParser;
use Controller\xp;
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

$app->post('/bot', function (Request $req, Response $res) use ($bot) {
    try {
        //Lets make a log..
        //Doing Magic
        $signature = $req->getHeader(HTTPHeader::LINE_SIGNATURE);
        $result = null;
        if (empty($signature)) {
            return $res->withStatus(400, 'Bad Request');
        }
        $events = $bot->parseEventRequest($req->getBody(), $signature[0]);
        foreach ($events as $event) {
            $admin = new admin($event->getUserId(), $event->getGroupId());
            $xp = new xp($event->getUserId(), $event->getGroupId(), $bot);
            $anime = new anime();
            $text = new textParser($event->getText());
            $reply = null;
            if ($event->isUserEvent()) {
                $bot->replyText($event->getReplyToken(), 'Hai!');
            }
            if ($event->isGroupEvent()) {
                switch ($text->textKecil) {
                    //Gacha
                    case 'gacha':
                        $reply = gacha::gachaSatu();
                        break;
                    case 'gacha banyak':
                    case 'gacha kontol':
                        $reply = gacha::gachaBanyak();
                        break;
                    //XP
                    case 'xp':
                        $reply = $xp->getXP();
                        break;
                    case 'lb':
                        $reply = $xp->getLeaderboard();
                        break;
                    case 'lbg':
                        $reply = $xp->getGroupLeaderBoard();
                        break;
                    //Admin
                    case 'gid':
                        $reply = $admin->sendGroupID();
                        break;
                    case 'uid':
                        $reply = $admin->sendUserID();
                        break;
                }
                switch (strtolower($text->textBintang[0])) {
                    case 'anime':
                    case 'nim':
                        if (isset($text->textBintang[2])) {
                            $reply = $anime->searchAnime($text->textBintang[1], $text->textBintang[2]);
                        } else {
                            $reply = $anime->searchAnime($text->textBintang[1]);
                        }
                        break;
                    case 'chara':
                    case 'character':
                        if (isset($text->textBintang[2])) {
                            $reply = $anime->searchChara($text->textBintang[1], $text->textBintang[2]);
                        } else {
                            $reply = $anime->searchChara($text->textBintang[1]);
                        }
                        break;
                }

                $cek = $bot->replyMessage($event->getReplyToken(), $reply);
                if (!$cek->isSucceeded()) {
                    debug::debugToMe(print_r($cek->getJSONDecodedBody(), 1));
                }
            }
        }
    } catch (Exception $e) {
        debug::debugToMe('Exception : ' . $e->getMessage());
    }
    //debug::debugToMe('Bot is doing well!');
    return true;
});

try {
    $app->run();
} catch (Exception $e) {
    debug::debugToMe('Exception while run : ' . $e->getMessage());
}
