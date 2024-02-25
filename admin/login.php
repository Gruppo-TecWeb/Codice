<?php

namespace Utilities;
require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");
use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/template-pagina.html");
$content = file_get_contents("../template/admin/login.html");

$title = 'Login &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina dove poter effettuare l\'accesso all\'area autenticata del sito.';
$keywords = 'login, freestyle rap, fungo, micelio, battle, eventi, classifiche';
$percorso = '../';
$percorsoAdmin = '';
$menu = get_menu($pageId, $percorso);
$breadcrumbs = get_breadcrumbs($pageId, $percorso);
$onload = '';
$username = '';
$messaggioForm = '';
$messaggiForm = '';

$connection = DBAccess::getInstance();
$connectionOk = $connection -> openDBConnection();

if ($connectionOk) {
    if (isset($_SESSION["login"])) {
        header("location: index.php");
    }

    $messaggioForm = get_content_between_markers($content, 'messaggioForm');
    if (isset($_POST["submit"])) {
        $errore = false;
        $username = validate_input($_POST["username"]);
        $password = validate_input($_POST["password"]);
        if ($username == "") {
            $errore = true;
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Inserire Username"]);
        }
        if ($password == "") {
            $errore = true;
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Inserire Password"]);
        }
        if (!$errore) {
            $utente = $connection -> login($username, $password);
            if (!(is_null($utente))) {
                $_SESSION["datiUtente"] = $utente;
                $_SESSION["login"] = true;
                header("location: index.php");
            } else {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Username e/o password errati"]);
            }
        }
        if ($errore) {
            $messaggiForm = replace_content_between_markers(
                get_content_between_markers($content, 'messaggiForm'), ['messaggioForm' => $messaggiForm]);
        }
    }

    $connection->closeDBConnection();
}
else {
    header("location: ../errore500.php");
}

if (isset($_SESSION["login"])) {
    $paginaHTML = replace_content_between_markers($paginaHTML, [
        'logout' => get_content_between_markers($paginaHTML, 'logout')
    ]);
} else {
    $paginaHTML = replace_content_between_markers($paginaHTML, [
        'logout' => ''
    ]);
}

echo multi_replace(replace_content_between_markers($paginaHTML, [
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => replace_content_between_markers($content, ['messaggiForm' => $messaggiForm]),
    '{onload}' => $onload,
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin,
    '{valoreUsername}' => $username
]);