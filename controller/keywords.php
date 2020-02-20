<?php
namespace Controller;

use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class keywords{

    private $reserved_keywords = ["gacha","list","gacha banyak","gacha kontol","xp","lb","lbg","gid","uid"];

    public function __construct($userid, $groupid, LINEBot $bot)
    {
        $this->userid = $userid;
        $this->groupid = $groupid;
        $this->bot = $bot;
        $this->model = new \Model\keywords();
    }

    function addKeyword($keyword,$reply){
        if(in_array($keyword,$this->reserved_keywords)){
            $reply = new TextMessageBuilder("Ah... you can't do this...\nKeywords tersebut sudah dipakai oleh aku >..<");
        }
        else if($this->model->checkIsExist($keyword, $this->groupid)){
            $reply = new TextMessageBuilder("Maaf keyword sudah ada!");
        }
        else{
            if($this->model->addKeyword($keyword,$reply,$this->groupid)){
                $reply = new TextMessageBuilder("Keyword sudah ditambahkan!");
            }
            else{
                $reply = new TextMessageBuilder("Ada kesalahan dalam menambahkan keyword...?");
            }
        }
        return $reply;
    }

    function getKeyword($keyword){
        if($this->model->checkKeywordExist($keyword, $this->groupid)){
            $reply = $this->model->getKeyword($keyword,$this->groupid);
            $foo = new TextMessageBuilder($reply);
            return $foo;
        }
        else if ($this->model->checkImageKeywordExist($keyword, $this->groupid)){
            $reply = $this->model->getImageKeyword($keyword,$this->groupid);
            $foo = new LINEBot\MessageBuilder\ImageMessageBuilder($reply,$reply);
            return $foo;
        }
        else{
            return false;
        }
    }

    function addImageKeyword($keyword){
        if(in_array($keyword,$this->reserved_keywords)){
            $reply = new TextMessageBuilder("Ah... you can't do this...\nKeywords tersebut sudah dipakai oleh aku >..<");
            return $reply;
        }
        else if(!$this->model->checkIsExist($keyword, $this->groupid)) {
            if ($this->model->uploadImageExist($this->groupid)) {
                $reply = new TextMessageBuilder("Kamu belum mengirimkan gambar untuk keyword sebelumnya.. >..<");
                return $reply;
            } else {
                if ($this->model->addImageKeyword($keyword, $this->groupid)) {
                    $reply = new TextMessageBuilder("Silahkan Kirim gambarnya!");
                    return $reply;
                } else {
                    return false;
                }
            }
        }
        else{
            $reply = new TextMessageBuilder("Maaf keyword sudah ada!");
            return $reply;
        }
    }

    function uploadImageKeyword($messageID){
        if($this->model->uploadImageKeyword($this->groupid, $messageID)) {
            $reply = new TextMessageBuilder("Keyword Berhasil ditambahkan!");
            return $reply;
        }
        else {
            return false;
        }
    }

    function uploadImageExist(){
        return $this->model->uploadImageExist($this->groupid);
    }

    function deleteKeyword($keyword){
        if($this->model->deleteKeywords($keyword,$this->groupid)){
            return new TextMessageBuilder("Keyword Berhasil dihapus!");
        }
        else{
            return new TextMessageBuilder("Keyword tidak ditemukan :(");
        }
    }

    function getAllKeywordsGroup(){
        $sentence = "Keywords yang tersedia : \n";
        $model = $this->model->getAllKeywordsGroup($this->groupid);
        foreach ($model as $a => $d){
            $f = $a+1;
            $sentence = $sentence.$f.". ".$d['keyword'];
            $sentence = $sentence."\n";
        }
        return new TextMessageBuilder($sentence);
    }
}