<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/gestione-profilo.html");

$title = 'Gestione Profilo &minus; Admin &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina di amministrazione per la modifica del proprio profilo utente.';
$keywords = 'profilo, amministrazione, admin';
$menu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

$immagineProfiloDefault = 'default_profile_pic.png';
$percorsoImmaginiProfilo = './../assets/media/img_profilo/';

if (!isset($_SESSION["login"])) {
    header("location: ../login.php");
    exit;
}

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

if ($connectionOk) {
    $validEmail = isset($_POST['email']) ? validate_input($_POST['email']) : '';
    $validPassword = isset($_POST['password']) ? validate_input($_POST['password']) : '';
    $validConfermaPassword = isset($_POST['confermaPassword']) ? validate_input($_POST['confermaPassword']) : '';
    $validImmagineProfilo = isset($_FILES['immagineProfilo']) ? basename($_FILES['immagineProfilo']["name"]) : null;
    $validUsername = $_SESSION["username"];
    if (((isset($_POST['email']) && $_POST['email'] != "") && $validEmail == "") ||
        ((isset($_POST['password']) && $_POST['password'] != "") && $validPassword == "") ||
        ((isset($_POST['confermaPassword']) && $_POST['confermaPassword'] != "") && $validConfermaPassword == "") ||
        ((isset($_FILES['immagineProfilo']) && $_FILES['immagineProfilo']['name'] != "") && $validImmagineProfilo == null)) {
        header("location: profilo.php?errore=invalid");
        exit;
    }
    $errore = '0';
    $messaggiForm = '';
    $messaggioForm = get_content_between_markers($content, 'messaggioForm');

    $eliminaImmagineProfilo = isset($_POST['eliminaImmagineProfilo']) ? true : false;
    $utente = $connection->get_utente_by_username($validUsername);

    if ($utente === null) {
        header("location: ../errore500.php");
        exit;
    }

    $username = $utente["Username"];
    $email = $utente["Email"];
    $immagineProfilo = $utente["ImmagineProfilo"];

    if (isset($_POST["conferma"])) {
        $errore = false;
        $modificato = false;

        if ($validEmail != '' && $email != $validEmail) {
            if (count($connection->get_utente_by_email($validEmail)) > 0) {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{messaggio}' => 'Questa e-mail è già associata ad un altro account'
                ]);
                $errore = true;
            } else {
                $connection->change_email($username, $validEmail);
                $modificato = true;
                $email = $validEmail;
            }
        }

        if (!$errore && $validPassword != '' && $validConfermaPassword != '') {
            if ($validPassword != $validConfermaPassword) {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{messaggio}' => 'Le password non coincidono'
                ]);
                $errore = true;
            } else {
                $connection->change_password($username, $validPassword);
                $modificato = true;
            }
        }

        if (!$errore && !$eliminaImmagineProfilo && $validImmagineProfilo) {
            $errori = carica_file($_FILES["immagineProfilo"], $percorsoImmaginiProfilo, $username . '_' . $validImmagineProfilo);
            if (count($errori) > 0) {
                foreach ($errori as $errore) {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{messaggio}' => $errore
                    ]);
                }
                $errore = true;
            } else {
                if ($immagineProfilo != null) {
                    unlink($percorsoImmaginiProfilo . $immagineProfilo);
                }
                $connection->change_profile_pic($username, $username . '_' . $validImmagineProfilo);
                $modificato = true;
            }
        } else if (!$errore && $eliminaImmagineProfilo && $immagineProfilo != null) {
            unlink($percorsoImmaginiProfilo . $immagineProfilo);
            $connection->change_profile_pic($username, '');
            $modificato = true;
        }
        if (!$errore && $modificato) {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{messaggio}' => 'Modifica effettuata con successo'
            ]);
        }
    }

    $content = replace_content_between_markers(multi_replace($content, [
        '{username}' => $username,
        '{email}' => $email
    ]), [
        'messaggiForm' => $messaggiForm
    ]);
} else {
    header("location: ../errore500.php");
    exit;
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
