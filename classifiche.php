<?php

namespace Utilities;
require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");
use DB\DBAccess;

$paginaHTML = file_get_contents("template/pagina-template.html");
$tabellaHTML = file_get_contents("template/tabella-template.html");
$rigaHTML = file_get_contents("template/tabella-riga-template.html");

$title = 'Classifiche &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Classifiche attuali sulla base dei punteggi ottenuti durante le battle di freestyle rap degli eventi Fungo e Micelio.';
$keywords = 'classifiche, fungo, micelio, freestyle, rap, freestyle rap, battle';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$content = '';
$onload = '';

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if ($connectionOk) {
    $content .= '<h2>Classifiche</h2>
    ';
    $classifica = $connection -> get_classifica('fungo');
    if ($classifica != null) {
        $tabella = str_replace('{tipoEvento}', 'Fungo', $tabellaHTML);
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

    $classifica = $connection -> get_classifica('micelio');
    if ($classifica != null) {
        $tabella = str_replace('{tipoEvento}', 'Micelio', $tabellaHTML);
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
else {
    $content .= '<p>I sistemi sono momentaneamente fuori servizio, ci scusiamo per il disagio.</p>';
}

echo replace_in_page($paginaHTML, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $content, $onload);
