<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

$eventiHTML = file_get_contents("template/pagina-template.html");

$title = 'Eventi &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$content = file_get_contents("template/eventi.html");
$onload = '';

$connection = new DBAccess();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    $titolo = isset($_GET['titolo']) ? $_GET['titolo'] : '';
    $data = isset($_GET['data']) ? $_GET['data'] : '';
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'futuri';

    $listaTitoli = $connection->executeSelectQuery("Select distinct titolo from eventi");
    $opzioniTitoli = '';
    foreach ($listaTitoli as $evento) {
        $selected = ($evento['titolo'] == $titolo) ? ' selected' : '';
        $opzioniTitoli .= "<option value='" . $evento['titolo'] . "'" . $selected . ">" . $evento['titolo'] . "</option>";
    }
    $content = str_replace('{listaTitoli}', $opzioniTitoli, $content);
    $content = str_replace('{data}', $data, $content);

    $lista_eventi = $connection->getListaEventi($titolo, $data, $filtro);

    $connection->closeDBConnection();

    if ($lista_eventi == null) {
        $content .= '<p>Non ci sono eventi in programma</p>';
    } else {
        $content .= '<div id="lista-eventi">';
        foreach ($lista_eventi as $evento) {
            $content .= '<div class="evento">';
            $content .= '<p><a href="evento.php?id=' . urlencode($evento['id']) . '">' . htmlspecialchars($evento['titolo']) . ' ' . htmlspecialchars($evento['data']) . '</a></p>';
            $content .= '</div>';
        }
        $content .= '</div>';
    }
}

echo replace_in_page($eventiHTML, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $content, $onload);
