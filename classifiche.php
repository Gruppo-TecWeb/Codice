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
$onload = '';
$classifiche = '';
$dataInizioScelta = null;
$eventoScelto = null;

if (isset($_GET["submit"]) && isset($_GET["classifica"]) && $_GET["classifica"] != "") {
    $classifica = explode('{.}', validate_input($_GET["classifica"]));
    $eventoScelto = $classifica[0];
    $dataInizioScelta = date_create($classifica[1]);
}

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if ($connectionOk) {
    // creo la lista delle classifiche per la scelta dall'archivio
    $resultClassifiche = $connection -> get_classifiche();
    foreach ($resultClassifiche as $resultClassifica) {
        $data = '';
        $dataInizio = date_create($resultClassifica['DataInizio']);
        $dataFine = $resultClassifica['DataFine'] ? date_create($resultClassifica['DataFine']) : $dataInizio;
        $evento = $resultClassifica['TipoEvento'];
        if ($dataInizio == $dataFine) {
            $data = date_format($dataInizio, 'Y');
        }
        elseif (date_format($dataInizio, 'Y') != date_format($dataFine, 'Y')) {
            $data = date_format($dataInizio, 'Y') . ' - ' . date_format($dataFine, 'Y');
        }
        elseif (date_format($dataInizio, 'm') != date_format($dataFine, 'm')) {
            $data = date_format($dataInizio, 'm/y') . ' - ' . date_format($dataFine, 'm/y');
        }
        else {
            $data = date_format($dataInizio, 'd/m/y') . ' - ' . date_format($dataFine, 'd/m/y');
        }
        if ((isset($_GET["submit"]) && isset($_GET["classifica"])) && $eventoScelto == $evento && $dataInizioScelta == $dataInizio) {
            $classifiche .= '<option value="' . $evento
                . '{.}' . date_format($dataInizio, 'Y-m-d') . '" selected>'
                . $evento . ' ' . $data . '</option>
                        ';
            $eventoScelto = $evento;
            $dataInizioScelta = $dataInizio;
        } else {
            $classifiche .= '<option value="' . $evento
                . '{.}' . date_format($dataInizio, 'Y-m-d') . '">'
                . $evento . ' ' . $data . '</option>
                            ';
        }
    }

    // creo le classifiche
    $tipiEvento = $connection -> get_tipo_evento($eventoScelto);
    foreach ($tipiEvento as $tipoEvento) {
        $titolo = $tipoEvento['Titolo'];
        $descrizione = $tipoEvento['Descrizione'];
        // controllo se ho scelto dall'archivio una classifica
        $dataInizio = is_null($dataInizioScelta) ? ($connection -> get_data_inizio_corrente($titolo)) : date_format($dataInizioScelta, 'Y-m-d');
        $classifica = $connection -> get_classifica($titolo, $dataInizio);
        if ($classifica != null) {
            $tabella = str_replace('{tipoEvento}', $titolo, $classificaHTML);
            $tabella = str_replace('{desTipoEvento}', $descrizione, $tabella);
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
    }

    $connection -> closeDBConnection();
}
else {
    $content .= '<p>I sistemi sono momentaneamente fuori servizio, ci scusiamo per il disagio.</p>';
}

$classificheHTML = str_replace('{classifica}', trim($classifiche), $classificheHTML);
$classificheHTML = str_replace('{classifiche}', $content, $classificheHTML);
echo replace_in_page($paginaHTML, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, trim($classificheHTML), $onload);
?>