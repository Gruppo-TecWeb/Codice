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
$percorso = '';
$percorsoAdmin = 'admin/';
$menu = get_menu($pageId, $percorso);
$breadcrumbs = get_breadcrumbs($pageId, $percorso);
$onload = 'hideSubmitButtons()';
$logout = '';

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    $classifiche = '';
    $sceltaEffettuata = false;
    $titoloEvento = '';
    $dataInizioEvento = null;

    if (!isset($_GET["reset"]) && isset($_GET["classifica"]) && $_GET["classifica"] != "") {
        $classifica = explode('{.}', validate_input($_GET["classifica"]));
        $titoloEvento = $classifica[0];
        $dataInizioEvento = date_create($classifica[1]);
        $sceltaEffettuata = true;
    } else {
        $titoloEvento = $connection->get_tipo_evento(null)['Titolo'];
        $dataInizioEvento = date_create($connection->get_data_inizio_corrente($titoloEvento));
    }

    // creo la lista delle classifiche per la scelta dall'archivio
    $resultClassifiche = $connection->get_classifiche();
    foreach ($resultClassifiche as $resultClassifica) {
        $dataInizio = date_create($resultClassifica['DataInizio']);
        $dataFine = $resultClassifica['DataFine'] ? date_create($resultClassifica['DataFine']) : $dataInizio;
        $evento = $resultClassifica['TipoEvento'];
        $dataVisualizzata = '';
        $selected = '';

        // <time datetime="2024-04-04">04 Aprile 2024</time>
        if ($dataInizio == $dataFine) {
            $dataVisualizzata = date_format($dataInizio, 'd/m/y');
        } elseif (date_format($dataInizio, 'Y') != date_format($dataFine, 'Y')) {
            $dataVisualizzata = date_format($dataInizio, 'Y') . ' - ' . date_format($dataFine, 'Y');
        } elseif (date_format($dataInizio, 'm') != date_format($dataFine, 'm')) {
            $dataVisualizzata = date_format($dataInizio, 'm/y') . ' - ' . date_format($dataFine, 'm/y');
        } else {
            $dataVisualizzata = date_format($dataInizio, 'd/m/y') . ' - ' . date_format($dataFine, 'd/m/y');
        }

        if ($titoloEvento == $evento && date_format($dataInizioEvento, 'Y-m-d') == date_format($dataInizio, 'Y-m-d')) {
            $titoloEvento = $evento;
            $dataInizioEvento = $dataInizio;
            $selected = ' selected';
        }
        $option = get_content_between_markers($content, 'listaClassifiche');
        $classifiche .= multi_replace($option, [
            '{tipoEvento}' => $evento,
            '{dataInizio}' => date_format($dataInizio, 'Y-m-d'),
            '{selezioneClassifica}' => $selected,
            '{opzioneClassifica}' => $evento . ' ' . $dataVisualizzata
        ]);
    }

    // creo le classifiche per la visualizzazione
    $descrizioneEvento = $connection->get_tipo_evento($titoloEvento)['Descrizione'];
    $classifica = $connection->get_classifica($titoloEvento, date_format($dataInizioEvento, 'Y-m-d'));
    if ($classifica != null) {
        $classificaHTML = get_content_between_markers($content, 'tabellaClassifica');
        $tabella = multi_replace($classificaHTML, [
            '{tipoEvento}' => $titoloEvento,
            '{desTipoEvento}' => $descrizioneEvento
        ]);
        $righe = '';
        $rigaHTML = get_content_between_markers($content, 'rigaClassifica');
        foreach ($classifica as $riga) {
            $righe .= multi_replace($rigaHTML, [
                '{ranking}' => $riga['ranking'],
                '{freestyler}' => $riga['partecipante'],
                '{punti}' => $riga['punti']
            ]);
        }
        $content = replace_content_between_markers($content, [
            'listaClassifiche' => $classifiche,
            'tabellaClassifica' => $tabella,
            'rigaClassifica' => $righe
        ]);
    } else {
        $content .= '<p>Non sono presenti classifiche.</p>';
    }

    $connection->closeDBConnection();
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
    '{onload}' => $onload,
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin
]);
