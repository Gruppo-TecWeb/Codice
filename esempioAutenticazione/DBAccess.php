<?php
    namespace DB;
    class DBAccess {
        private const DB_HOST = "localhost";
        private const DB_NAME = "demo";
        private const DB_USER = "root";
        private const DB_PASS = "";

        private $connection;

        public function openDBConnection() {
            $this -> connection = mysqli_connect(
                self::DB_HOST,
                self::DB_USER,
                self::DB_PASS,
                self::DB_NAME
                );
            return mysqli_connect_errno() == 0;
        }

        public function closeDBConnection() {
            mysqli_close($this -> connection);
        }
        public function getUtente($username) {
            $query = "SELECT username, note FROM utenti WHERE username = \"$username\";";
            $queryResult = mysqli_query($this -> connection, $query) or die("Errore in DBAccess" .mysqli_error($this -> connection));
            if (mysqli_num_rows($queryResult) != 0) {
                $result = mysqli_fetch_assoc($queryResult);
                $queryResult -> free();
                return array($result["username"],
                                $result["note"]);
            } else {
                return null;
            }
        }
        public function register($username, $password, $note) {
            $password = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO utenti (username, password, note)
                        VALUES (\"$username\", \"$password\", NULLIF(\"$note\",\"\"));";
            mysqli_query($this -> connection, $query) or die(mysqli_error($this -> connection));
            return mysqli_affected_rows($this -> connection);
        }

        public function login($username, $password) {
            $query = "SELECT username, note FROM utenti WHERE username = \"$username\";";
            $queryResult = mysqli_query($this -> connection, $query) or die("Errore in DBAccess" .mysqli_error($this -> connection));
            $datiUtente = mysqli_fetch_assoc($queryResult);
            $query = "SELECT password FROM utenti WHERE username = \"$username\";";
            $queryResult = mysqli_query($this -> connection, $query) or die("Errore in DBAccess" .mysqli_error($this -> connection));
            $passwordUtente = mysqli_fetch_assoc($queryResult);
            if ((mysqli_num_rows($queryResult) != 0) && (password_verify($password, $passwordUtente["password"]))) {
                return $datiUtente;
            } else {
                return null;
            }
        }
        public function changeUsername($prevUser, $newUser) {
            $query = "UPDATE utenti SET username=\"$newUser\" WHERE username=\"$prevUser\";";
            mysqli_query($this -> connection, $query) or die(mysqli_error($this -> connection));
            return mysqli_affected_rows($this -> connection);
        }
        public function changePassword($username, $newPassword) {
            $newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $query = "UPDATE utenti SET password=\"$newPassword\" WHERE username=\"$username\";";
            mysqli_query($this -> connection, $query) or die(mysqli_error($this -> connection));
            return mysqli_affected_rows($this -> connection);
        }
        public function changeNote($username, $newNote) {
            $query = "UPDATE utenti SET note=\"$newNote\" WHERE username=\"$username\";";
            mysqli_query($this -> connection, $query) or die(mysqli_error($this -> connection));
            return mysqli_affected_rows($this -> connection);
        }
    }
?>