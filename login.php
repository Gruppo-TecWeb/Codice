<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/login.html");
$style = 'login.css';

$title = 'Login &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina dove poter effettuare l\'accesso all\'area autenticata del sito.';
$keywords = 'login, freestyle rap, fungo, micelio, battle, eventi, classifiche';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$username = '';
$messaggioForm = '';
$messaggiForm = '';
$classList = '';
$logo = get_content_between_markers($paginaHTML, 'logoLink');

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

if ($connectionOk) {
    if (isset($_SESSION["login"])) {
        header("location: admin/index.php");
        exit;
    }

    $messaggioForm = get_content_between_markers($content, 'messaggioForm');
    if (isset($_POST["submit"])) {
        $errore = false;
        $username = validate_input($_POST["username"]);
        $password = validate_input($_POST["password"]);
        if ($username == "") {
            $errore = true;
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'inputError',
                '{messaggio}' => "Inserire <span lang=\"en\">Username</span>"
            ]);
        }
        if ($password == "") {
            $errore = true;
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'inputError',
                '{messaggio}' => "Inserire <span lang=\"en\">Password</span>"
            ]);
        }
        if (!$errore) {
            $utente = $connection->login($username, $password);
            if (!(is_null($utente))) {
                $_SESSION["username"] = $utente['Username'];
                $_SESSION["login"] = true;
                header("location: admin/index.php");
                exit;
            } else {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => "<span lang=\"en\">Username</span> e/o <span lang=\"en\">Password</span> errati"]);
            }
        }
        if ($errore) {
            $messaggiForm = replace_content_between_markers(
                get_content_between_markers($content, 'messaggiForm'),
                ['messaggioForm' => $messaggiForm]
            );
        }
    }
    $connection->close_DB_connection();
    
    $content = multi_replace(replace_content_between_markers($content, ['messaggiForm' => $messaggiForm]), [
        '{valoreUsername}' => $username
    ]);
} else {
    header("location: errore500.php");
    exit;
}

echo multi_replace(replace_content_between_markers($paginaHTML, [
    'logo' => $logo,
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu,
    'logout' => ''
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{style}' => $style,
    '{content}' => $content,
    '{onload}' => $onload,
    '{classList}' => $classList
]);
