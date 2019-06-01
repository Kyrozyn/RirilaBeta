<?php

namespace Model;

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
            $xpa = $this->db->get('user', 'user', ['userid' => $this->userid]);
            $xpp = rand(1, 2);
            $xpb = $xpa + $xpp;

            return $this->db->update('user', ['user' => $xpb, 'groupid' => $this->groupid], ['userid' => $this->userid]);
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
        return $this->db->get('user', 'user', ['userid' => $this->userid]);
    }

    public function getLeaderboard()
    {
        return $this->db->select('user', ['userid', 'xp'], ['ORDER' => ['user' => 'DESC'], 'LIMIT' => 10]);
    }

    public function getGroupLeaderboard()
    {
        return $this->db->select('user', ['userid', 'xp'], ['ORDER' => ['user' => 'DESC'], 'LIMIT' => 10, 'groupid' => $this->groupid]);
    }
}
