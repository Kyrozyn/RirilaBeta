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

    function hasXP()
    {
        return $this->db->has("xp", ['userid' => $this->userid]);
    }

    function addXP()
    {
        if ($this->hasXP()) {
            $xpa = $this->db->get("xp", "xp", ["userid" => $this->userid]);
            $xpp = rand(1, 2);
            $xpb = $xpa + $xpp;
            return $this->db->update("xp", ["xp" => $xpb, "groupid" => $this->groupid], ["userid" => $this->userid]);
        } else {
            $a = $this->db->insert("xp", ["userid" => $this->userid, "groupid" => $this->groupid]);
            if ($a) {
                $this->addXP();
                return true;
            } else {
                return false;
            }

        }
    }

    function getXP()
    {
        return $this->db->get("xp", "xp", ["userid" => $this->userid]);
    }

    function getLeaderboard()
    {
        return $this->db->select("xp", ["userid", "xp"], ["ORDER" => ["xp" => "DESC"], "LIMIT" => 10]);
    }

    function getGroupLeaderboard()
    {
        return $this->db->select("xp", ["userid", "xp"], ["ORDER" => ["xp" => "DESC"], "LIMIT" => 10, "groupid" => $this->groupid]);
    }
}