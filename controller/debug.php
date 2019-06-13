<?php


namespace Controller;


use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class debug
{
    public static function debugLog($message)
    {
        file_put_contents('php://stderr', 'Debug : ' . $message);
    }

    public static function debugToMe($message)
    {
        global $bot;
        $bot->pushMessage(getenv('UAID_DEBUG'), new TextMessageBuilder($message));
    }
}