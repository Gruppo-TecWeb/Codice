<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/pagina-template.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("template/logout.html") : '';

$title = 'Login &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina dove poter effettuare l\'accesso all\'area autenticata del sito.';
$keywords = 'login, freestyle rap, fungo, micelio, battle, eventi, classifiche';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$content = file_get_contents("template/login.html");
$onload = '';
$erroriVAL = '';
$errori = '';
$username = '';

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
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
            $utente = $connection->login($username, $password);
            if (!(is_null($utente))) {
                $_SESSION["datiUtente"] = $utente;
                $_SESSION["login"] = true;
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
} else {
    header("location: errore500.php");
}
$content = multi_replace($content, [
    '{messaggiForm}' => $errori,
    '{valoreUsername}' => $username
]);

echo replace_content_between_markers(multi_replace($paginaHTML, [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => $content,
    '{onload}' => $onload,
    '{logout}' => $logout
]), [
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu,
]);
