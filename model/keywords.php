<?php

namespace Model;

use Cloudinary\Uploader;

class keywords extends aobjectDB
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkIsExist($keyword, $groupid)
    {
        return $this->db->has('keywords', [
            'AND' => [
                'groupid' => $groupid,
                'keyword' => $keyword,
            ],
        ]) ? true : false;
    }

    public function getKeyword($keyword, $groupid)
    {
        if ($this->checkIsExist($keyword, $groupid)) {
            $reply = $this->db->get('keywords', 'reply', [
                'AND' => [
                    'groupid' => $groupid,
                    'keyword' => $keyword,
                ], ]);

            return $reply;
        } else {
            return false;
        }
    }

    public function addKeyword($keyword, $reply, $groupid)
    {
        $insert = $this->db->insert('keywords', [
            'keyword' => $keyword,
            'reply'   => $reply,
            'groupid' => $groupid,
        ]);
        if ($insert) {
            return true;
        } else {
            return false;
        }
    }

    public function addImageKeyword($keyword, $groupid)
    {
        $insert = $this->db->insert('imageKeywords', [
            'keyword' => $keyword,
            'reply'   => 0,
            'groupid' => $groupid,
        ]);
        if ($insert) {
            return true;
        } else {
            return false;
        }
    }

    public function uploadImageKeyword($groupID, $messageID)
    {
        if ($this->db->has('imageKeywords', [
            'AND' => [
                'groupid' => $groupID,
                'reply' => 0,
            ],
        ])) {
            $host = $_SERVER['HTTP_HOST'];
            $hostimage = 'https://'.$host.'/content/'.$messageID;
            $res = Uploader::upload($hostimage) ? true : false;
            $url = $res['secure_url'];
            $this->db->update('imageKeywords', ['reply' => $url], [
                'AND' => [
                    'groupid' => $groupID,
                    'reply'   => 0,
                ],
            ]);

            return true;
        } else {
            return false;
        }
    }

    public function uploadImageExist($groupID)
    {
        return $this->db->has('imageKeywords', [
            'AND' => [
                'groupid' => $groupID,
                'reply'   => 0,
            ],
        ]) ? true : false;
    }
}
