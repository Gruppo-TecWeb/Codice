<?php

namespace DB;

class DBAccess
{
    private const DB_HOST = "localhost";
    private const DB_NAME = "demo";
    private const DB_USER = "root";
    private const DB_PASS = "";

    private $connection;

    public function openDBConnection()
    {
        $this->connection = mysqli_connect(
            self::DB_HOST,
            self::DB_USER,
            self::DB_PASS,
            self::DB_NAME
        );
        return mysqli_connect_errno() == 0;
    }

    public function closeDBConnection()
    {
        mysqli_close($this->connection);
    }
}
