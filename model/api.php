<?php


namespace Model;


class api extends AobjectDB
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllKeywords(){
        return $this->db->select("keywords","*");
    }

    public function getAllImageKeywords(){
        return $this->db->select("imagekeywords","*");
    }

    public function getStatsKeyword(){
        return $this->db->query("SELECT `groupid`, COUNT(*) as jumlah FROM keywords GROUP BY `groupid`")->fetchAll();

    }

}