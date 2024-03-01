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

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    $messaggiForm = '';
    $messaggioForm = get_content_between_markers($content, 'messaggioForm');
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
    $percorsoLocandine = '../assets/media/locandine/';
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
        $evento = $connection->getEvento($validIdEvento);
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
    
    if (isset($_POST['elimina'])) {
        $connection->delete_evento($validIdEvento);
        $eliminato = $connection->getEvento($validIdEvento) ? 0 : 1;
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
        $legend = $legendAggiungi;
        $valueAzione = 'aggiungi';
    } elseif (isset($_POST['conferma'])) {
        $nuovoTitolo = $validNuovoTitolo;
        $nuovaData = $validNuovaData;
        $nuovaOra = $validNuovaOra;
        $nuovoLuogo = $validNuovoLuogo;
        $nuovaDescrizione = $validNuovaDescrizione;
        $nuovaLocandina = $validNuovaLocandina;
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
            $countEventi = count($connection->getListaEventi());
            $connection->insert_evento(
                $validNuovoTitolo, $validNuovaDescrizione, $validNuovaData, $validNuovaOra, $validNuovoLuogo, $validNuovaLocandina);
            $errore = count($connection->getListaEventi()) == $countEventi ? '1' : '0';
            if ($errore == '0') {
                if ($validNuovaLocandina != "" && getimagesize($_FILES["nuovaLocandina"]["tmp_name"]) !== false) {
                    $targetImage = $percorsoLocandine . $validNuovaLocandina;
                    if (file_exists($targetImage))
                        $messaggiForm .= str_replace('{messaggio}', 'Esiste già un file con questo nome', $messaggioForm);
                    elseif ($_FILES["nuovaLocandina"]["size"] > 500000)
                        $messaggiForm .= str_replace('{messaggio}', 'Il file è troppo grande', $messaggioForm);
                    elseif (move_uploaded_file($_FILES["nuovaLocandina"]["tmp_name"], $percorsoLocandine . $validNuovaLocandina))
                        $messaggiForm .= str_replace('{messaggio}', 'Il file è stato caricato', $messaggioForm);
                    else
                        $messaggiForm .= str_replace('{messaggio}', 'Errore nel caricamento del file', $messaggioForm);
                }
                header("location: eventi.php?aggiunto=1");
            }
        } elseif ($_POST['azione'] == 'modifica') {
            $errore = '0';
            $legend = $legendModifica;
            $valueAzione = 'modifica';
            $connection->update_evento(
                $validIdEvento, $validNuovoTitolo, $validNuovaDescrizione, $validNuovaData, $validNuovaOra, $validNuovoLuogo, $validNuovaLocandina);
            if ($errore == '0') {
                if ($validNuovaLocandina != "" && getimagesize($_FILES["nuovaLocandina"]["tmp_name"]) !== false) {
                    $targetImage = $percorsoLocandine . $validNuovaLocandina;
                    if (file_exists($targetImage))
                        {$messaggiForm .= str_replace('{messaggio}', 'Esiste già un file con questo nome', $messaggioForm);}
                    elseif ($_FILES["nuovaLocandina"]["size"] > 500000)
                        {$messaggiForm .= str_replace('{messaggio}', 'Il file è troppo grande', $messaggioForm);}
                    elseif (move_uploaded_file($_FILES["nuovaLocandina"]["tmp_name"], $percorsoLocandine . $validNuovaLocandina))
                        {$messaggiForm .= str_replace('{messaggio}', 'Il file è stato caricato', $messaggioForm);echo $percorsoLocandine . $validNuovaLocandina;}
                    else {
                        $connection->update_evento(
                            $validIdEvento, $validNuovoTitolo, $validNuovaDescrizione, $validNuovaData, $validNuovaOra, $validNuovoLuogo, $validLocandina = "" ? null : $validNuovaLocandina);
                        $messaggiForm .= str_replace('{messaggio}', 'Errore nel caricamento del file', $messaggioForm);echo $percorsoLocandine . $validNuovaLocandina;}
                }
                header("location: eventi.php?modificato=1");
            }
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
        'imgLocandina' => $locandina == '' ? '' : get_content_between_markers($content, 'imgLocandina')
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
