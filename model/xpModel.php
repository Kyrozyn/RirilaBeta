<?php

namespace Model;

use Controller\debug;

class xpModel extends aobjectDB
{
    private $userid;
    private $groupid;

    public function __construct($userid, $groupid)
    {
        parent::__construct();
        $this->userid = $userid;
        $this->groupid = $groupid;
        $this->addXP();
        //debug::debugToMe(print_r($this->db->error(),1));
    }

    public function hasXP()
    {
        return $this->db->has('user', ['userid' => $this->userid]);
    }

    private function isUpdated()
    {
        return $this->db->get('user', 'isUpdated', ['userid' => $this->userid]);
    }

    private function Update()
    {
        return $this->db->update('user', ['isUpdated' => true], ['userid' => $this->userid]);
    }

    public function addXP()
    {
        if ($this->hasXP()) {
            if (!$this->isUpdated()) {
                $xpa = $this->db->get('user', 'xp', ['userid' => $this->userid]);
                debug::debugToMe(print_r($this->db->error(), 1));
                $xpp = rand(1, 2);
                $xpb = $xpa + $xpp;
                $this->db->update('user', ['xp' => $xpb, 'groupid' => $this->groupid], ['userid' => $this->userid]);

                return $this->Update();
            } else {
                return true;
            }
        } else {
            $a = $this->db->insert('user', ['userid' => $this->userid, 'groupid' => $this->groupid]);
            debug::debugToMe(print_r($this->db->error(), 1));
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
        return $this->db->select('user', ['userid', 'xp'], ['ORDER' => ['xp' => 'DESC'], 'LIMIT' => 10]);
    }

    public function getGroupLeaderboard()
    {
        return $this->db->select('user', ['userid', 'groupid', 'xp'], ['ORDER' => ['xp' => 'DESC'], 'LIMIT' => 10, 'groupid' => $this->groupid]);
    }
}
