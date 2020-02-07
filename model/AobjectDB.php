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
                'server'        => 'localhost',
                'port'          => '3306',
                'username'      => 'root',
                'password'      => '',
            ]);
        } catch (Exception $e) {
            debug::debugToMe($e->getMessage());
        }
    }
}
