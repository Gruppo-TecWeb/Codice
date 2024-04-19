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

    public function get_eventi_selezionabili($dataInizio, $dataFine) {
        $query = "SELECT *
        FROM Eventi
        WHERE Eventi.Data >= ? AND Eventi.Data <= ? AND Eventi.Id NOT IN (
            SELECT Eventi.Id
            FROM Eventi
            JOIN ClassificheEventi ON Eventi.Id = ClassificheEventi.Evento
        )
        ORDER BY Eventi.Data ASC";
        return $this->execute_query($query, $dataInizio, $dataFine);
    }

    public function get_eventi_selezionati($tipoEvento, $dataInizio) {
        $query = "SELECT Eventi.Id,
        Eventi.Titolo,
        Eventi.Descrizione,
        Eventi.Data,
        Eventi.Ora,
        Eventi.Luogo,
        Eventi.Locandina
        FROM Eventi
        JOIN ClassificheEventi ON Eventi.Id = ClassificheEventi.Evento
        WHERE ClassificheEventi.TipoEvento = ? AND ClassificheEventi.DataInizio = ?
        ORDER BY Eventi.Data ASC";
        return $this->execute_query($query, $tipoEvento, $dataInizio);
    }

    public function get_evento($id) {
        $query = "SELECT e.Titolo,
        e.Descrizione,
        e.Data,
        e.Ora,
        e.Luogo,
        e.Locandina,
        ce.Tipoevento,
        ce.Datainizio
        FROM Eventi AS e
        LEFT JOIN ClassificheEventi AS ce ON e.Id = ce.Evento
        WHERE e.Id = ?";
        return ($ris = $this->execute_query($query, $id)) ? $ris[0] : null;
    }

    public function get_eventi_classifica($tipoEvento, $dataInizio) {
        return $this->execute_query(
            "SELECT Eventi.Id, Eventi.Titolo, Eventi.Data, Eventi.Ora, Eventi.Luogo
            FROM Eventi
            JOIN ClassificheEventi ON Eventi.Id = ClassificheEventi.Evento
            WHERE ClassificheEventi.TipoEvento = ? AND ClassificheEventi.DataInizio = ?;",
            $tipoEvento,
            $dataInizio
        );
    }

    public function insert_evento($titolo, $descrizione, $data, $ora, $luogo, $locandina) {
        $id = -1;
        $result = $this->execute_query(
            "INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo) VALUES (?, NULLIF(?, ''), ?, ?, ?);",
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

    public function update_evento($id, $titolo, $descrizione, $data, $ora, $luogo, $locandina) {
        return $this->execute_query(
            "UPDATE Eventi SET Titolo = ?, Descrizione = NULLIF(?, ''), Data = ?, Ora = ?, Luogo = ?, Locandina = NULLIF(?, '') WHERE Id = ?;",
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

    public function get_tipo_evento($titolo = null) {
        $query = $titolo ?
            "SELECT * FROM TipiEvento WHERE Titolo = ?;" :
            "SELECT TipiEvento.Titolo, 
             TipiEvento.Descrizione
             FROM TipiEvento
             JOIN ClassificheEventi ON TipiEvento.Titolo = ClassificheEventi.TipoEvento
             JOIN Eventi ON ClassificheEventi.Evento = Eventi.id
             ORDER BY Eventi.Data DESC
             LIMIT 1;";
        $res = $titolo ? $this->execute_query($query, $titolo) : $this->execute_query($query);
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

    public function get_classifiche($tipoEvento = null, $dataInizio = null) {
        if ($tipoEvento && $dataInizio) {
            return $this->execute_query(
                "SELECT * FROM Classifiche WHERE TipoEvento = ? AND DataInizio = ? ORDER BY DataInizio DESC;",
                $tipoEvento,
                $dataInizio
            );
        } else {
            return $this->execute_query(
                "SELECT * FROM Classifiche ORDER BY DataInizio DESC;"
            );
        }
    }

    public function get_data_inizio_corrente($tipoEvento) {
        return ($ris = $this->execute_query(
            "SELECT DataInizio FROM Classifiche WHERE TipoEvento =? AND DataInizio <= CURDATE() ORDER BY DataInizio DESC LIMIT 1;",
            $tipoEvento
        )) ? $ris[0]['DataInizio'] : null;
    }

    public function get_classifica($tipoEvento, $dataInizio) {
        return $this->execute_query(
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

    public function get_classifica_evento($evento) {
        return $this->execute_query(
            "SELECT @n := @n + 1 AS ranking, partecipante, punti
            FROM (SELECT @n := 0) m, (
            SELECT Punteggi.Partecipante AS partecipante, SUM(Punteggi.Punteggio) AS punti
            FROM Punteggi
            WHERE Punteggi.Evento = ?
            GROUP BY Punteggi.Partecipante
            ORDER BY Punti DESC) r;",
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

    public function insert_classifica($tipoEvento, $dataInizio, $dataFine) {
        return $this->execute_query(
            "INSERT INTO Classifiche (TipoEvento, DataInizio, DataFine) VALUES (?, ?, ?);",
            $tipoEvento,
            $dataInizio,
            $dataFine
        );
    }

    public function insert_classifica_eventi($tipoEvento, $dataInizio, $eventiSelezionati) {
        foreach ($eventiSelezionati as $eventoSelezionato) {
            $this->execute_query(
                "INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES (?, ?, ?);",
                $tipoEvento,
                $dataInizio,
                $eventoSelezionato
            );
        }
    }

    public function update_classifica($tipoEvento, $dataInizio, $nuovoTipoEvento, $nuovaDataInizio, $nuovaDataFine) {
        $this->execute_query(
            "UPDATE Classifiche SET TipoEvento = ?, DataInizio = ?, DataFine = ? WHERE TipoEvento = ? AND DataInizio = ?;",
            $nuovoTipoEvento,
            $nuovaDataInizio,
            $nuovaDataFine,
            $tipoEvento,
            $dataInizio
        );
    }

    public function update_classifica_eventi($tipoEvento, $dataInizio, $eventiSelezionati) {
        $this->execute_query(
            "DELETE FROM ClassificheEventi WHERE TipoEvento = ? AND DataInizio = ?;",
            $tipoEvento,
            $dataInizio
        );
        foreach ($eventiSelezionati as $eventoSelezionato) {
            $this->execute_query(
                "INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES (?, ?, ?);",
                $tipoEvento,
                $dataInizio,
                $eventoSelezionato
            );
        }
    }

    public function delete_classifica($tipoEvento, $dataInizio) {
        return $this->execute_query(
            "DELETE FROM Classifiche WHERE TipoEvento = ? AND DataInizio = ?;",
            $tipoEvento,
            $dataInizio
        );
    }

    public function login($username, $password) {
        $c = $this->execute_query(
            "SELECT Username, Email, Password, Admin FROM Utenti WHERE Username = ? ;",
            $username
        );
        $res = $c ? $c[0] : null;
        return $res && password_verify($password, $res['Password']) ? $res : null;
    }

    public function get_utente_by_username($username) {
        return ($ris = $this->execute_query(
            "SELECT Username, Email, Admin FROM Utenti WHERE Username = ?;",
            $username
        )) ? $ris[0] : [];
    }

    public function get_utente_by_email($email) {
        return ($ris = $this->execute_query(
            "SELECT Username, Email, Admin FROM Utenti WHERE Email = ?;",
            $email
        )) ? $ris[0] : [];
    }

    public function get_utenti() {
        return $this->execute_query(
            "SELECT * FROM Utenti ORDER BY Username;"
        );
    }

    public function get_utenti_base() {
        return $this->execute_query(
            "SELECT * FROM Utenti WHERE Admin = 'N' ORDER BY Username;"
        );
    }

    public function get_utenti_admin() {
        return $this->execute_query(
            "SELECT * FROM Utenti WHERE Admin = 'S' ORDER BY Username;"
        );
    }

    public function insert_utente($username, $password, $email, $admin = 'N') {
        return $this->execute_query(
            "INSERT INTO Utenti (Username, Password, Email, Admin) VALUES (?, ?, ?, ?);",
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
