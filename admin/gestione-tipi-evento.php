<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/gestione-tipi-evento.html");

$title = 'Gestione Tipi Evento &minus; Admin &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'pagina di amministrazione per la creazione e modifica dei tipi evento';
$keywords = 'Fungo, amministrazione, tipi evento';
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
        exit;
    }
    $errore = '0';
  
    if (isset($_POST['elimina'])) {
        $connection->delete_tipo_evento($validTitolo);
        $eliminato = $connection->get_tipo_evento($validTitolo) ? 0 : 1;
        header("location: tipi-evento.php?eliminato=$eliminato");
        exit;
    } elseif (isset($_POST['modifica'])) {
        $legend = $legendModifica;
        $nuovoTitolo = $validTitolo;
        $nuovaDescrizione = $connection->get_tipo_evento($validTitolo)['Descrizione'];
        $titolo = $validTitolo;
        $descrizione = $nuovaDescrizione;
        $valueAzione = 'modifica';
    } elseif (isset($_POST['aggiungi'])) {
        $buttonElimina = '';
        $legend = $legendAggiungi;
        $valueAzione = 'aggiungi';
    } elseif (isset($_POST['conferma'])) {
        $nuovoTitolo = $validNuovoTitolo;
        $nuovaDescrizione = $validNuovaDescrizione;
        $titolo = $validTitolo;
        $descrizione = $validTitolo == "" ? "" : $connection->get_tipo_evento($validTitolo)['Descrizione'];
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
                exit;
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
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{messaggio}' => 'Modifica effettuata con successo'
                ]);
                $titolo = $validNuovoTitolo;
            } else {
                $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore imprevisto"]);
            }
        }
    } else {
        header("location: tipi-evento.php");
        exit;
    }

    $messaggiFormHTML = $messaggiForm == '' ? '' : replace_content_between_markers($messaggiFormHTML, ['messaggioForm' => $messaggiForm]);

    $content = multi_replace($content, [
        '{legend}' => $legend,
        '{nuovoTitolo}' => $nuovoTitolo,
        '{nuovaDescrizione}' => $nuovaDescrizione,
        '{titolo}' => $titolo,
        '{descrizione}' => $descrizione,
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
