<?php

use Medoo\Medoo;

try {
    $db = new Medoo([
        'database_type' => 'pgsql',
        'database_name' => getenv('DATABASE_NAME'),
        'server'        => getenv('DATABASE_SERVER'),
        'username'      => getenv('DATABASE_USERNAME'),
        'password'      => getenv('DATABASE_PASSWORD'),
    ]);
} catch (Exception $e) {
    file_put_contents('php://stderr', $e->getMessage());
    file_put_contents('php://stderr', "\nError on Db... Sorry...");
}
