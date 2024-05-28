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
$description = 'pagina di amministrazione per la creazione e modifica delle classifiche';
$keywords = 'Fungo, amministrazione, classifiche';
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
    $validNuovoTitolo = isset($_POST['nuovoTitoloClassifica']) ? validate_input($_POST['nuovoTitoloClassifica']) : "";
    $validNuovoTipoEvento = isset($_POST['nuovoTipoEvento']) ? validate_input($_POST['nuovoTipoEvento']) : "";
    $validNuovaDataInizio = isset($_POST['nuovaDataInizio']) ? validate_input($_POST['nuovaDataInizio']) : "";
    $validNuovaDataFine = isset($_POST['nuovaDataFine']) ? validate_input($_POST['nuovaDataFine']) : "";
    $validIdEvento = isset($_POST['idEvento']) ? validate_input($_POST['idEvento']) : "";
    $validIdCLassifica = isset($_POST['idClassifica']) ? validate_input($_POST['idClassifica']) : "";
    if (((isset($_POST['nuovoTitoloClassifica']) && $_POST['nuovoTitoloClassifica'] != "") && $validNuovoTitolo == "") ||
        ((isset($_POST['nuovoTipoEvento']) && $_POST['nuovoTipoEvento'] != "") && $validNuovoTipoEvento == "") ||
        ((isset($_POST['nuovaDataInizio']) && $_POST['nuovaDataInizio'] != "") && $validNuovaDataInizio == "") ||
        ((isset($_POST['nuovaDataFine']) && $_POST['nuovaDataFine'] != "") && $validNuovaDataFine == "") ||
        ((isset($_POST['idEvento']) && $_POST['idEvento'] != "") && $validIdEvento == "") ||
        ((isset($_POST['idClassifica']) && $_POST['idClassifica'] != "") && $validIdCLassifica == "") ||
        (isset($_POST['punteggi']) && $validIdEvento == "") ||
        $validIdCLassifica != "" && $connection->get_classifica($validIdCLassifica) == null) {
                header("location: classifiche.php?errore=invalid");
                exit;
    }
    $errore = '0';

    if (isset($_POST['punteggi'])) {
        header("location: gestione-punteggi.php?idEvento=$validIdEvento");
        exit;
    }

    if (isset($_POST['elimina'])) {
        $connection->delete_classifica($validIdCLassifica);
        $eliminato = $connection->get_classifica($validIdCLassifica) ? 0 : 1;
        header("location: classifiche.php?eliminato=$eliminato");
        exit;
    }

    $messaggiForm = '';
    $messaggioForm = get_content_between_markers($content, 'messaggioForm');
    $buttonElimina = get_content_between_markers($content, 'buttonElimina');
    $listaTipoEvento = '';

    $classifica = $validIdCLassifica == "" ? null : $connection->get_classifica($validIdCLassifica);
    
    // costruisco la lista di option per la selezione del tipo evento
    $tipiEvento = $connection->get_tipi_evento();
    $optionTipoEvento = get_content_between_markers($content, 'listaTipoEvento');
    foreach ($tipiEvento as $tipoEvento) {
        $selected = '';
        if ($validNuovoTipoEvento != "") {
            $selected = $validNuovoTipoEvento == $tipoEvento['Titolo'] ? ' selected' : '';
        } elseif ($classifica && $classifica['TipoEvento'] == $tipoEvento['Titolo']) {
            $selected = ' selected';
        }
        $listaTipoEvento .= multi_replace($optionTipoEvento, [
            '{tipoEvento}' => $tipoEvento['Titolo'],
            '{selezioneTipoEvento}' => $selected
        ]);
    }
    
    $valueAzione = '';
    $eventiHTML = '';
    $legend = '';
    $legendAggiungi = 'Aggiungi Classifica';
    $legendModifica = 'Modifica Classifica';
    $selezioneDefault = '';
    $nessunEvento = '';
    $nuovoTitoloClassifica = '';
    $nuovoTipoEvento = '';
    $nuovaDataInizio = '';
    $nuovaDataFine = '';
    
    if (isset($_POST['modifica'])) {
        $legend = $legendModifica;
        $valueAzione = 'modifica';
        $nuovoTitoloClassifica = $classifica['Titolo'];
        $nuovoTipoEvento = $classifica['TipoEvento'];
        $nuovaDataInizio = $classifica['DataInizio'];
        $nuovaDataFine = $classifica['DataFine'];
    } elseif (isset($_POST['aggiungi'])) {
        $buttonElimina = '';
        $legend = $legendAggiungi;
        $selezioneDefault = ' selected';
        $nessunEvento = get_content_between_markers($content, 'nessunEvento');
        $valueAzione = 'aggiungi';
    } elseif (isset($_POST['conferma'])) {
        $nuovoTitoloClassifica = $validNuovoTitolo;
        $nuovoTipoEvento = $validNuovoTipoEvento;
        $nuovaDataInizio = $validNuovaDataInizio;
        $nuovaDataFine = $validNuovaDataFine;
        if ($_POST['azione'] == 'aggiungi') {
            $errore = '0';
            $legend = $legendAggiungi;
            $valueAzione = 'aggiungi';
            $errore = $connection->get_classifiche($validNuovoTitolo) ? '1' : '0';
            if ($errore == '0') {
                $connection->insert_classifica($validNuovoTitolo, $validNuovoTipoEvento, $validNuovaDataInizio, $validNuovaDataFine);
                $errore = $connection->get_classifiche($validNuovoTitolo) ? '0' : '1';
            } else {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{messaggio}' => "Esiste già una Classifica con questo Titolo"
                ]);
            }
            if ($errore == '0') {
                header("location: classifiche.php?aggiunto=1");
                exit;
            } else {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{messaggio}' => "Errore imprevisto"
                ]);
            }
        } elseif ($_POST['azione'] == 'modifica') {
            $errore = '0';
            $legend = $legendModifica;
            $valueAzione = 'modifica';
            if ($validNuovoTitolo != $classifica['Titolo']) {
                $errore = $connection->get_classifiche($validNuovoTitolo) ? '1' : '0';
            }
            if ($errore == '0') {
                $connection->update_classifica(
                    $validIdCLassifica, $validNuovoTitolo, $validNuovoTipoEvento, $validNuovaDataInizio, $validNuovaDataFine);
                $errore = $connection->get_classifiche($validNuovoTitolo) ? '0' : '1';
            } else {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{messaggio}' => "Esiste già una Classifica con questo Titolo"
                ]);
            }
            if ($errore == '0') {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{messaggio}' => 'Modifica effettuata con successo'
                ]);
                $dataInizio = $validNuovaDataInizio;
                $validDataInizio = $validNuovaDataInizio;
            } else {
                $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore imprevisto"]);
            }
        }
    } else {
        header("location: classifiche.php");
        exit;
    }
    
    // costruisco la lista degli eventi
    $eventi = $classifica ? $connection->get_eventi_classifica($classifica['TipoEvento'], $classifica['DataInizio'], $classifica['DataFine']) : null;
    if ($eventi != null) {
        $eventiHTML = get_content_between_markers($content, 'listaEventi');
        $elementoLista = get_content_between_markers($eventiHTML, 'elementoLista');
        $listaEventi = '';

        foreach ($eventi as $evento) {
            $listaEventi .= multi_replace($elementoLista, [
                '{idEvento}' => $evento['Id'],
                '{titoloEvento}' => $evento['Titolo'],
                '{dataVisualizzataEvento}' => date_format(date_create($evento['Data']), 'd/m/y')
            ]);
        }
        $eventiHTML = replace_content_between_markers($eventiHTML, ['elementoLista' => $listaEventi]);
    }

    // creo la classifica attuale
    $punteggiClassifica = $classifica ? $connection->get_punteggi_classifica($classifica['TipoEvento'], $classifica['DataInizio'], $classifica['DataFine']) : null;
    if ($punteggiClassifica != null) {
        $classificaHTML = get_content_between_markers($content, 'tabellaClassifica');
        $righe = '';
        $rigaHTML = get_content_between_markers($content, 'rigaClassifica');
        foreach ($punteggiClassifica as $riga) {
            $righe .= multi_replace($rigaHTML, [
                '{ranking}' => $riga['ranking'],
                '{freestyler}' => $riga['partecipante'],
                '{punti}' => $riga['punti']
            ]);
        }
        $content = replace_content_between_markers($content, [
            'tabellaClassifica' => $classificaHTML,
            'rigaClassifica' => $righe
        ]);
    } else {
        $content = replace_content_between_markers($content, [
            'tabellaClassifica' => ''
        ]);
    }

    $content = multi_replace($content, [
        '{legend}' => $legend,
        '{selezioneDefault}' => $selezioneDefault,
        '{idClassifica}' => $validIdCLassifica,
        '{nuovoTitoloClassifica}' => $nuovoTitoloClassifica,
        '{nuovoTipoEvento}' => $nuovoTipoEvento,
        '{nuovaDataInizio}' => $nuovaDataInizio,
        '{nuovaDataFine}' => $nuovaDataFine,
        '{valueAzione}' => $valueAzione
    ]);
    $content = replace_content_between_markers($content, [
        'listaTipoEvento' => $listaTipoEvento,
        'messaggiForm' => $messaggiForm,
        'buttonElimina' => $buttonElimina,
        'listaEventi' => $eventiHTML,
        'nessunEvento' => $nessunEvento
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
