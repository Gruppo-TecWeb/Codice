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

    public function getListaEventi($filtro = '', $data = '', $titolo = '')
    {
        $query = "SELECT e.id,
        e.titolo,
        e.descrizione,
        e.data,
        e.ora,
        e.luogo,
        e.locandina
        FROM eventi as e";
        $conditions = [];
        $order = 'asc';
        switch ($filtro) {
            case 'passati':
                $conditions[] = "e.data < '" . date('Y-m-d') . "'";
                $order = 'desc';
                break;
            case 'tutti':
                $order = 'desc';
                break;
            case 'data':
                $conditions[] = "e.data >= '$data'";
                break;
            default:
                $conditions[] = "e.data >= '" . date('Y-m-d') . "'";
                break;
        }

        if ($titolo != '') {
            $conditions[] = "e.titolo = '$titolo'";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }
        $query .= " order by data $order";
        return $this->executeSelectQuery($query);
    }

    public function getEvento($id)
    {
        $query = "SELECT e.titolo,
        e.descrizione,
        e.data,
        e.ora,
        e.luogo,
        e.locandina,
        ce.tipoevento,
        ce.datainizio
        FROM eventi as e
        left join classificheeventi as ce on e.id = ce.evento
        where e.id = $id";
        return $this->executeSelectQuery($query)[0];
    }
}
