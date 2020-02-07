<?php

namespace Model;

use Cloudinary\Uploader;

class keywords extends aobjectDB
{

    public function __construct()
    {
        parent::__construct();
    }
    function checkIsExist($keyword,$groupid){
        return $this->db->has("keywords", [
            "AND" => [
                "groupid" => $groupid,
                "keyword" => $keyword
            ]
        ]) ? true : false;
    }

    function getKeyword($keyword,$groupid){
        if($this->checkIsExist($keyword,$groupid)){
            $reply = $this->db->get("keywords","reply",[
                "AND" => [
                    "groupid" => $groupid,
                    "keyword" => $keyword
                ]]);
            return $reply;
        }
        else{
            return false;
        }
    }

    function addKeyword($keyword,$reply,$groupid){
            $insert = $this->db->insert("keywords",[
                "keyword" => $keyword,
                "reply" => $reply,
                "groupid" => $groupid
            ]);
            if($insert){
                return true;
            }
            else{
                return false;
            }
    }

    function addImageKeyword($keyword,$groupid){
        $insert = $this->db->insert("imageKeywords",[
            "keyword" => $keyword,
            "reply" => 0,
            "groupid" => $groupid
        ]);
        if($insert){
            return true;
        }
        else{
            return false;
        }
    }

    function uploadImageKeyword($groupID,$messageID){
        if($this->uploadImageExist($groupID)){
            $host = $_SERVER['HTTP_HOST'];
            $hostimage = "https://" . $host . "/content/" . $messageID;
            $res = Uploader::upload($hostimage) ? true : false;
            $url = $res['secure_url'];
            $this->db->update("imagekeywords",["reply" => $url],[
                "AND" => [
                    "groupid" => $groupID,
                    "reply" => 0
                ]
            ]);
            file_put_contents('php://stderr', 'Debug : '.print_r($res,1));
            return true;
        }
        else{
            return false;
        }
    }

    function uploadImageExist($groupID){
        return $this->db->has("imagekeywords", [
            "AND" => [
                "groupid" => $groupID,
                "reply" => 0
            ]
        ]);
    }
}