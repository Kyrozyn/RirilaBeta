<?php

namespace Controller;

use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Model\xpModel;

class xp
{
    private $model;
    private $userid;
    private $groupid;
    private $bot;

    /**
     * xp constructor.
     *
     * @param $userid
     * @param $groupid
     * @param LINEBot $bot
     */
    public function __construct($userid, $groupid, LINEBot $bot)
    {
        $this->userid = $userid;
        $this->groupid = $groupid;
        $this->bot = $bot;
        $this->model = new xpModel($userid, $groupid);
    }

    /**
     * @return TextMessageBuilder
     */
    public function isFound()
    {
        if ($this->model->hasXP()) {
            $reply = new TextMessageBuilder('your XP found');
        } else {
            $reply = new TextMessageBuilder('your XP not found');
        }

        return $reply;
    }

    /**
     * @return TextMessageBuilder
     */
    public function getXP()
    {
        $xp = $this->model->getXP();
        $reply = new TextMessageBuilder('XP kamu = '.$xp);

        return $reply;
    }

    public function addXP()
    {
        $this->model->addXP();
    }

    public function getLeaderboard()
    {
        $header = '***Leaderboard***';
        $angka = 0;
        $a = 0;
        $array = $this->model->getLeaderboard();
        $len = count($array);
        $balas = null;
        foreach ($array as $id) {
            $angka = $angka + 1;
            $profile = $this->bot->getProfile($id['userid']);
            $json = $profile->getJSONDecodedBody();
            $nama = $json['displayName'];
            if (empty($nama)) {
                $nama = '????';
            }
            if ($a == 0) {
                $balas = $balas.$header."\n";
            }
            $balas = $balas.$angka.'. '.$nama.' : '.$id['xp'];
            if ($a != $len - 1) {
                $balas = $balas."\n";
            }
            $a++;
        }
        $reply = new TextMessageBuilder($balas);

        return $reply;
    }

    public function getGroupLeaderBoard()
    {
        $header = '***Group Leaderboard***';
        $angka = 0;
        $a = 0;
        $array = $this->model->getGroupLeaderboard();
        $len = count($array);
        $balas = null;
        foreach ($array as $id) {
            $angka = $angka + 1;
            $profile = $this->bot->getGroupMemberProfile($id['groupid'], $id['userid']);
            $json = $profile->getJSONDecodedBody();
            $nama = $json['displayName'];
            if ($a == 0) {
                $balas = $balas.$header."\n";
            }
            $balas = $balas.$angka.'. '.$nama.' : '.$id['xp'];
            if ($a != $len - 1) {
                $balas = $balas."\n";
            }
            $a++;
        }
        $reply = new TextMessageBuilder($balas);

        return $reply;
    }
}
