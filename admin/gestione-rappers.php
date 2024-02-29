<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/gestione-rappers.html");

$title = 'Gestione Rappers &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$messaggiForm = '';

if (!isset($_SESSION["login"])) {
    header("location: ../login.php");
}

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    $messaggioForm = get_content_between_markers($content, 'messaggioForm');
    $legend = '';
    $legendAggiungi = 'Aggiungi <span lang="en">Rapper</span>';
    $legendModifica = 'Modifica <span lang="en">Rapper</span>';
    $nuovoUsername = '';
    $nuovaEmail = '';
    $username = '';
    $email = '';
    $valueAzione = '';
    $errore = '0';
    
    if (isset($_GET['elimina'])) {
        if ($_SESSION["datiUtente"]['Username'] == $_GET['username']) {
            header("location: rappers.php?eliminato=0");
        } else {
            $connection->delete_user($_GET["username"]);
            $eliminato = $connection->get_utente_by_email($_GET['email']) ? 0 : 1;
            header("location: rappers.php?eliminato=$eliminato");
        }
    } elseif (isset($_GET['modifica'])) {
        $legend = $legendModifica;
        $nuovoUsername = $_GET['username'];
        $nuovaEmail = $_GET['email'];
        $username = $_GET['username'];
        $email = $_GET['email'];
        $valueAzione = 'modifica';
    } elseif (isset($_GET['aggiungi'])) {
        $legend = $legendAggiungi;
        $valueAzione = 'aggiungi';
    } elseif (isset($_POST['conferma'])) {
        $nuovoUsername = $_POST['nuovoUsername'];
        $nuovaEmail = $_POST['nuovaEmail'];
        $username = $_POST['errore'] ? $_POST['username'] : $_POST['nuovoUsername'];
        $email = $_POST['errore'] ? $_POST['email'] : $_POST['nuovaEmail'];
        if ($_POST['azione'] == 'aggiungi') {
            $errore = '0';
            $legend = $legendAggiungi;
            $valueAzione = 'aggiungi';
            $erroreUsername = $connection->get_utente_by_username($_POST['nuovoUsername']) ? '1' : '0';
            $erroreEmail = $connection->get_utente_by_email($_POST['nuovaEmail']) ? '1' : '0';
            $errore = $erroreUsername == '0' && $erroreEmail == '0' ? '0' : '1';
            if ($errore == '0') {
                $connection->register($_POST['nuovoUsername'], '', $_POST['nuovaEmail']);
                $errore = $connection->get_utente_by_username($_POST['nuovoUsername']) ? '0' : '1';
            } else {
                if ($erroreUsername == '1') {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{messaggio}' => "Username già in uso"
                    ]);
                }
                if ($erroreEmail == '1') {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{messaggio}' => "Email già registrata"
                    ]);
                }
            }
            if ($errore == '0') {
                header("location: rappers.php?aggiunto=1");
            }
        } elseif ($_POST['azione'] == 'modifica') {
            $errore = '0';
            $erroreUsername = '0';
            $erroreEmail = '0';
            $legend = $legendModifica;
            $valueAzione = 'modifica';
            if ($_POST['username'] != $_POST['nuovoUsername']) {
                $erroreUsername = $connection->get_utente_by_username($_POST['nuovoUsername']) ? '1' : '0';
            }
            if ($_POST['email'] != $_POST['nuovaEmail']) {
                $erroreEmail = $connection->get_utente_by_email($_POST['nuovaEmail']) ? '1' : '0';
            }
            $errore = $erroreUsername == '0' && $erroreEmail == '0' ? '0' : '1';
            if ($errore == '0') {
                $connection->update_user($_POST['username'], $_POST['nuovoUsername'], $_POST['nuovaEmail']);
                $user = $connection->get_utente_by_email($_POST['nuovaEmail']);
                if (count($user) == 0) {
                    $errore = '1';
                }
                $user = $connection->get_utente_by_username($_POST['nuovoUsername']);
                if (count($user) == 0) {
                    $errore = '1';
                }
            } else {
                if ($erroreUsername == '1') {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{messaggio}' => "Username già in uso"
                    ]);
                }
                if ($erroreEmail == '1') {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{messaggio}' => "Email già registrata"
                    ]);
                }
            }
            if ($errore == '0') {
                header("location: rappers.php?modificato=1");
            } else {
                $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore imprevisto"]);
            }
        }
    } else {
        header("location: rappers.php");
    }

    $content = multi_replace($content, [
        '{legend}' => $legend,
        '{nuovoUsername}' => $nuovoUsername,
        '{nuovaEmail}' => $nuovaEmail,
        '{username}' => $username,
        '{email}' => $email,
        '{valueAzione}' => $valueAzione,
        '{valueErrore}' => $errore
    ]);
    $content = replace_content_between_markers($content, [
        'messaggiForm' => $messaggiForm
    ]);

    $connection->closeDBConnection();
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
