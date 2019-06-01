<?php

namespace Controller;

use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
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
        $balas = null;
        foreach ($this->model->getLeaderboard() as $id) {
            $angka = $angka + 1;
            $profile = $this->bot->getProfile($id['userid']);
            $json = $profile->getJSONDecodedBody();
            $nama = $json['displayName'];
            if (empty($nama)) {
                $nama = '????';
            }
            $balas = $balas.$angka.'. '.$nama.' : '.$id['xp'];
            if ($angka < 10) {
                $balas = $balas."\n";
            }
        }
        $satu = new TextMessageBuilder($header);
        $dua = new TextMessageBuilder($balas);
        $reply = new MultiMessageBuilder();
        $reply->add($satu);
        $reply->add($dua);

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
            if (empty($nama)) {
                $nama = '????';
                $warn = new TextMessageBuilder('Hmm... Apa kalian semua sudah add aku? Aku tidak bisa menampilkan namamu :(');
            }
            $balas = $balas.$angka.'. '.$nama.' : '.$id['xp'];
            if (!$a == $len - 1) {
                $balas = $balas."\n";
            }
            $a++;
        }
        $satu = new TextMessageBuilder($header);
        $dua = new TextMessageBuilder($balas);
        $reply = new MultiMessageBuilder();
        $reply->add($satu);
        $reply->add($dua);
        if (isset($warn)) {
            $reply->add($warn);
        }

        return $reply;
    }
}
