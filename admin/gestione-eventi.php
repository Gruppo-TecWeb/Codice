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
    $messaggiForm = '';
    $messaggioForm = get_content_between_markers($content, 'messaggioForm');
    $buttonElimina = get_content_between_markers($content, 'buttonElimina');
    $legend = '';
    $legendAggiungi = 'Aggiungi Evento';
    $legendModifica = 'Modifica Evento';
    $validNuovoTitolo = isset($_POST['nuovoTitolo']) ? validate_input($_POST['nuovoTitolo']) : "";
    $validNuovaData = isset($_POST['nuovaData']) ? validate_input($_POST['nuovaData']) : "";
    $validNuovaOra = isset($_POST['nuovaOra']) ? validate_input($_POST['nuovaOra']) : "";
    $validNuovoLuogo = isset($_POST['nuovoLuogo']) ? validate_input($_POST['nuovoLuogo']) : "";
    $validNuovaDescrizione = isset($_POST['nuovaDescrizione']) ? validate_input($_POST['nuovaDescrizione']) : "";
    $validNuovaLocandina = isset($_FILES['nuovaLocandina']) ? basename($_FILES["nuovaLocandina"]["name"]) : "";
    $validIdEvento = isset($_POST['idEvento']) ? validate_input($_POST['idEvento']) : "";
    $validTitolo = '';
    $validData = '';
    $validOra = '';
    $validLuogo = '';
    $validDescrizione = '';
    $validLocandina = '';
    $nuovoTitolo = '';
    $nuovaData = '';
    $nuovaOra = '';
    $nuovoLuogo = '';
    $nuovaDescrizione = '';
    $nuovaLocandina = '';
    $titolo = '';
    $data = '';
    $ora = '';
    $luogo = '';
    $descrizione = '';
    $locandina = '';
    $valueAzione = '';
    $evento = [];
    $percorsoLocandine = './../assets/media/locandine/';
    if (((isset($_POST['nuovoTitolo']) && $_POST['nuovoTitolo'] != "") && $validNuovoTitolo == "") ||
        ((isset($_POST['nuovaData']) && $_POST['nuovaData'] != "") && $validNuovaData == "") ||
        ((isset($_POST['nuovaOra']) && $_POST['nuovaOra'] != "") && $validNuovaOra == "") ||
        ((isset($_POST['nuovoLuogo']) && $_POST['nuovoLuogo'] != "") && $validNuovoLuogo == "") ||
        ((isset($_POST['nuovaDescrizione']) && $_POST['nuovaDescrizione'] != "") && $validNuovaDescrizione == "") ||
        ((isset($_POST['nuovaLocandina']) && $_POST['nuovaLocandina'] != "") && $validNuovaLocandina == "") ||
        ((isset($_POST['idEvento']) && $_POST['idEvento'] != "") && $validIdEvento == "")) {
        header("location: eventi.php?errore=invalid");
    }
    if($validIdEvento != '') {
        $evento = $connection->get_evento($validIdEvento);
        if ($evento) {
            $validTitolo = $evento['Titolo'];
            $validData = $evento['Data'];
            $validOra = $evento['Ora'];
            $validLuogo = $evento['Luogo'];
            $validDescrizione = $evento['Descrizione'];
            $validLocandina = $evento['Locandina'];
        }
    }
    $errore = '0';
    
    if (isset($_POST['punteggi'])) {
        header("location: gestione-punteggi.php?idEvento=$validIdEvento");
    } elseif (isset($_POST['elimina'])) {
        $connection->delete_evento($validIdEvento);
        $eliminato = $connection->get_evento($validIdEvento) ? 0 : 1;
        if ($eliminato) {
            unlink($percorsoLocandine . $validLocandina);
        }
        header("location: eventi.php?eliminato=$eliminato");
    } elseif (isset($_POST['modifica'])) {
        $legend = $legendModifica;
        $nuovoTitolo = $validTitolo;
        $nuovaData = $validData;
        $nuovaOra = $validOra;
        $nuovoLuogo = $validLuogo;
        $nuovaDescrizione = $validDescrizione;
        $nuovaLocandina = $validLocandina;
        $titolo = $validTitolo;
        $data = $validData;
        $ora = $validOra;
        $luogo = $validLuogo;
        $descrizione = $validDescrizione;
        $locandina = $validLocandina;
        $valueAzione = 'modifica';
    } elseif (isset($_POST['aggiungi'])) {
        $buttonElimina = '';
        $legend = $legendAggiungi;
        $valueAzione = 'aggiungi';
    } elseif (isset($_POST['conferma']) || isset($_POST['eliminaLocandina'])) {
        $nuovoTitolo = $validNuovoTitolo;
        $nuovaData = $validNuovaData;
        $nuovaOra = $validNuovaOra;
        $nuovoLuogo = $validNuovoLuogo;
        $nuovaDescrizione = $validNuovaDescrizione;
        $nuovaLocandina = $validIdEvento . '_' . $validNuovaLocandina;
        $titolo = $validTitolo;
        $data = $validData;
        $ora = $validOra;
        $luogo = $validLuogo;
        $descrizione = $validDescrizione;
        $locandina = $validLocandina;
        if ($_POST['azione'] == 'aggiungi') {
            $errore = '0';
            $legend = $legendAggiungi;
            $valueAzione = 'aggiungi';
            $countEventi = count($connection->get_eventi());
            $validNuovoIdEvento = $connection->insert_evento(
                $validNuovoTitolo, $validNuovaDescrizione, $validNuovaData, $validNuovaOra, $validNuovoLuogo, '');
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
                        $connection->update_evento(
                            $validNuovoIdEvento, $validNuovoTitolo, $validNuovaDescrizione, $validNuovaData, $validNuovaOra, $validNuovoLuogo, $validNuovoIdEvento . '_' . $validNuovaLocandina);
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
                $validIdEvento, $validNuovoTitolo, $validNuovaDescrizione, $validNuovaData, $validNuovaOra, $validNuovoLuogo, $validNuovaLocandina == '' ? $validLocandina : $validIdEvento . '_' . $validNuovaLocandina);
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
                            $validIdEvento, $validNuovoTitolo, $validNuovaDescrizione, $validNuovaData, $validNuovaOra, $validNuovoLuogo, $validLocandina);
                    } else {
                        unlink($percorsoLocandine . $validLocandina);
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
                $validIdEvento, $validNuovoTitolo, $validNuovaDescrizione, $validNuovaData, $validNuovaOra, $validNuovoLuogo, '');
            unlink($percorsoLocandine . $validLocandina);
        }
    } else {
        header("location: eventi.php");
    }

    $content = multi_replace($content, [
        '{legend}' => $legend,
        '{nuovoTitolo}' => $nuovoTitolo,
        '{nuovaData}' => $nuovaData,
        '{nuovaOra}' => $nuovaOra,
        '{nuovoLuogo}' => $nuovoLuogo,
        '{nuovaDescrizione}' => $nuovaDescrizione,
        '{nuovaLocandina}' => $nuovaLocandina,
        '{titolo}' => $titolo,
        '{data}' => $data,
        '{ora}' => $ora,
        '{luogo}' => $luogo,
        '{descrizione}' => $descrizione,
        '{locandina}' => '../assets/media/locandine/'. $locandina,
        '{valueAzione}' => $valueAzione,
        '{idEvento}' => $validIdEvento
    ]);
    $content = replace_content_between_markers($content, [
        'messaggiForm' => $messaggiForm,
        'buttonElimina' => $buttonElimina,
        'imgLocandina' => $locandina == '' ? '' : get_content_between_markers($content, 'imgLocandina'),
        'eliminaLocandina' => isset($_POST['aggiungi']) || $validLocandina == '' ? '' : get_content_between_markers($content, 'eliminaLocandina')
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
