<?php

declare(strict_types=1);

namespace App;

class DB
{
    public static function getConnection(array $dbConfig)
    {
        $conn = new \mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['database']);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}
