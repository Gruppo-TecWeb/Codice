<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/classifiche.html");

$title = 'Classifiche &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Classifiche attuali sulla base dei punteggi ottenuti durante le battle di freestyle rap degli eventi Fungo e Micelio.';
$keywords = 'classifiche, fungo, micelio, freestyle, rap, freestyle rap, battle';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$logout = '';

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

if ($connectionOk) {
    $classifiche = '';
    $validIdClassifica = isset($_GET['classifica']) ? validate_input($_GET['classifica']) : "";
    $classifica = $connection->get_classifica($validIdClassifica);
    if (((isset($_GET['classifica']) && $_GET['classifica'] != "") && $validIdClassifica == "") ||
        ((isset($_GET['classifica']) && $_GET['classifica'] != "") && $classifica == null)) {
        header("location: classifiche.php?errore=invalid");
    }

    if (isset($_GET['errore'])) {
        header("location: errore404.php");
    }

    if (isset($_GET['reset'])) {
        header("location: classifiche.php");
    }

    if (!isset($_GET['classifica'])) {
        $classifica = $connection->get_classifica_corrente();
    }

    // creo la lista delle classifiche per la scelta dall'archivio
    $resultClassifiche = $connection->get_classifiche();
    foreach ($resultClassifiche as $resultClassifica) {
        $idClassifica = $resultClassifica['Id'];
        $titoloClassifica = $resultClassifica['Titolo'];
        $tipoEvento = $resultClassifica['TipoEvento'];
        $selected = $idClassifica == $classifica['Id'] ? 'selected' : '';
        
        $option = get_content_between_markers($content, 'listaClassifiche');
        $classifiche .= multi_replace($option, [
            '{idClassifica}' => $idClassifica,
            '{tipoEvento}' => $tipoEvento,
            '{selezioneClassifica}' => $selected,
            '{opzioneClassifica}' => $titoloClassifica
        ]);
    }

    // creo le classifiche per la visualizzazione
    $tipoEvento = $classifica['TipoEvento'];
    $descrizioneEvento = $connection->get_tipo_evento($tipoEvento)['Descrizione'];
    $punteggiClassifica = $connection->get_punteggi_classifica($tipoEvento, $classifica['DataInizio'], $classifica['DataFine']);
    $titoloClassifica = $classifica['Titolo'];
    if ($punteggiClassifica != null) {
        $classificaHTML = get_content_between_markers($content, 'tabellaClassifica');
        $tabella = multi_replace($classificaHTML, [
            '{tipoEvento}' => $tipoEvento,
            '{desTipoEvento}' => $descrizioneEvento,
            '{titoloClassifica}' => $titoloClassifica
        ]);
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
            'listaClassificheDefault' => '',
            'listaClassifiche' => $classifiche,
            'tabellaClassifica' => $tabella,
            'rigaClassifica' => $righe,
            'nessunaClassifica' => ''
        ]);
    } else {
        $content = replace_content_between_markers($content, [
            'listaClassificheDefault' => get_content_between_markers($content, 'listaClassificheDefault'),
            'listaClassifiche' => '',
            'tabellaClassifica' => '',
            'nessunaClassifica' => get_content_between_markers($content, 'nessunaClassifica')
        ]);
    }

    $connection->close_DB_connection();
} else {
    header("location: errore500.php");
}

if (isset($_SESSION["login"])) {
    $logout = get_content_between_markers($paginaHTML, 'logout');
}

echo multi_replace(replace_content_between_markers($paginaHTML, [
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu,
    'logout' => $logout
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => $content,
    '{onload}' => $onload
]);
