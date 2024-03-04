<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/gestione-punteggi.html");

$title = 'Gestione Punteggi &minus; Fungo';
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
    $righeTabella = '';
    $validTipoEvento = isset($_POST['tipoEvento']) ? validate_input($_POST['tipoEvento'])
        : (isset($_GET['tipoEvento']) ? validate_input($_GET['tipoEvento']) : '');
    $validDataInizio = isset($_POST['dataInizio']) ? validate_input($_POST['dataInizio'])
        : (isset($_GET['dataInizio']) ? validate_input($_GET['dataInizio']) : '');
    $validIdEvento = isset($_POST['idEvento']) ? validate_input($_POST['idEvento']) : '';
    $eventoSelezionato = isset($_POST['idEvento']);
    $validRappersPoints = [];
    if (isset($_POST['username'])) {
        $count = 0;
        foreach ($_POST['username'] as $rapper) {
            $rapperUsername = validate_input($rapper);
            $rapperPoints = validate_input($_POST['punti'][$count]);
            if ($rapperUsername != "" && $rapperPoints != "") {
                $validRappersPoints[$rapperUsername] = $rapperPoints;
            }
            $count++;
        }
    }
    if (((isset($_POST['tipoEvento']) && $_POST['tipoEvento'] != "") && $validTipoEvento == "") ||
        ((isset($_POST['dataInizio']) && $_POST['dataInizio'] != "") && $validDataInizio == "") ||
        ((isset($_POST['idEvento']) && $_POST['idEvento'] != "") && $validIdEvento == "")) {
        header("location: classifiche.php?errore=invalid");
    }

    // quello che c'è da fare
    if (isset($_POST['conferma'])) {
        if ($eventoSelezionato) {
            $connection->update_punteggi_evento($validTipoEvento, $validDataInizio, $validIdEvento, $validRappersPoints);
            $messaggiForm .= multi_replace($messaggioForm, [
                '{messaggio}' => 'Punteggi aggiornati con successo'
            ]);
        } else {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{messaggio}' => 'Errore imprevisto, nessun evento selezionato'
            ]);
        }
    }

    // creo le option di ciascun evento della classifica selezionata
    if ((isset($_GET['tipoEvento']) && isset($_GET['dataInizio'])) || (isset($_POST['tipoEvento']) && isset($_POST['dataInizio']))) {
        $eventi = $connection->get_eventi_classifica($validTipoEvento, $validDataInizio);
        $optionEvento = get_content_between_markers($content, 'listaEvento');
        $options = '';
        foreach ($eventi as $evento) {
            $options .= multi_replace($optionEvento, [
                '{idEvento}' => $evento['Id'],
                '{titoloEvento}' => $evento['Titolo'],
                '{dataEvento}' => $evento['Data'],
                '{dataVisualizzataEvento}' => date_format(date_create($evento['Data']), 'd/m/y'),
                '{selezioneEvento}' => ($eventoSelezionato && $validIdEvento == $evento['Id']) ? ' selected' : ''
            ]);
        }
        $content = replace_content_between_markers($content, [
            'listaEvento' => $options
        ]);
        $content = multi_replace($content, [
            '{selezioneDefault}' => $eventoSelezionato ? '' : ' selected'
        ]);
    } else {
        $content = replace_content_between_markers($content, [
            'listaEvento' => ''
        ]);
        $content = multi_replace($content, [
            '{selezioneDefault}' => ' selected',
        ]);
        $messaggiForm .= multi_replace($messaggioForm, [
            '{messaggio}' => 'Errore imprevisto, nessun evento disponibile'
        ]);
    }

    // creo l'elenco dei rappers
    $rappers = $connection->get_utenti_base();
    $rigaTabella = get_content_between_markers($content, 'rigaTabella');
    $punteggio = $eventoSelezionato ? $connection->get_punteggi_evento($validTipoEvento, $validDataInizio, $validIdEvento) : [];
    foreach ($rappers as $rapper) {
        $righeTabella .= multi_replace($rigaTabella, [
            '{rapper}' => $rapper['Username'],
            '{idRapper}' => multi_replace($rapper['Username'], [' ' => '_']),
            '{valuePunti}' => isset($punteggio[$rapper['Username']]) ? " value=\"" . $punteggio[$rapper['Username']] . "\"" : ''
        ]);
    }

    $content = replace_content_between_markers($content, [
        'messaggiForm' => $messaggiForm,
        'rigaTabella' => $righeTabella
    ]);
    $content = multi_replace($content, [
        '{tipoEvento}' => $validTipoEvento,
        '{dataInizio}' => $validDataInizio
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
