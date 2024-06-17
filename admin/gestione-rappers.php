<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/gestione-rappers.html");

$title = 'Gestione Rappers &minus; Admin &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'pagina di amministrazione per la creazione e modifica dei rappers';
$keywords = 'Fungo, amministrazione, rappers';
$menu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

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
    $legendAggiungi = 'Aggiungi <span lang="en">Rapper</span>';
    $legendModifica = 'Modifica <span lang="en">Rapper</span>';
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
        ((isset($_POST['username']) && $_POST['username'] != "") && $validUsername == "")) {
        header("location: rappers.php?errore=invalid");
        exit;
    }
    $errore = '0';

    $user = $connection->get_utente_by_username($validUsername);
    $validEmail = '';
    if (count($user) != 0) {
        $validEmail = $user['Email'];
    }
  
    if (isset($_GET['elimina']) || isset($_POST['elimina'])) {
        if ($_SESSION['username'] == $_GET['username']) {
            header("location: rappers.php?eliminato=false");
        } else {
            $connection->delete_user($validUsername);
            if ($connection->get_utente_by_username($validUsername)) {
                header("location: rappers.php?eliminato=false");
            } else {
                header("location: rappers.php?eliminato=true");
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
            $errore = '0';
            $legend = $legendAggiungi;
            $valueAzione = 'aggiungi';
            $erroreUsername = $connection->get_utente_by_username($validNuovoUsername) ? '1' : '0';
            $erroreEmail = $connection->get_utente_by_email($validNuovaEmail) ? '1' : '0';
            $errore = $erroreUsername == '0' && $erroreEmail == '0' ? '0' : '1';
            if ($errore == '0') {
                $connection->insert_utente($validNuovoUsername, '', $validNuovaEmail);
                $errore = $connection->get_utente_by_username($validNuovoUsername) ? '0' : '1';
            } else {
                if ($erroreUsername == '1') {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'inputError',
                        '{messaggio}' => "Nome d'arte già utilizzato"
                    ]);
                }
                if ($erroreEmail == '1') {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'inputError',
                        '{messaggio}' => "<span lang=\"en\">E-Mail</span> già registrata"
                    ]);
                }
            }
            if ($errore == '0') {
                header("location: rappers.php?aggiunto=true");
                exit;
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
                        '{tipoMessaggio}' => 'inputError',
                        '{messaggio}' => "Nome d'arte già utilizzato"
                    ]);
                }
                if ($erroreEmail == '1') {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'inputError',
                        '{messaggio}' => "<span lang=\"en\">E-Mail</span> già registrata"
                    ]);
                }
            }
            if ($errore == '0') {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'successMessage',
                    '{messaggio}' => 'Modifica effettuata con successo'
                ]);
                $username = $validNuovoUsername;

                header("location: rappers.php?modificato=true");
                exit;
            } else {
                $messaggiForm .= $messaggiForm == '' ? multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => "Errore imprevisto"
                    ]) : '';
            }
        }
    } else {
        header("location: rappers.php?errore=invalid");
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
