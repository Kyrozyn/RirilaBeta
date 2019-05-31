<?php


namespace Controller;


use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class admin
{
    private $userid;
    private $groupid;

    /**
     * admin constructor.
     * @param $groupid
     */
    public function __construct($userid, $groupid)
    {
        $this->userid = $userid;
        $this->groupid = $groupid;
    }


    function sendGroupID()
    {
        $reply = new TextMessageBuilder($this->groupid);
        return $reply;
    }

    function sendUserID()
    {
        $reply = new TextMessageBuilder($this->userid);
        return $reply;
    }

    static function push($text)
    {
        $reply = new TextMessageBuilder($text);
        return $reply;
    }
}