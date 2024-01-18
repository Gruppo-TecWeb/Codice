<?php
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("registratiTemplate.html");
$messaggiPerForm = "<ul>";
$username = "";
$password = "";
$note = "";
$utenteRegistrato = 0;

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
            elseif (!(is_null($connection -> getUtente($username)))) {
                $errore = true;
                $messaggiPerForm .= "<li>Utente giá registrato. Vai alla pagina di <a href=\"login.php\">login</a>.</li>";
            }
            if ($password == "") {
                $errore = true;
                $messaggiPerForm .= "<li>Inserire Password.</li>";
            }
            if (!$errore) {
                $utenteRegistrato = $connection -> register($username, $password, $note);
                if ($utenteRegistrato > 0) {
                    $_SESSION["datiUtente"] = array("username"=>$username, "password"=>$password, "note"=>$note);
                    $_SESSION["login"] = TRUE;
                    header("location: profilo.php");
                    $messaggiPerForm .= "<li>Registrazione avvenuta correttamente.</li>";
                }
                else {
                    $messaggiPerForm .= "<li>La registrazione non é avvenuta.</li>";
                }
            }
        }
    }
}
else {
    $messaggiPerForm .= "<li class=\"errori\">I sistemi sono momentaneamente fuori servizio, ci scusiamo per il disagio.</li>";
}

$messaggiPerForm .= "</ul>";

$paginaHTML = str_replace("{valoreUsername}", $username, $paginaHTML);
$paginaHTML = str_replace("{valorePassword}", $password, $paginaHTML);
$paginaHTML = str_replace("{valoreNote}", $note, $paginaHTML);

$paginaHTML = str_replace("{messaggiForm}", $messaggiPerForm, $paginaHTML);

echo $paginaHTML;
?>