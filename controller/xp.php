<?php


namespace Controller;


use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Model\xpModel;

class xp
{
    private $model;
    private $userid;

    /**
     * xp constructor.
     * @param $userid
     */
    public function __construct($userid)
    {
        $this->userid = $userid;
        $this->model = new xpModel($userid);
    }

    public function checkXP()
    {
        if ($this->model->hasXP()) {
            $reply = new TextMessageBuilder('your XP found');
        } else {
            $reply = new TextMessageBuilder('your XP not found');

        }
        return $reply;
    }
}