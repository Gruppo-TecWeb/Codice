<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/profilo.html");

$title = 'Profilo personale &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina profilo contenente le informazioni relative al proprio profilo utente.';
$keywords = 'profilo, amministrazione, admin, restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
$menu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$errori = '';
$username = '';
$email = '';
$formEmail = '';
$formModificaEmail = '';
$formModificaPassword = '';
$messaggioProfilo = '';
$messaggioForm = '';
$messaggiProfilo = '';
$messaggiForm = '';

if (!isset($_SESSION["login"])) {
    header("location: ../login.php");
}

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    $utente = $_SESSION["datiUtente"];
    $username = $utente["Username"];
    $email = $utente["Email"];
    $messaggioProfilo = get_content_between_markers($content, 'messaggioProfilo');
    $messaggioForm = get_content_between_markers($content, 'messaggioForm');

    if (isset($_GET["submitEmail"])) {
        $formModificaEmail = get_content_between_markers($content, 'formModificaEmail');
    } elseif (isset($_GET["submitPassword"])) {
        $formModificaPassword = get_content_between_markers($content, 'formModificaPassword');
    }

    if (isset($_POST["submitNewEmail"])) {
        $errore = false;
        $formEmail = validate_input($_POST["email"]);
        if ($formEmail == "") {
            $errore = true;
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Inserire nuova e-Mail."]);
        } else {
            $utente = $connection->get_utente_by_email($formEmail);
            if (!(is_null($utente))) {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Un utente risulta giá registrato con questa e-Mail."]);
            }
        }
        if (!$errore) {
            $mailModificata = $connection->change_email($username, $formEmail);
            if ($mailModificata) {
                $_SESSION["datiUtente"]["Email"] = $formEmail;
                $email = $formEmail;
                $messaggiProfilo .= multi_replace($messaggioProfilo, ['{messaggio}' => "E-Mail aggiornata con successo."]);
            }
        } else {
            $formModificaEmail = get_content_between_markers($content, 'formModificaEmail');
            $messaggiForm = replace_content_between_markers(
                get_content_between_markers($content, 'messaggiForm'),
                ['messaggioForm' => $messaggiForm]
            );
        }
    } elseif (isset($_POST["submitNewPassword"])) {
        $errore = false;
        $formPassword = validate_input($_POST["password"]);
        $formConfermaPassword = validate_input($_POST["confermaPassword"]);
        if ($formPassword == "") {
            $errore = true;
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Inserire nuova password."]);
        } elseif ($formPassword != $formConfermaPassword) {
            $errore = true;
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Le password non coincidono."]);
        }
        if (!$errore) {
            $passwordModificata = $connection->change_password($username, $formPassword);
            if ($passwordModificata) {
                $messaggiProfilo .= multi_replace($messaggioProfilo, ['{messaggio}' => "Password aggiornata con successo."]);
            }
        } else {
            $formModificaPassword = get_content_between_markers($content, 'formModificaPassword');
            $messaggiForm = replace_content_between_markers(
                get_content_between_markers($content, 'messaggiForm'),
                ['messaggioForm' => $messaggiForm]
            );
        }
    }

    $connection->closeDBConnection();
    $content = multi_replace(replace_content_between_markers($content, [
        'messaggiProfilo' => $messaggiProfilo,
        'formModificaEmail' => replace_content_between_markers($formModificaEmail, ['messaggiForm' => $messaggiForm]),
        'formModificaPassword' => replace_content_between_markers($formModificaPassword, ['messaggiForm' => $messaggiForm]),
    ]), [
        '{username}' => $username,
        '{email}' => $email,
        '{formEmail}' => $formEmail
    ]);
} else {
    header("location: ../errore500.php");
}

echo multi_replace(replace_content_between_markers($paginaHTML, [
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => $content,
    '{onload}' => $onload
]);
