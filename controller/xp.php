<?php


namespace Controller;


use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Model\xpModel;

class xp
{
    private $model;
    private $userid;
    private $groupid;

    /**
     * xp constructor.
     * @param $userid
     */
    public function __construct($userid, $groupid)
    {
        $this->userid = $userid;
        $this->groupid = $groupid;
        $this->model = new xpModel($userid, $groupid);
    }

    public function isFound()
    {
        if ($this->model->hasXP()) {
            $reply = new TextMessageBuilder('your XP found');
        } else {
            $reply = new TextMessageBuilder('your XP not found');

        }
        return $reply;
    }

    public function checkXP()
    {
        $xp = $this->model->checkXP();
        $reply = new TextMessageBuilder("XP kamu = " . $xp);
        return $reply;
    }
}