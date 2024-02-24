<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/template-pagina.html");
$content = file_get_contents("../template/admin/login-template.html");

$title = 'Login &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina dove poter effettuare l\'accesso all\'area autenticata del sito.';
$keywords = 'login, freestyle rap, fungo, micelio, battle, eventi, classifiche';
$percorso = '../';
$percorsoAdmin = '';
$menu = get_menu($pageId, $percorso);
$breadcrumbs = get_breadcrumbs($pageId, $percorso);
$onload = '';
$erroriVAL = '';
$errori = '';
$username = '';
$logout = '';

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    if (isset($_SESSION["login"])) {
        header("location: index.php");
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
                header("location: index.php");
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
    header("location: ../errore500.php");
}

if (isset($_SESSION["login"])) {
    $logout = get_content_between_markers($paginaHTML, 'logout');
}

echo multi_replace(replace_content_between_markers($paginaHTML, [
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu,
    'adminMenu' => $adminMenu,
    'logout' => $logout
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => $content,
    '{onload}' => $onload,
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin,
    '{messaggiForm}' => $errori,
    '{valoreUsername}' => $username
]);
