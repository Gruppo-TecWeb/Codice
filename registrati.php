<?php

namespace Utilities;
require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");
use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/pagina-template.html");
$registratiHTML = file_get_contents("template/registrati-template.html");

$title = 'Registrati &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina dove poter effettuare l\'accesso all\'area autenticata del sito.';
$keywords = 'registrati, freestyle rap, fungo, micelio, battle, eventi, classifiche';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$erroriVAL = '';
$errori = '';
$username = '';

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();

if ($connectionOk) {
    if (isset($_SESSION["login"])) {
        header("location: profilo.php");
    }
    if (isset($_POST["submit"])) {
        $errore = false;
        $username = validate_input($_POST["username"]);
        $password = validate_input($_POST["password"]);
        $email = validate_input($_POST["email"]);
        if ($username == "") {
            $errore = true;
            $erroriVAL .= "<li>Inserire Username.</li>";
        }
        else {
            $utente = $connection -> get_utente($username);
            if (!(is_null($utente))) {
                $errore = true;
                $erroriVAL .= "<li>Utente giá registrato. Vai alla pagina di <a href=\"login.php\" lang=\"en\">login</a>.</li>";
            }
        }
        if ($password == "") {
            $errore = true;
            $erroriVAL .= "<li>Inserire Password.</li>";
        }
        if ($email == "") {
            $errore = true;
            $erroriVAL .= "<li>Inserire Email.</li>";
        }
        if (!$errore) {
            $utenteRegistrato = $connection -> register($username, $password, $email);
            if ($utenteRegistrato > 0) {
                $_SESSION["datiUtente"] = array("username" => $username, "email" => $email);
                $_SESSION["login"] = TRUE;
                header("location: profilo.php");
                $messaggiPerForm .= "<li>Registrazione avvenuta correttamente.</li>";
            }
            else {
                $messaggiPerForm .= "<li>La registrazione non é avvenuta.</li>";
            }
        }
        if ($errore) {
            $errori = '<ul>' . $erroriVAL . '</ul>';
        }
    }
}
else {
    $content .= '<p>I sistemi sono momentaneamente fuori servizio, ci scusiamo per il disagio.</p>';
}

$registratiHTML = str_replace("{messaggiForm}", $errori, $registratiHTML);
$registratiHTML = str_replace("{valoreUsername}", $username, $registratiHTML);
$registratiHTML = str_replace("{valoreEmail}", $email, $registratiHTML);
echo replace_in_page($paginaHTML, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $registratiHTML, $onload);
?>