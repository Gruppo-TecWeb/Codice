<?php

namespace DB;

class DBAccess {
    private const DB_HOST = "localhost";
    private const DB_NAME = "fungo";
    private const DB_USER = "root";
    private const DB_PASS = "";

    private $connection;
    private static $instance = null;
    private function __construct()
    {
    }
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DBAccess();
        }
        return self::$instance;
    }

    public function openDBConnection() {
        $this->connection = mysqli_connect(
            self::DB_HOST,
            self::DB_USER,
            self::DB_PASS,
            self::DB_NAME
        );
        return mysqli_connect_errno() == 0;
    }

    public function closeDBConnection() {
        mysqli_close($this->connection);
    }

    public function executeSelectQuery($query) {
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

    public function getListaEventi($filtro = '', $data = '', $titolo = '') {
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

    public function getEvento($id) {
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
    public function get_tipo_evento($evento) {
        $query = $evento ? "SELECT * FROM TipiEvento WHERE Titolo = \"$evento\";"
            : "SELECT TipiEvento.Titolo, TipiEvento.Descrizione
                    FROM TipiEvento
                    JOIN ClassificheEventi ON TipiEvento.Titolo = ClassificheEventi.TipoEvento
                    JOIN Eventi ON ClassificheEventi.Evento = Eventi.id
                    ORDER BY Eventi.Data DESC
                    LIMIT 1;";
        $queryResult = mysqli_query($this->connection, $query)
            or die("Errore in DBAccess" . mysqli_error($this->connection));
        if (mysqli_num_rows($queryResult) != 0) {
            $result = array();
            while ($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult->free();
            return $result;
        } else {
            return null;
        }
    }
    public function get_classifiche() {
        $query = "SELECT * FROM Classifiche;";
        $queryResult = mysqli_query($this->connection, $query)
            or die("Errore in DBAccess" . mysqli_error($this->connection));
        if (mysqli_num_rows($queryResult) != 0) {
            $result = array();
            while ($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult->free();
            return $result;
        } else {
            return null;
        }
    }
    public function get_data_inizio_corrente($tipoEvento) {
        $query = "SELECT DataInizio FROM Classifiche WHERE TipoEvento = \"$tipoEvento\" AND DataInizio <= CURDATE() ORDER BY DataInizio DESC LIMIT 1;";
        $queryResult = mysqli_query($this->connection, $query)
            or die("Errore in DBAccess" . mysqli_error($this->connection));
        if (mysqli_num_rows($queryResult) != 0) {
            $result = mysqli_fetch_assoc($queryResult);
            $queryResult->free();
            return $result['DataInizio'];
        } else {
            return null;
        }
    }
    public function get_classifica($tipoEvento, $dataInizio) {
        $query = "SELECT @n := @n + 1 AS ranking, partecipante, punti
                    FROM (SELECT @n := 0) m, (
                        SELECT Punteggi.Partecipante AS partecipante, SUM(Punteggi.Punteggio) AS punti
                        FROM Punteggi
                        JOIN Eventi ON Punteggi.Evento = Eventi.id
                        JOIN ClassificheEventi ON Eventi.id = ClassificheEventi.Evento
                        JOIN Classifiche ON ClassificheEventi.TipoEvento = Classifiche.TipoEvento AND ClassificheEventi.DataInizio = Classifiche.DataInizio
                        WHERE Classifiche.TipoEvento = \"$tipoEvento\" AND Classifiche.DataInizio = \"$dataInizio\"
                        GROUP BY Punteggi.Partecipante
                        ORDER BY Punti DESC) r;";
        $queryResult = mysqli_query($this->connection, $query)
            or die("Errore in DBAccess" . mysqli_error($this->connection));
        if (mysqli_num_rows($queryResult) != 0) {
            $result = array();
            while ($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult->free();
            return $result;
        } else {
            return null;
        }
    }
    public function login($username, $password) {
        $query = "SELECT Username, Email, Admin FROM Utenti WHERE Username = \"$username\";";
        $queryResult = mysqli_query($this->connection, $query) or die("Errore in DBAccess" . mysqli_error($this->connection));
        $datiUtente = mysqli_fetch_assoc($queryResult);
        $query = "SELECT Password FROM Utenti WHERE Username = \"$username\";";
        $queryResult = mysqli_query($this->connection, $query) or die("Errore in DBAccess" . mysqli_error($this->connection));
        $passwordUtente = mysqli_fetch_assoc($queryResult)["Password"];
        if ((mysqli_num_rows($queryResult) != 0) && (password_verify($password, $passwordUtente))) {
            return $datiUtente;
        } else {
            return null;
        }
    }
    public function get_utente_by_username($username) {
        $query = "SELECT Username, Email, Admin FROM Utenti WHERE Username = \"$username\";";
        $queryResult = mysqli_query($this->connection, $query) or die("Errore in DBAccess" . mysqli_error($this->connection));
        if (mysqli_num_rows($queryResult) != 0) {
            $result = mysqli_fetch_assoc($queryResult);
            $queryResult->free();
            return $result;
        } else {
            return null;
        }
    }
    public function get_utente_by_email($email) {
        $query = "SELECT Username, Email, Admin FROM Utenti WHERE Email = \"$email\";";
        $queryResult = mysqli_query($this->connection, $query) or die("Errore in DBAccess" . mysqli_error($this->connection));
        if (mysqli_num_rows($queryResult) != 0) {
            $result = mysqli_fetch_assoc($queryResult);
            $queryResult->free();
            return $result;
        } else {
            return null;
        }
    }
    public function register($username, $password, $email) {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO Utenti (Username, Password, Email)
                    VALUES (\"$username\", \"$password\", \"$email\");";
        mysqli_query($this->connection, $query) or die(mysqli_error($this->connection));
        return mysqli_affected_rows($this->connection);
    }
    public function change_email($username, $newEmail) {
        $query = "UPDATE Utenti SET Email = \"$newEmail\" WHERE Username = \"$username\";";
        mysqli_query($this->connection, $query) or die(mysqli_error($this->connection));
        return mysqli_affected_rows($this->connection);
    }
    public function change_password($username, $newPassword) {
        $newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $query = "UPDATE Utenti SET Password = \"$newPassword\" WHERE Username = \"$username\";";
        mysqli_query($this->connection, $query) or die(mysqli_error($this->connection));
        return mysqli_affected_rows($this->connection);
    }
    public function get_basi() {
        $query = "Select * from basi";
        $queryResult = mysqli_query($this->connection, $query)
            or die("Errore in DBAccess" . mysqli_error($this->connection));
        if (mysqli_num_rows($queryResult) != 0) {
            $result = array();
            while ($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult->free();
            return $result;
        } else {
            return null;
        }
    }
}
