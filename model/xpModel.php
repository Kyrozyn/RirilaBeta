<?php


namespace Model;


class xpModel extends objectDB
{
    private $userid;

    public function __construct($userid)
    {
        parent::__construct();
        $this->userid = $userid;
    }

    function hasXP()
    {
        return $this->db->has("xp", ['userid' => $this->userid]);
    }


}