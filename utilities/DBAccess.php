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
    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new DBAccess();
        }
        return self::$instance;
    }

    public function open_DB_connection() {
        $this->connection = mysqli_connect(
            self::DB_HOST,
            self::DB_USER,
            self::DB_PASS,
            self::DB_NAME
        );
        return mysqli_connect_errno() == 0;
    }

    public function close_DB_connection() {
        mysqli_close($this->connection);
    }

    private function execute_query($query, ...$args) {
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

    public function get_lista_eventi($data = '', $titolo = '', $ascendente = true) {
        // TODO: aggiungere il TipoEvento al risultato
        $query = "SELECT e.Id,
        e.Titolo,
        e.Descrizione,
        e.Data,
        e.Ora,
        e.Luogo,
        e.Locandina
        FROM Eventi as e";
        $conditions = [];
        // $conditions[] = $data != '' ? "e.Data >= '$data'" : "e.Data >= '" . date('Y-m-d') . "'";
        $conditions[] = "e.Data " . ($ascendente ? ">=" : "<=") . " '" . ($data != '' ? $data : date('Y-m-d')) . "'";
        if ($titolo != '') {
            $conditions[] = "e.Titolo = '$titolo'";
        }
        $query .= " WHERE " . implode(' AND ', $conditions) . " ORDER BY Data " . ($ascendente ? "ASC" : "DESC");
        return $this->execute_query($query);
    }

    public function get_eventi() {
        return $this->execute_query(
            "SELECT * FROM Eventi ORDER BY Data DESC;"
        );
    }

    public function get_evento($id) {
        $query = "SELECT e.TipoEvento,
        e.Titolo,
        e.Descrizione,
        e.Data,
        e.Ora,
        e.Luogo,
        e.Locandina
        FROM Eventi AS e
        WHERE e.Id = ?";
        return ($ris = $this->execute_query($query, $id)) ? $ris[0] : null;
    }

    public function get_eventi_classifica($tipoEvento, $dataInizio, $dataFine) {
        $query = "SELECT
            e.Id,
            e.TipoEvento,
            e.Titolo,
            e.Descrizione,
            e.Data,
            e.Ora,
            e.Luogo,
            e.Locandina
            FROM Eventi AS e
            WHERE e.TipoEvento = ? AND e.Data >= ? AND e.Data <= ?;";
        return ($ris = $this->execute_query($query, $tipoEvento, $dataInizio, $dataFine)) ? $ris : [];
    }

    public function insert_evento($tipoEvento, $titolo, $descrizione, $data, $ora, $luogo, $locandina) {
        $id = -1;
        $result = $this->execute_query(
            "INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo) VALUES (NULLIF(?, ''), ?, NULLIF(?, ''), ?, ?, ?);",
            $tipoEvento,
            $titolo,
            $descrizione,
            $data,
            $ora,
            $luogo
        );

        if ($result) {
            $id = $this->connection->insert_id;
        }

        return $id;
    }

    public function update_evento($id, $tipoEvento, $titolo, $descrizione, $data, $ora, $luogo, $locandina) {
        return $this->execute_query(
            "UPDATE Eventi SET TipoEvento = NULLIF(?, ''), Titolo = ?, Descrizione = NULLIF(?, ''), Data = ?, Ora = ?, Luogo = ?, Locandina = NULLIF(?, '') WHERE Id = ?;",
            $tipoEvento,
            $titolo,
            $descrizione,
            $data,
            $ora,
            $luogo,
            $locandina,
            $id
        );
    }

    public function delete_evento($id) {
        return $this->execute_query(
            "DELETE FROM Eventi WHERE Id = ?;",
            $id
        );
    }

    public function get_titoli_eventi() {
        return $this->execute_query(
            "SELECT DISTINCT Titolo FROM Eventi;"
        );
    }
    public function get_oldest_date() {
        return ($ris = $this->execute_query(
            "SELECT Data FROM Eventi ORDER BY Data ASC LIMIT 1;"
        )) ? $ris[0]['Data'] : null;
    }

    public function get_tipi_evento() {
        return $this->execute_query(
            "SELECT * FROM TipiEvento ORDER BY Titolo;"
        );
    }

    public function get_tipo_evento($titolo) {
        $query = "SELECT * FROM TipiEvento WHERE Titolo = ?;";
        $res = $this->execute_query($query, $titolo);
        return $res ? $res[0] : [];
    }

    public function insert_tipo_evento($titolo, $descrizione) {
        return $this->execute_query(
            "INSERT INTO TipiEvento (Titolo, Descrizione) VALUES (?, ?);",
            $titolo,
            $descrizione
        );
    }

    public function update_tipo_evento($oldTitolo, $newTitolo, $newDescrizione) {
        return $this->execute_query(
            "UPDATE TipiEvento SET Titolo = ?, Descrizione = ? WHERE Titolo = ?;",
            $newTitolo,
            $newDescrizione,
            $oldTitolo
        );
    }

    public function delete_tipo_evento($titolo) {
        return $this->execute_query(
            "DELETE FROM TipiEvento WHERE Titolo = ?;",
            $titolo
        );
    }

    public function get_classifiche($titolo = null) {
        if ($titolo) {
            return $this->execute_query(
                "SELECT * FROM Classifiche WHERE Titolo = ?;",
                $titolo
            );
        } else {
            return $this->execute_query(
                "SELECT * FROM Classifiche ORDER BY DataInizio DESC;"
            );
        }
    }

    public function get_classifica($id) {
        return ($ris = $this->execute_query(
            "SELECT * FROM Classifiche WHERE Id = ?;",
            $id
        )) ? $ris[0] : null;
    }

    public function get_classifica_corrente() {
        // restituisce la classifica corrente, ovvero quella con la data di inizio più recente
        $query = "SELECT * FROM Classifiche WHERE DataInizio <= CURDATE() AND DataFine >= CURDATE() ORDER BY DataInizio DESC LIMIT 1;";
        $ris = $this->execute_query($query);
        if ($ris) {
            return $ris[0];
        } else {
            $query = "SELECT * FROM Classifiche WHERE DataInizio >= CURDATE() ORDER BY DataInizio ASC LIMIT 1;";
            $ris = $this->execute_query($query);
            return $ris ? $ris[0] : null;
        }
    }

    public function get_punteggi_classifica($tipoEvento, $dataInizio, $dataFine) {
        return $this->execute_query(
            "SELECT @n := @n + 1 AS ranking, partecipante, punti
            FROM (SELECT @n := 0) m, (
            SELECT Punteggi.Partecipante AS partecipante, SUM(Punteggi.Punteggio) AS punti
            FROM Punteggi
            JOIN Eventi ON Punteggi.Evento = Eventi.id
            WHERE Eventi.TipoEvento = ? AND Eventi.Data >= ? AND Eventi.Data <= ?
            GROUP BY Punteggi.Partecipante
            ORDER BY Punti DESC) r;",
            $tipoEvento,
            $dataInizio,
            $dataFine
        );
    }

    public function get_classifica_evento($evento) {
        return $this->execute_query(
            "SELECT @n := @n + 1 AS ranking, partecipante, punti
            FROM (SELECT @n := 0) m, (
            SELECT Punteggi.Partecipante AS partecipante, SUM(Punteggi.Punteggio) AS punti
            FROM Punteggi
            WHERE Punteggi.Evento = ?
            GROUP BY Punteggi.Partecipante 
            ORDER BY Punti DESC) r;", // TODO: credo sia inutile il group by, (evento,partecipante) sono chiave primaria, togliere?
            $evento
        );
    }

    public function get_punteggi_evento($evento) {
        $results = $this->execute_query(
            "SELECT Punteggi.Partecipante AS partecipante, Punteggi.Punteggio AS punteggio
            FROM Punteggi
            WHERE Punteggi.Evento = ?;",
            $evento
        );
        $punteggi = [];
        foreach ($results as $result) {
            $punteggi[$result['partecipante']] = $result['punteggio'];
        }
        return $punteggi;
    }

    public function update_punteggi_evento($evento, $punteggi) {
        $this->execute_query(
            "DELETE FROM Punteggi WHERE Evento = ?;",
            $evento
        );
        foreach ($punteggi as $partecipante => $punteggio) {
            if ($punteggio != "0") {
                $this->execute_query(
                    "INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES (?, ?, ?);",
                    $partecipante,
                    $evento,
                    $punteggio
                );
            }
        }
    }

    public function delete_punteggi_evento($evento) {
        return $this->execute_query(
            "DELETE FROM Punteggi WHERE Evento = ?;",
            $evento
        );
    }

    public function insert_classifica($titolo, $tipoEvento, $dataInizio, $dataFine) {
        return $this->execute_query(
            "INSERT INTO Classifiche (Titolo, TipoEvento, DataInizio, DataFine) VALUES (?, ?, ?, ?);",
            $titolo,
            $tipoEvento,
            $dataInizio,
            $dataFine
        );
    }

    public function update_classifica($id, $titolo, $tipoEvento, $dataInizio, $dataFine) {
        $this->execute_query(
            "UPDATE Classifiche SET Titolo = ?, TipoEvento = ?, DataInizio = ?, DataFine = ? WHERE Id = ?;",
            $titolo,
            $tipoEvento,
            $dataInizio,
            $dataFine,
            $id
        );
    }

    public function delete_classifica($id) {
        return $this->execute_query(
            "DELETE FROM Classifiche WHERE Id = ?;",
            $id
        );
    }

    public function login($username, $password) {
        $c = $this->execute_query(
            "SELECT Username, Email, Password, TipoUtente FROM Utenti WHERE Username = ? ;",
            $username
        );
        $res = $c ? $c[0] : null;
        return $res && password_verify($password, $res['Password']) ? $res : null;
    }

    public function get_utente_by_username($username) {
        return ($ris = $this->execute_query(
            "SELECT Username, Email, TipoUtente FROM Utenti WHERE Username = ?;",
            $username
        )) ? $ris[0] : [];
    }

    public function get_utente_by_email($email) {
        return ($ris = $this->execute_query(
            "SELECT Username, Email, TipoUtente FROM Utenti WHERE Email = ?;",
            $email
        )) ? $ris[0] : [];
    }

    public function get_utenti() {
        return $this->execute_query(
            "SELECT Username, Email, TipoUtente FROM Utenti ORDER BY Username;"
        );
    }

    public function get_utenti_base() {
        return $this->execute_query(
            "SELECT Username, Email, TipoUtente FROM Utenti WHERE TipoUtente = 'U' ORDER BY Username;"
        );
    }

    public function get_utenti_admin() {
        return $this->execute_query(
            "SELECT Username, Email, TipoUtente FROM Utenti WHERE TipoUtente = 'A' ORDER BY Username;"
        );
    }

    public function insert_utente($username, $password, $email, $admin = 'U') {
        return $this->execute_query(
            "INSERT INTO Utenti (Username, Password, Email, TipoUtente) VALUES (?, ?, ?, ?);",
            $username,
            password_hash($password, PASSWORD_BCRYPT),
            $email,
            $admin
        );
    }

    public function change_username($oldUsername, $newUsername) {
        return $this->execute_query(
            "UPDATE Utenti SET Username = ? WHERE Username = ?;",
            $newUsername,
            $oldUsername
        );
    }

    public function change_email($username, $newEmail) {
        return $this->execute_query(
            "UPDATE Utenti SET Email = ? WHERE Username = ?;",
            $newEmail,
            $username
        );
    }

    public function change_password($username, $newPassword) {
        return $this->execute_query(
            "UPDATE Utenti SET Password = ? WHERE Username = ?;",
            password_hash($newPassword, PASSWORD_BCRYPT),
            $username
        );
    }

    public function update_user($oldUsername, $newUsername, $newEmail) {
        return $this->execute_query(
            "UPDATE Utenti SET Username = ?, Email = ? WHERE Username = ?;",
            $newUsername,
            $newEmail,
            $oldUsername
        );
    }

    public function delete_user($username) {
        return $this->execute_query(
            "DELETE FROM Utenti WHERE Username = ?;",
            $username
        );
    }
}
