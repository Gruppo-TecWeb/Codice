<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/gestione-classifiche.html");

$title = 'Gestione Classifiche &minus; Admin &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'pagina di amministrazione per la creazione e modifica delle classifiche';
$keywords = 'Fungo, amministrazione, classifiche';
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
    $validNuovoTitolo = isset($_POST['nuovoTitoloClassifica']) ? validate_input($_POST['nuovoTitoloClassifica']) : "";
    $validNuovoTipoEvento = isset($_POST['nuovoTipoEvento']) ? validate_input($_POST['nuovoTipoEvento']) : "";
    $validNuovaDataInizio = isset($_POST['nuovaDataInizio']) ? validate_input($_POST['nuovaDataInizio']) : "";
    $validNuovaDataFine = isset($_POST['nuovaDataFine']) ? validate_input($_POST['nuovaDataFine']) : "";
    $validIdEvento = isset($_POST['idEvento']) ? validate_input($_POST['idEvento']) : "";
    $validIdCLassifica = isset($_GET['idClassifica']) ? validate_input($_GET['idClassifica']) : "";
    if (((isset($_POST['nuovoTitoloClassifica']) && $_POST['nuovoTitoloClassifica'] != "") && $validNuovoTitolo == "") ||
        ((isset($_POST['nuovoTipoEvento']) && $_POST['nuovoTipoEvento'] != "") && $validNuovoTipoEvento == "") ||
        ((isset($_POST['nuovaDataInizio']) && $_POST['nuovaDataInizio'] != "") && $validNuovaDataInizio == "") ||
        ((isset($_POST['nuovaDataFine']) && $_POST['nuovaDataFine'] != "") && $validNuovaDataFine == "") ||
        ((isset($_POST['idEvento']) && $_POST['idEvento'] != "") && $validIdEvento == "") ||
        ((isset($_GET['idClassifica']) && $_GET['idClassifica'] != "") && $validIdCLassifica == "") ||
        (isset($_POST['punteggi']) && $validIdEvento == "") ||
        $validIdCLassifica != "" && $connection->get_classifica($validIdCLassifica) == null) {
                header("location: classifiche.php?errore=invalid");
                exit;
    }
    $errore = true;

    if (isset($_POST['punteggi'])) {
        header("location: gestione-punteggi.php?idEvento=$validIdEvento&provenienza=classifiche");
        exit;
    }

    if (isset($_GET['elimina']) || isset($_POST['elimina'])) {
        $connection->delete_classifica($validIdCLassifica);
        if ($connection->get_classifica($validIdCLassifica)) {
            header("location: classifiche.php?eliminato=false");
        } else {
            header("location: classifiche.php?eliminato=true");
        }
        exit;
    }

    $messaggiForm = '';
    $messaggiFormHTML = get_content_between_markers($content, 'messaggiForm');
    $messaggioForm = get_content_between_markers($messaggiFormHTML, 'messaggioForm');
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
    
    if (isset($_GET['modifica'])) {
        $legend = $legendModifica;
        $valueAzione = 'modifica';
        $nuovoTitoloClassifica = $classifica['Titolo'];
        $nuovoTipoEvento = $classifica['TipoEvento'];
        $nuovaDataInizio = $classifica['DataInizio'];
        $nuovaDataFine = $classifica['DataFine'];
    } elseif (isset($_GET['aggiungi'])) {
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
            $legend = $legendAggiungi;
            $valueAzione = 'aggiungi';
            if ($validNuovoTitolo == "") {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Il titolo è obbligatorio'
                ]);
                $errore = true;
            }
            if ($validNuovoTipoEvento == "") {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Il tipo di evento è obbligatorio'
                ]);
                $errore = true;
            }
            if ($validNuovaDataInizio == "") {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'La data inizio è obbligatoria'
                ]);
                $errore = true;
            }
            if ($validNuovaDataFine == "") {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'La data fine è obbligatoria'
                ]);
                $errore = true;
            }
            if (validate_date_time(date_format(date_create($validNuovaDataInizio), 'Y-m-d'), 'Y-m-d') == false) {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Data inizio non valida'
                ]);
                $errore = true;
            }
            if (validate_date_time(date_format(date_create($validNuovaDataFine), 'Y-m-d'), 'Y-m-d') == false) {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Data fine non valida'
                ]);
                $errore = true;
            }
            if ($connection->get_classifiche($validNuovoTitolo)) {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Esiste già una Classifica con questo titolo'
                ]);
                $errore = true;
            }
            if (!$errore) {
                $connection->insert_classifica($validNuovoTitolo, $validNuovoTipoEvento, $validNuovaDataInizio, $validNuovaDataFine);
                if ($connection->get_classifiche($validNuovoTitolo)) {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'successMessage',
                        '{messaggio}' => 'Classifica aggiunta con successo'
                    ]);
                    header("location: classifiche.php?aggiunto=true");
                } else {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'inputError',
                        '{messaggio}' => 'Errore nell\'inserimento della Classifica'
                    ]);
                }
            }
        } elseif ($_POST['azione'] == 'modifica') {
            $legend = $legendModifica;
            $valueAzione = 'modifica';
            if ($validNuovoTitolo != $classifica['Titolo'] && $connection->get_classifiche($validNuovoTitolo)) {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Esiste già una Classifica con questo titolo'
                ]);
                $errore = true;
            }
            if ($validNuovoTitolo == "") {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Il titolo è obbligatorio'
                ]);
                $errore = true;
            }
            if ($validNuovoTipoEvento == "") {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Il tipo di evento è obbligatorio'
                ]);
                $errore = true;
            }
            if ($validNuovaDataInizio == "") {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'La data inizio è obbligatoria'
                ]);
                $errore = true;
            }
            if ($validNuovaDataFine == "") {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'La data fine è obbligatoria'
                ]);
                $errore = true;
            }
            if (validate_date_time(date_format(date_create($validNuovaDataInizio), 'Y-m-d'), 'Y-m-d') == false) {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Data inizio non valida'
                ]);
                $errore = true;
            }
            if (validate_date_time(date_format(date_create($validNuovaDataFine), 'Y-m-d'), 'Y-m-d') == false) {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{tipoMessaggio}' => 'inputError',
                    '{messaggio}' => 'Data fine non valida'
                ]);
                $errore = true;
            }
            if (!$errore) {
                $connection->update_classifica(
                    $validIdCLassifica, $validNuovoTitolo, $validNuovoTipoEvento, $validNuovaDataInizio, $validNuovaDataFine);
                $updatedClassifica = $connection->get_classifica($validIdCLassifica);
                if ($updatedClassifica['Titolo'] == $validNuovoTitolo &&
                    $updatedClassifica['TipoEvento'] == $validNuovoTipoEvento &&
                    $updatedClassifica['DataInizio'] == $validNuovaDataInizio &&
                    $updatedClassifica['DataFine'] == $validNuovaDataFine) {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'successMessage',
                        '{messaggio}' => 'Classifica modificata con successo'
                    ]);
                    header("location: classifiche.php?modificato=true");
                } else {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{tipoMessaggio}' => 'inputError',
                        '{messaggio}' => 'Errore nell\'aggiornamento della Classifica'
                    ]);
                }                    
            }
        }
    } else {
        header("location: classifiche.php?errore=invalid");
        exit;
    }
    
    // costruisco la lista degli eventi ordinati per data
    $eventi = $classifica ? $connection->get_eventi_classifica($classifica['Id']) : null;
    if ($eventi != null) {
        // ordino gli eventi per data
        usort($eventi, function ($a, $b) {
            return strtotime($a['Data']) - strtotime($b['Data']);
        });

        $eventiHTML = get_content_between_markers($content, 'listaEventi');
        $elementoLista = get_content_between_markers($eventiHTML, 'elementoLista');
        $listaEventi = '';

        foreach ($eventi as $evento) {
            $listaEventi .= multi_replace($elementoLista, [
                '{idClassifica}' => $classifica['Id'],
                '{idEvento}' => $evento['Id'],
                '{titoloEvento}' => $evento['Titolo'],
                '{dataEvento}' => date_format(date_create($evento['Data']), 'Y-m-d'),
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

    $messaggiFormHTML = $messaggiForm == '' ? '' : replace_content_between_markers($messaggiFormHTML, ['messaggioForm' => $messaggiForm]);

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
        'messaggiForm' => $messaggiFormHTML,
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
