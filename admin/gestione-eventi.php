<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/gestione-eventi.html");

$title = 'Gestione Eventi &minus; Fungo';
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
    if (isset($_POST['indietro'])) {
        header("location: eventi.php");
    }

    $validNuovoTipoEvento = isset($_POST['nuovoTipoEvento']) ? validate_input($_POST['nuovoTipoEvento']) : "";
    $validNuovoTitolo = isset($_POST['nuovoTitolo']) ? validate_input($_POST['nuovoTitolo']) : "";
    $validNuovaData = isset($_POST['nuovaData']) ? validate_input($_POST['nuovaData']) : "";
    $validNuovaOra = isset($_POST['nuovaOra']) ? validate_input($_POST['nuovaOra']) : "";
    $validNuovoLuogo = isset($_POST['nuovoLuogo']) ? validate_input($_POST['nuovoLuogo']) : "";
    $validNuovaDescrizione = isset($_POST['nuovaDescrizione']) ? validate_input($_POST['nuovaDescrizione']) : "";
    $validNuovaLocandina = isset($_FILES['nuovaLocandina']) ? basename($_FILES["nuovaLocandina"]["name"]) : "";
    $validIdEvento = isset($_POST['idEvento']) ? validate_input($_POST['idEvento']) : "";
    if (((isset($_POST['nuovoTipoEvento']) && $_POST['nuovoTipoEvento'] != "") && $validNuovoTipoEvento == "") ||
        ((isset($_POST['nuovoTitolo']) && $_POST['nuovoTitolo'] != "") && $validNuovoTitolo == "") ||
        ((isset($_POST['nuovaData']) && $_POST['nuovaData'] != "") && $validNuovaData == "") ||
        ((isset($_POST['nuovaOra']) && $_POST['nuovaOra'] != "") && $validNuovaOra == "") ||
        ((isset($_POST['nuovoLuogo']) && $_POST['nuovoLuogo'] != "") && $validNuovoLuogo == "") ||
        ((isset($_POST['nuovaDescrizione']) && $_POST['nuovaDescrizione'] != "") && $validNuovaDescrizione == "") ||
        ((isset($_POST['nuovaLocandina']) && $_POST['nuovaLocandina'] != "") && $validNuovaLocandina == "") ||
        ((isset($_POST['idEvento']) && $_POST['idEvento'] != "") && $validIdEvento == "") ||
        (isset($_POST['punteggi']) && $validIdEvento == "") ||
        $validIdEvento != "" && $connection->get_evento($validIdEvento) == null) {
        header("location: eventi.php?errore=invalid");
    }
    $errore = '0';
    
    if (isset($_POST['punteggi'])) {
        header("location: gestione-punteggi.php?idEvento=$validIdEvento");
    }

    $nuovoTipoEvento = '';
    $nuovoTitolo = '';
    $nuovaData = '';
    $nuovaOra = '';
    $nuovoLuogo = '';
    $nuovaDescrizione = '';
    $locandina = '';
    $percorsoLocandine = './../assets/media/locandine/';
    $evento = null;

    if($validIdEvento && $validIdEvento != '') {
        $evento = $connection->get_evento($validIdEvento);
        if ($evento) {
            $nuovoTipoEvento = $evento['TipoEvento'] ?? '';
            $nuovoTitolo = $evento['Titolo'];
            $nuovaData = $evento['Data'];
            $nuovaOra = $evento['Ora'];
            $nuovoLuogo = $evento['Luogo'];
            $nuovaDescrizione = $evento['Descrizione'];
            $locandina = $evento['Locandina'];
        }
    }

    if (isset($_POST['elimina'])) {
        $connection->delete_evento($validIdEvento);
        $eliminato = $connection->get_evento($validIdEvento) ? 0 : 1;
        if ($eliminato) {
            unlink($percorsoLocandine . $locandina);
        }
        header("location: eventi.php?eliminato=$eliminato");
    }

    $messaggiForm = '';
    $messaggioForm = get_content_between_markers($content, 'messaggioForm');
    $listaTipoEvento = '';
    $legend = '';
    $legendAggiungi = 'Aggiungi Evento';
    $legendModifica = 'Modifica Evento';
    $valueAzione = '';
    $selezioneDefault = '';
    $nessunaSelezione = '';
    
    // costruisco la lista di option per la selezione del tipo evento
    $tipiEvento = $connection->get_tipi_evento();
    $optionTipoEvento = get_content_between_markers($content, 'listaTipoEvento');
    foreach ($tipiEvento as $tipoEvento) {
        $selected = '';
        if ($validNuovoTipoEvento != "" || isset($_POST['conferma']) || isset($_POST['eliminaLocandina'])) {
            $selected = $validNuovoTipoEvento == $tipoEvento['Titolo'] ? ' selected' : '';
        } elseif ($evento && $evento['TipoEvento'] && $evento['TipoEvento'] == $tipoEvento['Titolo']) {
            $selected = ' selected';
        }
        $listaTipoEvento .= multi_replace($optionTipoEvento, [
            '{tipoEvento}' => $tipoEvento['Titolo'],
            '{selezioneTipoEvento}' => $selected
        ]);
    }
    if ($evento && !$evento['TipoEvento']) {
        $nessunaSelezione = ' selected';
    }

    if (isset($_POST['modifica'])) {
        if (!$validIdEvento || $validIdEvento == "") {
            header("location: eventi.php?errore=invalid");
        }
        $legend = $legendModifica;
        $valueAzione = 'modifica';
    } elseif (isset($_POST['aggiungi'])) {
        $legend = $legendAggiungi;
        $valueAzione = 'aggiungi';
        $selezioneDefault = ' selected';
    } elseif (isset($_POST['conferma']) || isset($_POST['eliminaLocandina'])) {
        $nuovoTipoEvento = $validNuovoTipoEvento;
        $nuovoTitolo = $validNuovoTitolo;
        $nuovaData = $validNuovaData;
        $nuovaOra = $validNuovaOra;
        $nuovoLuogo = $validNuovoLuogo;
        $nuovaDescrizione = $validNuovaDescrizione;
        if ($_POST['azione'] == 'aggiungi') {
            $errore = '0';
            $legend = $legendAggiungi;
            $valueAzione = 'aggiungi';
            $countEventi = count($connection->get_eventi());
            $validNuovoIdEvento = $connection->insert_evento(
                $validNuovoTipoEvento, $validNuovoTitolo, $validNuovaDescrizione, $validNuovaData, $validNuovaOra, $validNuovoLuogo, '');
            $errore = count($connection->get_eventi()) == $countEventi ? '1' : '0';
            if ($errore == '0') {
                if (!isset($_POST['eliminaLocandina']) && $validNuovaLocandina != "" && getimagesize($_FILES["nuovaLocandina"]["tmp_name"]) !== false) {
                    $errori = carica_file($_FILES["nuovaLocandina"], $percorsoLocandine, $validNuovoIdEvento . '_' . $validNuovaLocandina);
                    if (count($errori) > 0) {
                        foreach ($errori as $errore) {
                            $messaggiForm .= multi_replace($messaggioForm, [
                                '{messaggio}' => $errore
                            ]);
                        }
                        $errore = '1';
                    } else {
                        $locandina = $validNuovoIdEvento . '_' . $validNuovaLocandina;
                        $connection->update_evento(
                            $validNuovoIdEvento, $validNuovoTipoEvento, $validNuovoTitolo, $validNuovaDescrizione, $validNuovaData, $validNuovaOra, $validNuovoLuogo, $locandina);
                    }
                }
                if ($errore == '0') {
                    header("location: eventi.php?aggiunto=1");
                }
            }
            else {
                $messaggiForm .= multi_replace($messaggioForm, [
                    '{messaggio}' => "Errore nell'aggiunta dell'evento"
                ]);
            }
        } elseif ($_POST['azione'] == 'modifica') {
            $errore = '0';
            $legend = $legendModifica;
            $valueAzione = 'modifica';
            $connection->update_evento(
                $validIdEvento, $validNuovoTipoEvento, $validNuovoTitolo, $validNuovaDescrizione, $validNuovaData, $validNuovaOra, $validNuovoLuogo, $validIdEvento . '_' . $validNuovaLocandina);
            if ($errore == '0') {
                if (!isset($_POST['eliminaLocandina']) && $validNuovaLocandina != "" && getimagesize($_FILES["nuovaLocandina"]["tmp_name"]) !== false) {
                    $errori = carica_file($_FILES["nuovaLocandina"], $percorsoLocandine, $validIdEvento . '_' . $validNuovaLocandina);
                    if (count($errori) > 0) {
                        foreach ($errori as $errore) {
                            $messaggiForm .= multi_replace($messaggioForm, [
                                '{messaggio}' => $errore
                            ]);
                        }
                        $errore = '1';
                        $connection->update_evento(
                            $validIdEvento, $validNuovoTipoEvento, $validNuovoTitolo, $validNuovaDescrizione, $validNuovaData, $validNuovaOra, $validNuovoLuogo, $locandina);
                    } else {
                        if ($locandina != '') {
                            unlink($percorsoLocandine . $locandina);
                        }
                        $locandina = $validIdEvento . '_' . $validNuovaLocandina;
                    }
                }
                if ($errore == '0') {
                    $messaggiForm .= multi_replace($messaggioForm, [
                        '{messaggio}' => 'Modifica effettuata con successo'
                    ]);
                }
            }
        }
        if (isset($_POST['eliminaLocandina'])) {
            $connection->update_evento(
                $validIdEvento, $validNuovoTipoEvento, $validNuovoTitolo, $validNuovaDescrizione, $validNuovaData, $validNuovaOra, $validNuovoLuogo, '');
            unlink($percorsoLocandine . $locandina);
            $locandina = '';
        }
    } else {
        header("location: eventi.php");
    }

    $content = multi_replace($content, [
        '{legend}' => $legend,
        '{selezioneDefault}' => $selezioneDefault,
        '{nessunaSelezione}' => $nessunaSelezione,
        '{nuovoTipoEvento}' => $nuovoTipoEvento,
        '{nuovoTitolo}' => $nuovoTitolo,
        '{nuovaData}' => $nuovaData,
        '{nuovaOra}' => $nuovaOra,
        '{nuovoLuogo}' => $nuovoLuogo,
        '{nuovaDescrizione}' => $nuovaDescrizione,
        '{nuovaLocandina}' => $locandina,
        '{locandina}' => '../assets/media/locandine/'. $locandina,
        '{valueAzione}' => $valueAzione,
        '{idEvento}' => $validIdEvento
    ]);
    $content = replace_content_between_markers($content, [
        'listaTipoEvento' => $listaTipoEvento,
        'messaggiForm' => $messaggiForm,
        'imgLocandina' => $locandina == '' ? '' : get_content_between_markers($content, 'imgLocandina'),
        'eliminaLocandina' => isset($_POST['aggiungi']) || $locandina == '' ? '' : get_content_between_markers($content, 'eliminaLocandina')
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
