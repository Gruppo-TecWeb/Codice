<?php

namespace DB;

class DBAccess
{
    private const DB_HOST = "localhost";
    private const DB_NAME = "fungo";
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

    public function executeSelectQuery($query)
    {
        $queryResult = mysqli_query($this->connection, $query) or die("Errore in DBAccess: " . mysqli_error($this->connection));
        if (mysqli_num_rows($queryResult) == 0) {
            return null;
        } else {
            $result = array();
            while ($row = mysqli_fetch_array($queryResult)) {
                $result[] = $row;
            }
            $queryResult->free();
            return $result;
        }
    }

    public function getlistaEventi()
    {
        $query = "SELECT e.id,
        e.titolo,
        e.descrizione,
        e.data,
        e.ora,
        e.luogo,
        s.annoinizio,
        s.meseinizio,
        s.tipoevento
        FROM eventi as e
        join stagioni as s on e.stagione = s.id 
        order by data desc";
        return $this->executeSelectQuery($query);
    }

    public function getEvento($id)
    {
        $query = "SELECT e.titolo,
        e.descrizione,
        e.data,
        e.ora,
        e.luogo,
        s.annoinizio,
        s.meseinizio,
        s.tipoevento
        FROM eventi as e
        join stagioni as s on e.stagione = s.id 
        where e.id = $id";
        return $this->executeSelectQuery($query)[0];
    }

    public function getEventiByTitolo($titolo)
    {
        $query = "SELECT e.id,
        e.titolo,
        e.descrizione,
        e.data,
        e.ora,
        e.luogo,
        s.annoinizio,
        s.meseinizio,
        s.tipoevento
        FROM eventi as e
        join stagioni as s on e.stagione = s.id 
        where e.titolo = '$titolo'
        order by data desc";
        return $this->executeSelectQuery($query);
    }

    public function getEventiByData($data)
    {
        $query = "SELECT e.id,
        e.titolo,
        e.descrizione,
        e.data,
        e.ora,
        e.luogo,
        s.annoinizio,
        s.meseinizio,
        s.tipoevento
        FROM eventi as e
        join stagioni as s on e.stagione = s.id 
        where e.data = '$data'
        order by data desc";
        return $this->executeSelectQuery($query);
    }
}
