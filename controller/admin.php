<?php


namespace Controller;


use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class admin
{
    private $groupid;

    /**
     * admin constructor.
     * @param $groupid
     */
    public function __construct($groupid)
    {
        $this->groupid = $groupid;
    }


    function sendGroupID()
    {
        $reply = new TextMessageBuilder($this->groupid);
        return $reply;
    }

    static function push($text)
    {
        $reply = new TextMessageBuilder($text);
        return $reply;
    }
}