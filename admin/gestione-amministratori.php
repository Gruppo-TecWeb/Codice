<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/gestione-amministratori.html");

$title = 'Gestione Amministratori &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

if (!isset($_SESSION["login"])) {
    header("location: ../login.php");
}

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

if ($connectionOk) {
    $messaggiForm = '';
    $messaggioForm = get_content_between_markers($content, 'messaggioForm');
    $legend = '';
    $legendAggiungi = 'Aggiungi Amministratore';
    $legendModifica = 'Modifica Amministratore';
    $validNuovoUsername = isset($_POST['nuovoUsername']) ? validate_input($_POST['nuovoUsername']) : "";
    $validNuovaEmail = isset($_POST['nuovaEmail']) ? validate_input($_POST['nuovaEmail']) : "";
    $validUsername = isset($_POST['username']) ? validate_input($_POST['username']) : "";
    $validEmail = isset($_POST['email']) ? validate_input($_POST['email']) : "";
    $nuovoUsername = '';
    $nuovaEmail = '';
    $username = '';
    $email = '';
    $valueAzione = '';
    if (((isset($_POST['nuovoUsername']) && $_POST['nuovoUsername'] != "") && $validNuovoUsername == "") ||
        ((isset($_POST['nuovaEmail']) && $_POST['nuovaEmail'] != "") && $validNuovaEmail == "") ||
        ((isset($_POST['username']) && $_POST['username'] != "") && $validUsername == "") ||
        ((isset($_POST['email']) && $_POST['email'] != "") && $validEmail == "")) {
        header("location: amministratori.php?errore=invalid");
    }
    $errore = '0';
    
    if (isset($_POST['elimina'])) {
        if ($_SESSION["datiUtente"]['Username'] == $_POST['username']) {
            header("location: amministratori.php?eliminato=0");
        } else {
            $connection->delete_user($validUsername);
            $eliminato = $connection->get_utente_by_email($validEmail) ? 0 : 1;
            header("location: amministratori.php?eliminato=$eliminato");
        }
    } elseif (isset($_POST['modifica'])) {
        $legend = $legendModifica;
        $nuovoUsername = $validUsername;
        $nuovaEmail = $validEmail;
        $username = $validUsername;
        $email = $validEmail;
        $valueAzione = 'modifica';
    } elseif (isset($_POST['aggiungi'])) {
        $legend = $legendAggiungi;
        $valueAzione = 'aggiungi';
    } elseif (isset($_POST['conferma'])) {
        $nuovoUsername = $validNuovoUsername;
        $nuovaEmail = $validNuovaEmail;
        $username = $validUsername;
        $email = $validEmail;
        if ($_POST['azione'] == 'aggiungi') {
            $errore = '0';
            $legend = $legendAggiungi;
            $valueAzione = 'aggiungi';
            $erroreUsername = $connection->get_utente_by_username($validNuovoUsername) ? '1' : '0';
            $erroreEmail = $connection->get_utente_by_email($validNuovaEmail) ? '1' : '0';
            $errore = $erroreUsername == '0' && $erroreEmail == '0' ? '0' : '1';
            if ($errore == '0') {
                $connection->insert_utente($validNuovoUsername, '', $validNuovaEmail, 'S');
                $errore = $connection->get_utente_by_username($validNuovoUsername) ? '0' : '1';
            } else {
                if ($erroreUsername == '1') {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{messaggio}' => "<span lang=\"en\">Username</span> già in uso"
                    ]);
                }
                if ($erroreEmail == '1') {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{messaggio}' => "<span lang=\"en\">E-Mail</span> già registrata"
                    ]);
                }
            }
            if ($errore == '0') {
                header("location: amministratori.php?aggiunto=1");
            }
        } elseif ($_POST['azione'] == 'modifica') {
            $errore = '0';
            $erroreUsername = '0';
            $erroreEmail = '0';
            $legend = $legendModifica;
            $valueAzione = 'modifica';
            if ($validUsername != $validNuovoUsername) {
                $erroreUsername = $connection->get_utente_by_username($validNuovoUsername) ? '1' : '0';
            }
            if ($validEmail != $validNuovaEmail) {
                $erroreEmail = $connection->get_utente_by_email($validNuovaEmail) ? '1' : '0';
            }
            $errore = $erroreUsername == '0' && $erroreEmail == '0' ? '0' : '1';
            if ($errore == '0') {
                $connection->update_user($validUsername, $validNuovoUsername, $validNuovaEmail);
                $user = $connection->get_utente_by_email($validNuovaEmail);
                if (count($user) == 0) {
                    $errore = '1';
                }
                $user = $connection->get_utente_by_username($validNuovoUsername);
                if (count($user) == 0) {
                    $errore = '1';
                }
            } else {
                if ($erroreUsername == '1') {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{messaggio}' => "<span lang=\"en\">Username</span> già in uso"
                    ]);
                }
                if ($erroreEmail == '1') {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{messaggio}' => "<span lang=\"en\">E-Mail</span> già registrata"
                    ]);
                }
            }
            if ($errore == '0') {
                header("location: amministratori.php?modificato=1");
            } else {
                $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore imprevisto"]);
            }
        }
    } else {
        header("location: amministratori.php");
    }

    $content = multi_replace($content, [
        '{legend}' => $legend,
        '{nuovoUsername}' => $nuovoUsername,
        '{nuovaEmail}' => $nuovaEmail,
        '{username}' => $username,
        '{email}' => $email,
        '{valueAzione}' => $valueAzione
    ]);
    $content = replace_content_between_markers($content, [
        'messaggiForm' => $messaggiForm
    ]);

    $connection->close_DB_connection();
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
