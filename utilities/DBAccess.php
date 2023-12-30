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
    public function get_stagione($tipoEvento){
        $query = "SELECT Stagioni.id AS stagione
                    FROM Stagioni
                    WHERE TipoEvento = $tipoEvento
                    AND AnnoInizio = (
                        SELECT MAX(Stagioni.AnnoInizio) AS stagione
                        FROM Eventi
                        JOIN Stagioni ON Eventi.Stagione = Stagioni.id
                        JOIN TipiEvento ON Stagioni.TipoEvento = TipiEvento.id
                        WHERE TipiEvento.id = $tipoEvento);";
        $queryResult = mysqli_query($this -> connection, $query)
            or die("Errore in DBAccess" .mysqli_error($this -> connection));
        if (mysqli_num_rows($queryResult) != 0) {
            return mysqli_fetch_assoc($queryResult)['stagione'];
        } else {
            return null;
        }
    }
    public function get_classifica($tipoEvento){
        $stagione = null;
        switch ($tipoEvento) {
            case 'fungo':
                $stagione = $this->get_stagione(1);
                break;
            case 'micelio':
                $stagione = $this->get_stagione(2);
                break;
        }
        if ($stagione != null) {
            $query = "SELECT @n := @n + 1 AS ranking, partecipante, punti
                        FROM (SELECT @n := 0) m, (
                            SELECT Punteggi.Partecipante AS partecipante, SUM(Punteggi.Punteggio) AS punti
                            FROM Punteggi
                            JOIN Eventi ON Punteggi.Evento = Eventi.id
                            WHERE Eventi.Stagione = $stagione
                            GROUP BY Punteggi.Partecipante
                            ORDER BY Punti DESC) r;";
            $queryResult = mysqli_query($this -> connection, $query)
                or die("Errore in DBAccess" .mysqli_error($this -> connection));
                if (mysqli_num_rows($queryResult) != 0) {
                    $result = array();
                    while ($row = mysqli_fetch_assoc($queryResult)) {
                        $result[] = $row;
                    }
                    $queryResult -> free();
                    return $result;
                } else {
                    return null;
                }
        } else {
            return null;
        }
    }
}
