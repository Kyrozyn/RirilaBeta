<?php

namespace Model;

use Controller\debug;
use Exception;
use Medoo\Medoo;

class objectDB
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
                'database_type' => getenv('DATABASE_TYPE'),
                'database_name' => getenv('DATABASE_NAME'),
                'server'        => getenv('DATABASE_SERVER'),
                'port'          => getenv('DATABASE_PORT'),
                'username'      => getenv('DATABASE_USERNAME'),
                'password'      => getenv('DATABASE_PASSWORD'),
            ]);
        } catch (Exception $e) {
            debug::debugToMe($e->getMessage());
        }
    }
}
