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
        file_put_contents('php://stderr', 'Body : '.file_get_contents('php://input'));

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
                    //Admin
                    case 'gid':
                        $reply = $admin->sendGroupID();
                        break;
                    case 'uid':
                        $reply = $admin->sendGroupID();
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
                }
                $bot->replyMessage($event->getReplyToken(), $reply);
            }
        }
    } catch (Exception $e) {
        file_put_contents('php://stderr', 'Exception : '.$e->getMessage());
    }

    return true;
});

$app->get('/send/{groupid}/{message}', function (Request $req, Response $res, $args) use ($bot) {
    $groupid = $args['groupid'];
    $message = $args['message'];
    $text = admin::push($message);
    $bot->pushMessage($groupid, $text);
});

try {
    $app->run();
} catch (Exception $e) {
    file_put_contents('php://stderr', 'Exception while run : '.$e->getMessage());
}
