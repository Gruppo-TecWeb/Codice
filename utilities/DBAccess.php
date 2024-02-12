<?php

namespace DB;

use Exception;

class DBAccess {
    private const DB_HOST = "localhost";
    private const DB_NAME = "fungo";
    private const DB_USER = "root";
    private const DB_PASS = "";

    private $connection;
    private static $instance = null;
    private function __construct() {
    }
    public static function getInstance() {
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


    private function executeQuery($query, ...$args) {
        mysqli_report(MYSQLI_REPORT_STRICT);
        try {
            $stmt = $this->connection->prepare($query) or die("Errore in DBAccess: " . mysqli_error($this->connection));
            $stmt->execute($args);
            $res = $stmt->get_result();
            return match ($res) {
                false => true,
                default => $res->fetch_all(MYSQLI_ASSOC),
            };
        } catch (Exception) {
            die("Errore in DBAccess: " . mysqli_error($this->connection));
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            if (isset($res) && gettype($res) == 'mysqli_result') {
                $res->free();
            }
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
        return $this->executeQuery($query);
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
        where e.id = ?";
        try {
            return $this->executeQuery(
                $query,
                $id
            )[0];
        } catch (Exception) {
            return null;
        }
    }

    public function getTitoliEventi() {
        return $this->executeQuery(
            "SELECT DISTINCT titolo FROM eventi;"
        );
    }
    public function get_tipo_evento($evento) {
        $query = $evento ?
            "SELECT * FROM TipiEvento WHERE Titolo = ?;" :
            "SELECT TipiEvento.Titolo, 
             TipiEvento.Descrizione
             FROM TipiEvento
             JOIN ClassificheEventi ON TipiEvento.Titolo = ClassificheEventi.TipoEvento
             JOIN Eventi ON ClassificheEventi.Evento = Eventi.id
             ORDER BY Eventi.Data DESC
             LIMIT 1;";
        return $evento ? $this->executeQuery($query, $evento)[0] : $this->executeQuery($query)[0];
    }
    public function get_classifiche() {
        return $this->executeQuery(
            "SELECT * FROM Classifiche;"
        );
    }
    public function get_data_inizio_corrente($tipoEvento) {
        try {
            return $this->executeQuery(
                "SELECT DataInizio FROM Classifiche WHERE TipoEvento =? AND DataInizio <= CURDATE() ORDER BY DataInizio DESC LIMIT 1;",
                $tipoEvento
            )[0]['DataInizio'];
        } catch (Exception) {
            return null;
        }
    }
    public function get_classifica($tipoEvento, $dataInizio) {
        return $this->executeQuery(
            "SELECT @n := @n + 1 AS ranking, partecipante, punti
            FROM (SELECT @n := 0) m, (
            SELECT Punteggi.Partecipante AS partecipante, SUM(Punteggi.Punteggio) AS punti
            FROM Punteggi
            JOIN Eventi ON Punteggi.Evento = Eventi.id
            JOIN ClassificheEventi ON Eventi.id = ClassificheEventi.Evento
            JOIN Classifiche ON ClassificheEventi.TipoEvento = Classifiche.TipoEvento AND ClassificheEventi.DataInizio = Classifiche.DataInizio
            WHERE Classifiche.TipoEvento = ? AND Classifiche.DataInizio = ?
            GROUP BY Punteggi.Partecipante
            ORDER BY Punti DESC) r;",
            $tipoEvento,
            $dataInizio
        );
    }
    public function login($username, $password) {
        try {
            $c = $this->executeQuery(
                "SELECT Username, Email, Password, Admin FROM Utenti WHERE Username = ? ;",
                $username
            )[0];
            if (password_verify($password, $c['Password'])) {
                unset($c['Password']);
                return $c;
            } else {
                return null;
            }
        } catch (Exception) {
            return null;
        }
    }
    public function get_utente_by_username($username) {
        try {
            return $this->executeQuery(
                "SELECT Username, Email, Admin FROM Utenti WHERE Username = ?;",
                $username
            )[0];
        } catch (Exception) {
            return null;
        }
    }
    public function get_utente_by_email($email) {
        try {
            return $this->executeQuery(
                "SELECT Username, Email, Admin FROM Utenti WHERE Email = ?;",
                $email
            )[0];
        } catch (Exception) {
            return null;
        }
    }
    public function register($username, $password, $email) {
        return $this->executeQuery(
            "INSERT INTO Utenti (Username, Password, Email) VALUES (?, ?,?);",
            $username,
            password_hash($password, PASSWORD_BCRYPT),
            $email
        );
    }
    public function change_email($username, $newEmail) {
        return $this->executeQuery(
            "UPDATE Utenti SET Email = ? WHERE Username = ?;",
            $newEmail,
            $username
        );
    }
    public function change_password($username, $newPassword) {
        return $this->executeQuery(
            "UPDATE Utenti SET Password = ? WHERE Username = ?;",
            password_hash($newPassword, PASSWORD_BCRYPT),
            $username
        );
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
