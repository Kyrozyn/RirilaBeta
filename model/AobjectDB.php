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
                'database_type' => getenv("DATABASE_TYPE") ? getenv("DATABASE_TYPE") : "mysql",
                'database_name' => getenv("DATABASE_NAME") ? getenv("DATABASE_NAME") : "ririla",
                'server'        => getenv("DATABASE_SERVER") ? getenv("DATABASE_SERVER") : '127.0.0.1',
                'port'          => getenv("DATABASE_PORT") ? getenv("DATABASE_PORT") : "3306",
                'username'      => getenv("DATABASE_USERNAME") ? getenv("DATABASE_USERNAME") : "root",
                'password'      => getenv("DATABASE_NAME") ? getenv("DATABASE_NAME") : '',
            ]);
            $this->db->query("SET GLOBAL event_scheduler='ON'");
        } catch (Exception $e) {
            debug::debugToMe($e->getMessage());
        }
    }
}
