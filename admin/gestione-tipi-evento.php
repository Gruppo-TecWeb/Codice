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
    $legendAggiungi = 'Aggiungi Tipo Evento';
    $legendModifica = 'Modifica Tipo Evento';
    $validNuovoTitolo = isset($_POST['nuovoTitolo']) ? validate_input($_POST['nuovoTitolo']) : "";
    $validNuovaDescrizione = isset($_POST['nuovaDescrizione']) ? validate_input($_POST['nuovaDescrizione']) : "";
    $validTitolo = isset($_GET['titolo']) ? validate_input($_GET['titolo']) : "";
    $nuovoTitolo = '';
    $nuovaDescrizione = '';
    $titolo = '';
    $descrizione = '';
    $valueAzione = '';
    if (((isset($_POST['nuovoTitolo']) && $_POST['nuovoTitolo'] != "") && $validNuovoTitolo == "") ||
        ((isset($_POST['nuovaDescrizione']) && $_POST['nuovaDescrizione'] != "") && $validNuovaDescrizione == "") ||
        ((isset($_GET['titolo']) && $_GET['titolo'] != "") && $validTitolo == "")) {
        header("location: tipi-evento.php?errore=invalid");
        exit;
    }
    $errore = false;
  
    if (isset($_GET['elimina']) || isset($_POST['elimina'])) {
        $connection->delete_tipo_evento($validTitolo);
        if ($connection->get_tipo_evento($validTitolo)) {
            header("location: tipi-evento.php?eliminato=false");
        } else {
            header("location: tipi-evento.php?eliminato=true");
        }
        exit;
    } elseif (isset($_GET['modifica'])) {
        $legend = $legendModifica;
        $nuovoTitolo = $validTitolo;
        $nuovaDescrizione = $connection->get_tipo_evento($validTitolo)['Descrizione'];
        $titolo = $validTitolo;
        $descrizione = $nuovaDescrizione;
        $valueAzione = 'modifica';
    } elseif (isset($_GET['aggiungi'])) {
        $buttonElimina = '';
        $legend = $legendAggiungi;
        $valueAzione = 'aggiungi';
    } elseif (isset($_POST['conferma'])) {
        $nuovoTitolo = $validNuovoTitolo;
        $nuovaDescrizione = $validNuovaDescrizione;
        $titolo = $validTitolo;
        $descrizione = $validTitolo == "" ? "" : $connection->get_tipo_evento($validTitolo)['Descrizione'];
        if ($_POST['azione'] == 'aggiungi') {
            $legend = $legendAggiungi;
            $valueAzione = 'aggiungi';
            if ($validNuovoTitolo == "") {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Il titolo è obbligatorio'
                ]);
                $errore = true;
            }
            if ($connection->get_tipo_evento($validNuovoTitolo)) {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Esiste già un Tipo Evento con questo Titolo'
                ]);
                $errore = true;
            }
            if (!$errore) {
                $connection->insert_tipo_evento($validNuovoTitolo, $validNuovaDescrizione);
                if ($connection->get_tipo_evento($validNuovoTitolo)) {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'successMessage',
                        '{messaggio}' => 'Tipo Evento aggiunto con successo'
                    ]);
                    header("location: tipi-evento.php?aggiunto=true");
                    exit;
                } else {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'inputError',
                        '{messaggio}' => 'Errore nell\'inserimento del Tipo Evento'
                    ]);
                }
            }
        } elseif ($_POST['azione'] == 'modifica') {
            $legend = $legendModifica;
            $valueAzione = 'modifica';
            if ($validNuovoTitolo == "") {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Il titolo è obbligatorio'
                ]);
                $errore = true;
            }
            if ($validTitolo != $validNuovoTitolo && $connection->get_tipo_evento($validNuovoTitolo)) {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Esiste già un Tipo Evento con questo titolo'
                ]);
            }
            if (!$errore) {
                $connection->update_tipo_evento($validTitolo, $validNuovoTitolo, $validNuovaDescrizione);
                $updatedTipoEvento = $connection->get_tipo_evento($validNuovoTitolo);
                if (count($updatedTipoEvento) != 0 &&
                    $updatedTipoEvento['Descrizione'] == $validNuovaDescrizione) {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'successMessage',
                        '{messaggio}' => 'Tipo Evento modificato con successo'
                    ]);
                    $titolo = $validNuovoTitolo;
                    header("location: tipi-evento.php?modificato=true");
                    exit;
                } else {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'inputError',
                        '{messaggio}' => 'Errore nella modifica del Tipo Evento'
                    ]);
                }
            } else {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => "Tipo Evento già esistente con questo titolo"
                ]);
            }
        }
    } else {
        header("location: tipi-evento.php?errore=invalid");
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
