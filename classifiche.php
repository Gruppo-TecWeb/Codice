<?php

namespace Utilities;
require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");
use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/pagina-template.html");
$classificheHTML = file_get_contents("template/classifiche-template.html");
$classificaHTML = file_get_contents("template/classifica-template.html");
$rigaHTML = file_get_contents("template/tabella-riga-template.html");

$title = 'Classifiche &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Classifiche attuali sulla base dei punteggi ottenuti durante le battle di freestyle rap degli eventi Fungo e Micelio.';
$keywords = 'classifiche, fungo, micelio, freestyle, rap, freestyle rap, battle';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$content = '';
$onload = 'hideSubmitButtons()';
$classifiche = '';

$connection = DBAccess::getInstance();
$connectionOk = $connection -> openDBConnection();
if ($connectionOk) {
    $sceltaEffettuata = false;
    $titoloEvento = '';
    $dataInizioEvento = null;

    if (!isset($_GET["reset"]) && isset($_GET["classifica"]) && $_GET["classifica"] != "") {
        $classifica = explode('{.}', validate_input($_GET["classifica"]));
        $titoloEvento = $classifica[0];
        $dataInizioEvento = date_create($classifica[1]);
        $sceltaEffettuata = true;
    }
    else {
        $titoloEvento = $connection -> get_tipo_evento(null)['Titolo'];
        $dataInizioEvento = date_create($connection -> get_data_inizio_corrente($titoloEvento));
    }

    // creo la lista delle classifiche per la scelta dall'archivio
    $resultClassifiche = $connection -> get_classifiche();
    foreach ($resultClassifiche as $resultClassifica) {
        $dataInizio = date_create($resultClassifica['DataInizio']);
        $dataFine = $resultClassifica['DataFine'] ? date_create($resultClassifica['DataFine']) : $dataInizio;
        $evento = $resultClassifica['TipoEvento'];
        $dataVisualizzata = '';
        $selected = '';

        // formatto la data in base all'intervallo di tempo, da rivedere...
        if ($dataInizio == $dataFine) {
            $dataVisualizzata = date_format($dataInizio, 'd/m/y');
        }
        elseif (date_format($dataInizio, 'Y') != date_format($dataFine, 'Y')) {
            $dataVisualizzata = date_format($dataInizio, 'Y') . ' - ' . date_format($dataFine, 'Y');
        }
        elseif (date_format($dataInizio, 'm') != date_format($dataFine, 'm')) {
            $dataVisualizzata = date_format($dataInizio, 'm/y') . ' - ' . date_format($dataFine, 'm/y');
        }
        else {
            $dataVisualizzata = date_format($dataInizio, 'd/m/y') . ' - ' . date_format($dataFine, 'd/m/y');
        }

        if ($titoloEvento == $evento && date_format($dataInizioEvento, 'Y-m-d') == date_format($dataInizio, 'Y-m-d')) {
            $titoloEvento = $evento;
            $dataInizioEvento = $dataInizio;
            $selected = ' selected';
        }
        $classifiche .= '<option value="' . $evento
            . '{.}' . date_format($dataInizio, 'Y-m-d') . '"' . $selected . '>'
            . $evento . ' ' . $dataVisualizzata . '</option>
                    ';
    }

    // creo le classifiche per la visualizzazione
    $descrizioneEvento = $connection -> get_tipo_evento($titoloEvento)['Descrizione'];
    $classifica = $connection -> get_classifica($titoloEvento, date_format($dataInizioEvento, 'Y-m-d'));
    if ($classifica != null) {
        $tabella = str_replace('{tipoEvento}', $titoloEvento, $classificaHTML);
        $tabella = str_replace('{desTipoEvento}', $descrizioneEvento, $tabella);
        $righe = '';
        foreach ($classifica as $riga) {
            $rigaHTML_temp = $rigaHTML;
            $rigaHTML_temp = str_replace('{ranking}', $riga['ranking'], $rigaHTML_temp);
            $rigaHTML_temp = str_replace('{freestyler}', $riga['partecipante'], $rigaHTML_temp);
            $rigaHTML_temp = str_replace('{punti}', $riga['punti'], $rigaHTML_temp);
            $righe .= $rigaHTML_temp;
        }
        $tabella = str_replace('{tableContent}', $righe, $tabella);
        $content .= $tabella;
    }
    else {
        $content .= '<p>Non sono presenti classifiche.</p>';
    }

    $connection -> closeDBConnection();
}
else {
    header("location: errore500.php");
}

echo multi_replace($paginaHTML,[
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{menu}' => $menu,
    '{breadcrumbs}' => $breadcrumbs,
    '{content}' => trim($classificheHTML),
    '{onload}' => $onload,
    '{classifica}' => trim($classifiche),
    '{classifiche}' => $content
]);
