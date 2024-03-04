<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/gestione-tipi-evento.html");

$title = 'Gestione Tipi Evento &minus; Fungo';
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
    $legendAggiungi = 'Aggiungi Tipo Evento';
    $legendModifica = 'Modifica Tipo Evento';
    $validNuovoTitolo = isset($_POST['nuovoTitolo']) ? validate_input($_POST['nuovoTitolo']) : "";
    $validNuovaDescrizione = isset($_POST['nuovaDescrizione']) ? validate_input($_POST['nuovaDescrizione']) : "";
    $validTitolo = isset($_POST['titolo']) ? validate_input($_POST['titolo']) : "";
    $nuovoTitolo = '';
    $nuovaDescrizione = '';
    $titolo = '';
    $descrizione = '';
    $valueAzione = '';
    if (((isset($_POST['nuovoTitolo']) && $_POST['nuovoTitolo'] != "") && $validNuovoTitolo == "") ||
        ((isset($_POST['nuovaDescrizione']) && $_POST['nuovaDescrizione'] != "") && $validNuovaDescrizione == "") ||
        ((isset($_POST['titolo']) && $_POST['titolo'] != "") && $validTitolo == "")) {
        header("location: tipi-evento.php?errore=invalid");
    }
    $errore = '0';
    
    if (isset($_POST['elimina'])) {
        $connection->delete_tipo_evento($validTitolo);
        $eliminato = $connection->get_tipo_evento($validTitolo) ? 0 : 1;
        header("location: tipi-evento.php?eliminato=$eliminato");
    } elseif (isset($_POST['modifica'])) {
        $legend = $legendModifica;
        $nuovoTitolo = $validTitolo;
        $nuovaDescrizione = $connection->get_tipo_evento($validTitolo)['Descrizione'];
        $titolo = $validTitolo;
        $descrizione = $nuovaDescrizione;
        $valueAzione = 'modifica';
    } elseif (isset($_POST['aggiungi'])) {
        $legend = $legendAggiungi;
        $valueAzione = 'aggiungi';
    } elseif (isset($_POST['conferma'])) {
        $nuovoTitolo = $validNuovoTitolo;
        $nuovaDescrizione = $validNuovaDescrizione;
        $titolo = $validTitolo;
        $descrizione = $connection->get_tipo_evento($validTitolo)[0]['Descrizione'];
        if ($_POST['azione'] == 'aggiungi') {
            $errore = '0';
            $legend = $legendAggiungi;
            $valueAzione = 'aggiungi';
            $errore = $connection->get_tipo_evento($validNuovoTitolo) ? '1' : '0';
            if ($errore == '0') {
                $connection->insert_tipo_evento($validNuovoTitolo, $validNuovaDescrizione);
                $errore = $connection->get_tipo_evento($validNuovoTitolo) ? '0' : '1';
            } else {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{messaggio}' => "Tipo Evento già esistente con questo titolo"
                ]);
            }
            if ($errore == '0') {
                header("location: tipi-evento.php?aggiunto=1");
            }
        } elseif ($_POST['azione'] == 'modifica') {
            $errore = '0';
            $legend = $legendModifica;
            $valueAzione = 'modifica';
            if ($validTitolo != $validNuovoTitolo) {
                $errore = $connection->get_tipo_evento($validNuovoTitolo) ? '1' : '0';
            }
            if ($errore == '0') {
                $connection->update_tipo_evento($validTitolo, $validNuovoTitolo, $validNuovaDescrizione);
                $tipo_evento = $connection->get_tipo_evento($validNuovoTitolo);
                if (count($tipo_evento) == 0) {
                    $errore = '1';
                }
            } else {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{messaggio}' => "Tipo Evento già esistente con questo titolo"
                ]);
            }
            if ($errore == '0') {
                header("location: tipi-evento.php?modificato=1");
            } else {
                $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore imprevisto"]);
            }
        }
    } else {
        header("location: tipi-evento.php");
    }

    $content = multi_replace($content, [
        '{legend}' => $legend,
        '{nuovoTitolo}' => $nuovoTitolo,
        '{nuovaDescrizione}' => $nuovaDescrizione,
        '{titolo}' => $titolo,
        '{descrizione}' => $descrizione,
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
