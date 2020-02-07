<?php

namespace Model;

use Controller\debug;
use Exception;
use Medoo\Medoo;

class aobjectDB
{
    public $db;

    /**
     * objectDB constructor.
     */
    public function __construct()
    {
        $this->db = null;

        try {
            $this->db = new Medoo([
                'database_type' => 'mysql',
                'database_name' => 'ririla',
                'server'        => '127.0.0.1',
                'port'          => '57181',
                'username'      => 'azure',
                'password'      => '6#vWHD_$',
            ]);
        } catch (Exception $e) {
            debug::debugToMe($e->getMessage());
        }
    }
}
