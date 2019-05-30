<?php

/** @noinspection PhpUndefinedMethodInspection */
require __DIR__.'/vendor/autoload.php';
require 'def.php';
require 'db.php';

use LINE\LINEBot;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
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
            if ($event->isUserEvent()) {
                $bot->replyText($event->getReplyToken(), 'Hai!');
            }
            if ($event->isGroupEvent()) {
                switch ($text->textKecil) {
                    case 'test':
                        $bot->replyText($event->getReplyToken(), 'MASUK');
                        break;
                    case 'gacha':
                        $roll = random_int(1, 100);
                        if ($roll == 100) {
                            $bot->replyText($event->getReplyToken(), '5* Servant');
                        } elseif ($roll <= 99 && $roll > 96) {
                            $bot->replyText($event->getReplyToken(), '5* CE');
                        } elseif ($roll <= 96 && $roll > 92) {
                            $bot->replyText($event->getReplyToken(), '4* Servant');
                        } elseif ($roll <= 92 && $roll > 84) {
                            $bot->replyText($event->getReplyToken(), '4* CE');
                        } elseif ($roll <= 84 && $roll > 44) {
                            $bot->replyText($event->getReplyToken(), '3* Servant');
                        } else {
                            $bot->replyText($event->getReplyToken(), '3* CE');
                        }
                        break;
                    case 'gacha banyak':
                    case 'gacha kontol':
                        re :
                        $balas = null;
                        $ssr = 0;
                        $sr = 0;
                        $r = 0;
                        for ($a = 0; $a < 10; $a++) {
                            $roll = random_int(1, 100);
                            if ($roll == 100) {
                                $balas = $balas.'5* Servant';
                                $ssr = $ssr + 1;
                            } elseif ($roll <= 99 && $roll > 96) {
                                $balas = $balas.'5* CE';
                                $ssr = $ssr + 1;
                            } elseif ($roll <= 96 && $roll > 92) {
                                $balas = $balas.'4* Servant';
                                $sr = $sr + 1;
                            } elseif ($roll <= 92 && $roll > 84) {
                                $balas = $balas.'4* CE';
                                $sr = $sr + 1;
                            } elseif ($roll <= 84 && $roll > 44) {
                                $balas = $balas.'3* Servant';
                                $r = $r + 1;
                            } else {
                                $balas = $balas.'3* CE';
                                $r = $r + 1;
                            }
                            if ($a != 9) {
                                $balas = $balas."\n";
                            }
                        }
                        if ($ssr == 10 or $sr == 10 or $r == 10) {
                            goto re;
                        } else {
                            $text1 = new TextMessageBuilder($balas);
                            if ($sr < 2 and $ssr < 1) {
                                $rand = ['Ampas sekali hidup anda ^_^', 'Perbanyak tobat agar luck anda meningkat ^_^'];
                                $tx = $rand[array_rand($rand)];
                            } else {
                                $rand = ['Jangan lupa sikat gigi sebelum gacha ^_^', 'Jangan lupa puasa sebelum gacha ^_^', 'Jangan lupa makan sebelum gacha ^_^', 'Jangan lupa minum sebelum gacha ^_^'];
                                $tx = $rand[array_rand($rand)];
                            }
                            $text2 = new TextMessageBuilder('SSR = '.$ssr."\nSR = ".$sr."\nR =".$r."\n".$tx);
                            $satuin = new LINEBot\MessageBuilder\MultiMessageBuilder();
                            $satuin->add($text1);
                            $satuin->add($text2);
                            $bot->replyMessage($event->getReplyToken(), $satuin);
                        }
                        break;
                }
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
