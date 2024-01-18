<?php
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("loginTemplate.html");
$messaggiPerForm = "<ul>";
$username = "";
$password = "";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();

if ($connectionOk) {
    session_start();
    if (isset($_SESSION["login"])) {
        $messaggiPerForm .= "<li>Accesso giá effettuato. Visualizza la tua <a href=\"profilo.php\">pagina profilo</a>.</li>";
    }
    else {
        if (isset($_POST["submit"])) {
            $errore = false;
            $username = trim(filter_var($_POST["username"], FILTER_SANITIZE_STRING));
            $password = trim(filter_var($_POST["password"], FILTER_SANITIZE_STRING));
            $note = trim(filter_var($_POST["note"], FILTER_SANITIZE_STRING));
            if ($username == "") {
                $errore = true;
                $messaggiPerForm .= "<li>Inserire Username.</li>";
            }
            if ($password == "") {
                $errore = true;
                $messaggiPerForm .= "<li>Inserire Password.</li>";
            }
            if (!$errore) {
                $utente = $connection -> login($username, $password);
                if (!(is_null($utente))) {
                    $_SESSION["datiUtente"] = $utente;
                    $_SESSION["login"] = TRUE;
                    header("location: profilo.php");
                    $messaggiPerForm .= "<li>Utente loggato correttamente.</li>";
                } else {
                    $messaggiPerForm .= "<li>Username e/o password errati.</li>";
                }
            }
        }
    }
}

$messaggiPerForm .= "</ul>";

$paginaHTML = str_replace("{valoreUsername}", $username, $paginaHTML);
$paginaHTML = str_replace("{valorePassword}", $password, $paginaHTML);

$paginaHTML = str_replace("{messaggiForm}", $messaggiPerForm, $paginaHTML);

echo $paginaHTML;
?>