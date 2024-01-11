<?php

namespace Utilities;
require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");
use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/pagina-template.html");
$loginHTML = file_get_contents("template/login-template.html");

$title = 'Login &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina dove poter effettuare l\'accesso all\'area autenticata del sito.';
$keywords = 'login, freestyle rap, fungo, micelio, battle, eventi, classifiche';
$menu = get_menu($pageId);
$reservedMenu = get_reserved_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$erroriVAL = '';
$errori = '';
$username = '';

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();

if ($connectionOk) {
    session_start();
    if (isset($_SESSION["login"])) {
        header("location: profilo.php");
    }
    if (isset($_POST["submit"])) {
        $errore = false;
        $username = validate_input($_POST["username"]);
        $password = validate_input($_POST["password"]);
        if ($username == "") {
            $errore = true;
            $erroriVAL .= "<li>Inserire Username.</li>";
        }
        if ($password == "") {
            $errore = true;
            $erroriVAL .= "<li>Inserire Password.</li>";
        }
        if (!$errore) {
            $utente = $connection -> login($username, $password);echo $username;
            if (!(is_null($utente))) {
                $_SESSION["datiUtente"] = $utente;
                $_SESSION["login"] = TRUE;
                header("location: profilo.php");
            } else {
                $errore = true;
                $erroriVAL .= "<li>Username e/o password errati.</li>";
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

$loginHTML = str_replace("{messaggiForm}", $errori, $loginHTML);
$loginHTML = str_replace("{valoreUsername}", $username, $loginHTML);
echo replace_in_page($paginaHTML, $title, $description, $keywords, $pageId, $menu, $reservedMenu, $breadcrumbs, $loginHTML, $onload);
?>