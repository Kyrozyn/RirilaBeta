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

//
Cloudinary::config([
    'cloud_name'    => 'ririla-bot',
    'api_key'       => '589385422775558',
    'api_secret'    => 'lIXchV3VqoQw9JOQO4fRy4IJj8Y',
    'resource_type' => 'raw',
]);
//
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
            //Message Event
            $keywords = new \Controller\keywords($event->getUserId(), $event->getGroupId(), $bot);
            if ($event->getType() == 'message') {
                if ($event->getMessageType() == 'text') {
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
                            case 'add':
                                if (isset($text->textBintang[1]) and isset($text->textBintang[2])) {
                                    $reply = $keywords->addKeyword($text->textBintang[1], $text->textBintang[2]);
                                } else {
                                    $reply = new LINEBot\MessageBuilder\TextMessageBuilder('Mohon isi keyword sama replynya ya ^_^');
                                }
                                break;
                            case 'addpic':
                                if (isset($text->textBintang[1])) {
                                    $reply = $keywords->addImageKeyword($text->textBintang[1]);
                                } else {
                                    $reply = new LINEBot\MessageBuilder\TextMessageBuilder('Mohon isi keywordnya ya ^_^');
                                }
                                break;
                            default:
                                $reply = $keywords->getKeyword($text->textKecil);
                                break;
                        }
                        if (!empty($reply)) {
                            $cek = $bot->replyMessage($event->getReplyToken(), $reply);
                            if (!$cek->isSucceeded()) {
                                debug::debugToMe(print_r($cek->getJSONDecodedBody(), 1));
                            }
                        }
                    }
                } elseif ($event->getMessageType() == 'image') {
                    if ($keywords->uploadImageExist()) {
                        $reply = $keywords->uploadImageKeyword($event->getMessageID());
                        $bot->replyMessage($event->getReplyToken(), $reply);
                    }
                }
            }
        }
    } catch (Exception $e) {
        debug::debugToMe('Exception : '.$e->getMessage());
    }
    //debug::debugToMe('Bot is doing well!');
    return true;
});

$app->get('/content/{messageId}', function ($req, $res) use ($bot) {
    // get message content
    $route = $req->getAttribute('route');
    $messageId = $route->getArgument('messageId');
    $result = $bot->getMessageContent($messageId);

    // set response
    $res->write($result->getRawBody());

    return $res->withHeader('Content-Type', $result->getHeader('Content-Type'));
});

try {
    $app->run();
} catch (Exception $e) {
    debug::debugToMe('Exception while run : '.$e->getMessage());
}
