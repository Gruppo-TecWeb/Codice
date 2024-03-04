<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/registrati.html");

$title = 'Registrati &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina dove poter effettuare l\'accesso all\'area autenticata del sito.';
$keywords = 'registrati, freestyle rap, fungo, micelio, battle, eventi, classifiche';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$username = '';
$email = '';
$messaggioForm = '';
$messaggiForm = '';

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

if ($connectionOk) {
    if (isset($_SESSION["login"])) {
        header("location: admin/index.php");
    }

    $messaggioForm = get_content_between_markers($content, 'messaggioForm');
    $messaggioFormConLink = get_content_between_markers($content, 'messaggioFormConLink');
    if (isset($_POST["submit"])) {
        $errore = false;
        $username = validate_input($_POST["username"]);
        $password = validate_input($_POST["password"]);
        $confermaPassword = validate_input($_POST["confermaPassword"]);
        $email = validate_input($_POST["email"]);
        if ($username == "") {
            $errore = true;
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Inserire Username"]);
        } else {
            $utente = $connection->get_utente_by_username($username);
            if (is_null($utente)) {
                $utente = $connection->get_utente_by_email($email);
            }
            if (!is_null($utente)) {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioFormConLink, [
                    '{messaggio}' => "Utente giá registrato, vai alla pagina di ",
                    '{pageHref}' => "login.php",
                    '{lang}' => " lang=\"en\"",
                    '{anchor}' => "login"
                ]);
            }
        }
        if ($password == "") {
            $errore = true;
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Inserire <span lang=\"en\">Password</span>"]);
        } elseif ($password != $confermaPassword) {
            $errore = true;
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Le <span lang=\"en\">Password</span> non coincidono"]);
        }
        if ($email == "") {
            $errore = true;
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Inserire <span lang=\"en\">E-Mail</span>"]);
        }
        if (!$errore) {
            $utenteRegistrato = $connection->insert_utente($username, $password, $email);
            if ($utenteRegistrato > 0) {
                $_SESSION["datiUtente"] = array("Username" => $username, "Email" => $email);
                $_SESSION["login"] = true;
                header("location: admin/index.php");
                $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Registrazione avvenuta correttamente"]);
            } else {
                $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "La registrazione non é avvenuta"]);
            }
        } else {
            $messaggiForm = replace_content_between_markers(
                get_content_between_markers($content, 'messaggiForm'),
                [
                    'messaggioForm' => $messaggiForm,
                    'messaggioFormConLink' => ''
                ]
            );
        }
    }

    $connection->close_DB_connection();
    $content = multi_replace(replace_content_between_markers($content, [
        'messaggiForm' => $messaggiForm
    ]), [
        '{valoreUsername}' => $username,
        '{valoreEmail}' => $email
    ]);
} else {
    header("location: errore500.php");
}

echo multi_replace(replace_content_between_markers($paginaHTML, [
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu,
    'logout' => ''
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => $content,
    '{onload}' => $onload
]);
