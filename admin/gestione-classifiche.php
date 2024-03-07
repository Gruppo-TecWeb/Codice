<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/gestione-classifiche.html");

$title = 'Gestione Classifiche &minus; Fungo';
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
    $legendAggiungi = 'Aggiungi Classifica';
    $legendModifica = 'Modifica Classifica';
    $listaTipoEvento = '';
    $selezioneDefault = '';
    $validNuovoTipoEvento = isset($_POST['nuovoTipoEvento']) ? validate_input($_POST['nuovoTipoEvento']) : "";
    $validNuovaDataInizio = isset($_POST['nuovaDataInizio']) ? validate_input($_POST['nuovaDataInizio']) : "";
    $validNuovaDataFine = isset($_POST['nuovaDataFine']) ? validate_input($_POST['nuovaDataFine']) : "";
    $validTipoEvento = isset($_POST['tipoEvento']) ? validate_input($_POST['tipoEvento']) : "";
    $validDataInizio = isset($_POST['dataInizio']) ? validate_input($_POST['dataInizio']) : "";
    $validDataFine = isset($_POST['dataFine']) ? validate_input($_POST['dataFine']) : "";
    $validIdEvento = isset($_POST['idEvento']) ? validate_input($_POST['idEvento']) : "";
    $validEventiSelezionati = [];
    if (isset($_POST['eventi'])) {
        foreach ($_POST['eventi'] as $evento) {
            $validEventiSelezionati[] = validate_input($evento);
            if ($validEventiSelezionati[count($validEventiSelezionati) - 1] == "" && $evento != "") {
                header("location: classifiche.php?errore=invalid");
            }
        }
    }
    $nuovoTipoEvento = '';
    $nuovaDataInizio = '';
    $nuovaDataFine = '';
    $tipoEvento = '';
    $dataInizio = '';
    $dataFine = '';
    $valueAzione = '';
    $listaEventiChecked = '';
    $listaEventiUnchecked = '';
    $nessunEvento = '';
    if (((isset($_POST['nuovoTipoEvento']) && $_POST['nuovoTipoEvento'] != "") && $validNuovoTipoEvento == "") ||
        ((isset($_POST['nuovaDataInizio']) && $_POST['nuovaDataInizio'] != "") && $validNuovaDataInizio == "") ||
        ((isset($_POST['nuovaDataFine']) && $_POST['nuovaDataFine'] != "") && $validNuovaDataFine == "") ||
        ((isset($_POST['tipoEvento']) && $_POST['tipoEvento'] != "") && $validTipoEvento == "") ||
        ((isset($_POST['dataInizio']) && $_POST['dataInizio'] != "") && $validDataInizio == "") ||
        ((isset($_POST['dataFine']) && $_POST['dataFine'] != "") && $validDataFine == "") ||
        ((isset($_POST['idEvento']) && $_POST['idEvento'] != "") && $validIdEvento == "")) {
                header("location: classifiche.php?errore=invalid");
    }
    $errore = '0';
    
    // costruisco la lista di option per la selezione del tipo evento
    $tipiEvento = $connection->get_tipi_evento();
    $optionTipoEvento = get_content_between_markers($content, 'listaTipoEvento');
    foreach ($tipiEvento as $tipoE) {
        $selected = '';
        if ($validNuovoTipoEvento != "") {
            $selected = $validNuovoTipoEvento == $tipoE['Titolo'] ? ' selected' : '';
        } elseif ($validTipoEvento != "") {
            $selected = $validTipoEvento == $tipoE['Titolo'] ? ' selected' : '';
        }
        $listaTipoEvento .= multi_replace($optionTipoEvento, [
            '{tipoEvento}' => $tipoE['Titolo'],
            '{selezioneTipoEvento}' => $selected 
        ]);
    }
    
    if (isset($_POST['punteggi'])) {
        header("location: gestione-punteggi.php?idEvento=$validIdEvento");
    } elseif (isset($_POST['elimina'])) {
        $connection->delete_classifica($validTipoEvento, $validDataInizio);
        $eliminato = $connection->get_classifiche($validTipoEvento, $validDataInizio) ? 0 : 1;
        header("location: classifiche.php?eliminato=$eliminato");
    } elseif (isset($_POST['modifica'])) {
        $legend = $legendModifica;
        $nuovoTipoEvento = $validTipoEvento;
        $nuovaDataInizio = $validDataInizio;
        $nuovaDataFine = $validDataFine;
        $tipoEvento = $validTipoEvento;
        $dataInizio = $validDataInizio;
        $dataFine = $validDataFine;
        $valueAzione = 'modifica';
    } elseif (isset($_POST['aggiungi'])) {
        $legend = $legendAggiungi;
        $selezioneDefault = ' selected';
        $nessunEvento = get_content_between_markers($content, 'nessunEvento');
        $valueAzione = 'aggiungi';
    } elseif(isset($_POST['mostraEventi'])) {
        $nuovoTipoEvento = $validNuovoTipoEvento;
        $nuovaDataInizio = $validNuovaDataInizio;
        $nuovaDataFine = $validNuovaDataFine;
        $tipoEvento = $validTipoEvento;
        $dataInizio = $validDataInizio;
        $dataFine = $validDataFine;
        $valueAzione = validate_input($_POST['azione']);
    } elseif (isset($_POST['conferma'])) {
        $nuovoTipoEvento = $validNuovoTipoEvento;
        $nuovaDataInizio = $validNuovaDataInizio;
        $nuovaDataFine = $validNuovaDataFine;
        $tipoEvento = $validTipoEvento;
        $dataInizio = $validDataInizio;
        $dataFine = $validDataFine;
        if ($_POST['azione'] == 'aggiungi') {
            $errore = '0';
            $legend = $legendAggiungi;
            $valueAzione = 'aggiungi';
            $errore = $connection->get_classifiche($validNuovoTipoEvento, $validNuovaDataInizio) ? '1' : '0';
            if ($errore == '0') {
                $connection->insert_classifica($validNuovoTipoEvento, $validNuovaDataInizio, $validNuovaDataFine);
                $connection->insert_classifica_eventi($validNuovoTipoEvento, $validNuovaDataInizio, $validEventiSelezionati);
                $errore = $connection->get_classifiche($validNuovoTipoEvento, $validNuovaDataInizio) ? '0' : '1';
            } else {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{messaggio}' => "Classifica già esistente con questo Tipo Evento e con questa data di inizio"
                ]);
            }
            if ($errore == '0') {
                header("location: classifiche.php?aggiunto=1");
            }
        } elseif ($_POST['azione'] == 'modifica') {
            $errore = '0';
            $legend = $legendModifica;
            $valueAzione = 'modifica';
            if ($validTipoEvento != $validNuovoTipoEvento || $validDataInizio != $validNuovaDataInizio) {
                $errore = $connection->get_classifiche($validNuovoTipoEvento, $validNuovaDataInizio) ? '1' : '0';
            }
            if ($errore == '0') {
                $connection->update_classifica(
                    $validTipoEvento, $validDataInizio, $validNuovoTipoEvento, $validNuovaDataInizio, $validNuovaDataFine);
                $connection->update_classifica_eventi($validNuovoTipoEvento, $validNuovaDataInizio, $validEventiSelezionati);
                $errore = $connection->get_classifiche($validNuovoTipoEvento, $validNuovaDataInizio) ? '0' : '1';
            } else {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{messaggio}' => "Classifica già esistente con questo Tipo Evento e con questa data di inizio"
                ]);
            }
            if ($errore == '0') {
                header("location: classifiche.php?modificato=1");
            } else {
                $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore imprevisto"]);
            }
        }
    } else {
        header("location: classifiche.php");
    }
    
    // costruisco la lista di option per la selezione degli eventi
    if (!isset($_POST["aggiungi"])) {
        $eventiSelezionati = $connection->get_eventi_selezionati($validTipoEvento, $validDataInizio);
        $eventiSelezionabili = $connection->get_eventi_selezionabili(
            $validNuovaDataInizio != "" ? $validNuovaDataInizio : $validDataInizio,
            $validNuovaDataFine != "" ? $validNuovaDataFine : $validDataFine
        );
        $checkEventoChecked = get_content_between_markers($content, 'listaEventiChecked');
        $checkEventoUnchecked = get_content_between_markers($content, 'listaEventiUnchecked');

        foreach ($eventiSelezionati as $eventoSelezionato) {
            $listaEventiChecked .= multi_replace($checkEventoChecked, [
                '{idEvento}' => $eventoSelezionato['Id'],
                '{titoloEvento}' => $eventoSelezionato['Titolo'],
                '{dataEvento}' => date_format(date_create($eventoSelezionato['Data']), 'Y-m-d'),
                '{dataVisualizzataEvento}' => date_format(date_create($eventoSelezionato['Data']), 'd/m/y')
            ]);
        }
        foreach ($eventiSelezionabili as $eventoSelezionabile) {
            $listaEventiUnchecked .= multi_replace($checkEventoUnchecked, [
                '{idEvento}' => $eventoSelezionabile['Id'],
                '{titoloEvento}' => $eventoSelezionabile['Titolo'],
                '{dataEvento}' => date_format(date_create($eventoSelezionabile['Data']), 'Y-m-d'),
                '{dataVisualizzataEvento}' => date_format(date_create($eventoSelezionabile['Data']), 'd/m/y')
            ]);
        }
        if ($listaEventiChecked === '' && $listaEventiUnchecked === '') {
            $nessunEvento = get_content_between_markers($content, 'nessunEvento');
        }
    }

    $content = multi_replace($content, [
        '{legend}' => $legend,
        '{selezioneDefault}' => $selezioneDefault,
        '{nuovoTipoEvento}' => $nuovoTipoEvento,
        '{nuovaDataInizio}' => $nuovaDataInizio,
        '{nuovaDataFine}' => $nuovaDataFine,
        '{tipoEvento}' => $tipoEvento,
        '{dataInizio}' => $dataInizio,
        '{dataFine}' => $dataFine,
        '{valueAzione}' => $valueAzione
    ]);
    $content = replace_content_between_markers($content, [
        'listaTipoEvento' => $listaTipoEvento,
        'messaggiForm' => $messaggiForm,
        'listaEventiChecked' => $listaEventiChecked,
        'listaEventiUnchecked' => $listaEventiUnchecked,
        'nessunEvento' => $nessunEvento
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
