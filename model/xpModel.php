<?php

namespace Model;

use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class xpModel extends objectDB
{
    private $userid;
    private $groupid;

    public function __construct($userid, $groupid)
    {
        parent::__construct();
        $this->userid = $userid;
        $this->groupid = $groupid;
        $this->addXP();
    }

    public function hasXP()
    {
        return $this->db->has('user', ['userid' => $this->userid]);
    }

    public function addXP()
    {
        if ($this->hasXP()) {
            $xpa = $this->db->get('user', 'xp', ['userid' => $this->userid]);
            $xpp = rand(1, 2);
            $xpb = $xpa + $xpp;

            return $this->db->update('user', ['xp' => $xpb, 'groupid' => $this->groupid], ['userid' => $this->userid]);
        } else {
            $a = $this->db->insert('user', ['userid' => $this->userid, 'groupid' => $this->groupid]);
            if ($a) {
                $this->addXP();
                return true;
            } else {
                return false;
            }
        }
    }

    public function getXP()
    {
        return $this->db->get('user', 'xp', ['userid' => $this->userid]);
    }

    public function getLeaderboard()
    {
        global $bot;
        $db = $this->db->select('user', ['userid', 'xp'], ['ORDER' => ['user' => 'DESC'], 'LIMIT' => 10]);
        if (!$db) {
            $bot->pushMessage("U6f3a4276a41f0a7eb3310fb2f43b4419", new TextMessageBuilder(print_r($this->db->error(), 1)));
        }
    }

    public function getGroupLeaderboard()
    {
        return $this->db->select('user', ['userid', 'xp'], ['ORDER' => ['user' => 'DESC'], 'LIMIT' => 10, 'groupid' => $this->groupid]);
    }
}
