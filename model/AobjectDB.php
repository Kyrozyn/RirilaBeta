<?php

namespace Model;

use Controller\debug;
use Exception;
use Medoo\Medoo;

class AobjectDB
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
                'database_name' => 'heroku_8936a7e2cfac410',
                'server'        => 'us-cdbr-iron-east-04.cleardb.net',
                'port'          => '3306',
                'username'      => 'b8576eef192d88',
                'password'      => 'cd90f92c',
            ]);
        } catch (Exception $e) {
            debug::debugToMe($e->getMessage());
        }
    }
}
