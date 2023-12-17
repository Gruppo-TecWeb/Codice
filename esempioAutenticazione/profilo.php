<?php
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("profiloTemplate.html");
$messaggiPerForm = "<ul>";
$username = "";
$note = "";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();

if ($connectionOk) {
    session_start();
    if (isset($_SESSION["login"]) && $_SESSION["login"]) {
        $username = $_SESSION["datiUtente"]["username"];
        $note = $_SESSION["datiUtente"]["note"];
        if (isset($_POST["submitUsername"])) {
            $errore = false;
            $newUsername = trim(filter_var($_POST["username"], FILTER_SANITIZE_STRING));
            if ($newUsername == "") {
                $errore = true;
                $messaggiPerForm .= "<li>Inserire Username.</li>";
            }
            elseif (!(is_null($connection -> getUtente($newUsername))) && $newUsername != $username) {
                $errore = true;
                $messaggiPerForm .= "<li>Username giá registrato, sceglierne un altro.</li>";
            }
            if (!$errore) {
                if ($connection -> changeUsername($username, $newUsername) > 0) {
                    $username = $newUsername;
                    $_SESSION["datiUtente"]["username"] = $username;
                    $messaggiPerForm .= "<li>Username modificato correttamente.</li>";
                }
                else {
                    $messaggiPerForm .= "<li>La modifica non é avvenuta.</li>";
                }
            }
        }
        if (isset($_POST["submitPassword"])) {
            $newPassword = trim(filter_var($_POST["password"], FILTER_SANITIZE_STRING));
            if ($newPassword == "") {
                $errore = true;
                $messaggiPerForm .= "<li>Inserire Password.</li>";
            } else {
                if ($connection -> changePassword($username, $newPassword) > 0) {
                    $messaggiPerForm .= "<li>Password modificata correttamente.</li>";
                }
                else {
                    $messaggiPerForm .= "<li>La modifica non é avvenuta.</li>";
                }
            }
        }
        if (isset($_POST["submitNote"])) {
            $newNote = trim(filter_var($_POST["note"], FILTER_SANITIZE_STRING));
            if ($connection -> changeNote($username, $newNote) > 0) {
                $note = $newNote;
                $_SESSION["datiUtente"]["note"] = $note;
                $messaggiPerForm .= "<li>Note modificate correttamente.</li>";
            }
            else {
                $messaggiPerForm .= "<li>La modifica non é avvenuta.</li>";
            }
        }
    } else {
        header("Location: login.php");
        $messaggiPerForm .= "<li>Effettuare l'accesso.</li>";
    }
}

$messaggiPerForm .= "</ul>";

$paginaHTML = str_replace("{valoreUsername}", $username, $paginaHTML);
$paginaHTML = str_replace("{valoreNote}", $note, $paginaHTML);

$paginaHTML = str_replace("{messaggiForm}", $messaggiPerForm, $paginaHTML);

echo $paginaHTML;
?>