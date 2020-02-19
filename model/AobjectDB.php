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
                'database_name' => getenv('DATABASE_NAME'),
                'server'        => '127.0.0.1',
                'port'          => getenv('DATABASE_PORT'),
                'username'      => getenv('DATABASE_USERNAME'),
                'password'      => getenv('DATABASE_PASSWORD'),
            ]);
            $this->db->query("SET GLOBAL event_scheduler='ON'");
        } catch (Exception $e) {
            debug::debugToMe($e->getMessage());
        }
    }
}
