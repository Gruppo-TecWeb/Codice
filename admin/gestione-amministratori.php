<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/gestione-amministratori.html");

$title = 'Gestione Amministratori &minus; Admin &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina di amministrazione per la creazione e modifica degli amministratori';
$keywords = 'Fungo, amministrazione, amministratori';
$menu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$classList = 'fullMenu';
$logo = get_content_between_markers($paginaHTML, 'logoLink');

if (!isset($_SESSION["login"])) {
    header("location: ../login.php");
    exit;
}

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

if ($connectionOk) {
    $messaggiForm = '';
    $messaggiFormHTML = get_content_between_markers($content, 'messaggiForm');
    $messaggioForm = get_content_between_markers($messaggiFormHTML, 'messaggioForm');
    $buttonElimina = get_content_between_markers($content, 'buttonElimina');
    $legend = '';
    $legendAggiungi = 'Aggiungi Amministratore';
    $legendModifica = 'Modifica Amministratore';
    $validNuovoUsername = isset($_POST['nuovoUsername']) ? validate_input($_POST['nuovoUsername']) : "";
    $validNuovaEmail = isset($_POST['nuovaEmail']) ? validate_input($_POST['nuovaEmail']) : "";
    $validUsername = isset($_GET['username']) ? validate_input($_GET['username']) : "";
    $nuovoUsername = '';
    $nuovaEmail = '';
    $username = '';
    $email = '';
    $valueAzione = '';
    if (((isset($_POST['nuovoUsername']) && $_POST['nuovoUsername'] != "") && $validNuovoUsername == "") ||
        ((isset($_POST['nuovaEmail']) && $_POST['nuovaEmail'] != "") && $validNuovaEmail == "") ||
        ((isset($_GET['username']) && $_GET['username'] != "") && $validUsername == "")) {
        header("location: amministratori.php?errore=invalid");
        exit;
    }
    $errore = false;

    $user = $connection->get_utente_by_username($validUsername);
    $validEmail = '';
    if (count($user) != 0) {
        $validEmail = $user['Email'];
    }
  
    if (isset($_GET['elimina']) || isset($_POST['elimina'])) {
        if ($_SESSION['username'] == $_GET['username']) {
            header("location: amministratori.php?eliminato=false");
        } else {
            $connection->delete_user($validUsername);
            if (count($connection->get_utente_by_email($validEmail)) != 0) {
                header("location: amministratori.php?eliminato=false");
            } else {
                header("location: amministratori.php?eliminato=true");
            }
        }
        exit;
    } elseif (isset($_GET['modifica'])) {
        $legend = $legendModifica;
        $nuovoUsername = $validUsername;
        $nuovaEmail = $validEmail;
        $username = $validUsername;
        $email = $validEmail;
        $valueAzione = 'modifica';
    } elseif (isset($_GET['aggiungi'])) {
        $buttonElimina = '';
        $legend = $legendAggiungi;
        $valueAzione = 'aggiungi';
    } elseif (isset($_POST['conferma'])) {
        $nuovoUsername = $validNuovoUsername;
        $nuovaEmail = $validNuovaEmail;
        $username = $validUsername;
        $email = $validEmail;
        if ($_POST['azione'] == 'aggiungi') {
            $legend = $legendAggiungi;
            $valueAzione = 'aggiungi';
            if ($connection->get_utente_by_username($validNuovoUsername)) {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Username già in uso'
                ]);
            }
            if ($connection->get_utente_by_email($validNuovaEmail)) {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Indirizzo e-mail già registrato'
                ]);
            }
            if (filter_var($validNuovaEmail, FILTER_VALIDATE_EMAIL) === false) {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Indirizzo e-mail non valido'
                ]);
            }
            if ($validNuovaEmail == '') {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Inserire un indirizzo e-mail'
                ]);
            }
            if ($validNuovoUsername == '') {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Inserire uno username'
                ]);
            }
            if (!$errore) {
                $connection->insert_utente($validNuovoUsername, '', $validNuovaEmail, 'A');
                if (count($connection->get_utente_by_username($validNuovoUsername)) == 0) {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'inputError',
                        '{messaggio}' => 'Errore nell\'inserimento dell\'Amministratore'
                    ]);
                } else {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'successMessage',
                        '{messaggio}' => 'Amministratore aggiunto con successo'
                    ]);
                    header("location: amministratori.php?aggiunto=true");
                    exit;
                }
            }
        } elseif ($_POST['azione'] == 'modifica') {
            $legend = $legendModifica;
            $valueAzione = 'modifica';
            if ($validUsername != $validNuovoUsername && $connection->get_utente_by_username($validNuovoUsername)) {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Username già in uso'
                ]);
            }
            if ($validEmail != $validNuovaEmail && $connection->get_utente_by_email($validNuovaEmail)) {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Indirizzo e-mail già registrato'
                ]);
            }
            if (filter_var($validNuovaEmail, FILTER_VALIDATE_EMAIL) === false) {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Indirizzo e-mail non valido'
                ]);
            }
            if (!$errore) {
                $connection->update_user($validUsername, $validNuovoUsername, $validNuovaEmail);
                $updatedUser = $connection->get_utente_by_username($validNuovoUsername);
                if (count($updatedUser) == 0 ||
                    $updatedUser['Email'] != $validNuovaEmail) {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'inputError',
                        '{messaggio}' => 'Errore nella modifica dell\'Amministratore'
                    ]);
                } else {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'successMessage',
                        '{messaggio}' => 'Amministratore modificato con successo'
                    ]);
                    $username = $validNuovoUsername;
                    if ($_SESSION['username'] == $validUsername) {
                        $_SESSION['username'] = $validNuovoUsername;
                    }
                    header("location: amministratori.php?modificato=true");
                    exit;
                }
            }
        }
    } else {
        header("location: amministratori.php?errore=invalid");
        exit;
    }

    $messaggiFormHTML = $messaggiForm == '' ? '' : replace_content_between_markers($messaggiFormHTML, ['messaggioForm' => $messaggiForm]);

    $content = multi_replace($content, [
        '{legend}' => $legend,
        '{nuovoUsername}' => $nuovoUsername,
        '{nuovaEmail}' => $nuovaEmail,
        '{username}' => $username,
        '{email}' => $email,
        '{valueAzione}' => $valueAzione
    ]);
    $content = replace_content_between_markers($content, [
        'messaggiForm' => $messaggiFormHTML,
        'buttonElimina' => $buttonElimina
    ]);

    $connection->close_DB_connection();
} else {
    header("location: ../errore500.php");
    exit;
}

echo multi_replace(replace_content_between_markers($paginaHTML, [
    'logo' => $logo,
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => $content,
    '{onload}' => $onload,
    '{classList}' => $classList
]);
