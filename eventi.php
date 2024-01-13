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
$onload = 'init_eventi();';

$connection = new DBAccess();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    $titolo = isset($_GET['titolo']) ? $_GET['titolo'] : '';
    $data = isset($_GET['data']) ? $_GET['data'] : '';
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
    
    $lista_eventi_array = $connection->getListaEventi($filtro, $data, $titolo);
    
    $lista_titoli_array = $connection->executeSelectQuery("Select distinct titolo from eventi");
    $lista_titoli_string = '';
    foreach ($lista_titoli_array as $evento) {
        $selected = ($evento['titolo'] == $titolo) ? ' selected' : '';
        $lista_titoli_string .= "<option value='" . $evento['titolo'] . "'" . $selected . ">" . $evento['titolo'] . "</option>";
    }


    $connection->closeDBConnection();
    $lista_eventi_string = '';
    if ($lista_eventi_array == null) {
        $lista_eventi_string .= '<p>Non ci sono eventi in programma</p>';
    } else {
        foreach ($lista_eventi_array as $evento) {
            $lista_eventi_string .= '<article>';
            $lista_eventi_string .= '<a href="evento.php?id=' . urlencode($evento['id']) . '">';
            $lista_eventi_string .= '<p>' . format_date($evento['data']) . '</p>';
            $lista_eventi_string .= '<img src="images/' . $evento['locandina'] . '">';
            $lista_eventi_string .= '<p>' . htmlspecialchars($evento['titolo']) . '</p>';
            $lista_eventi_string .= '</a>';
            $lista_eventi_string .= '</article>';
        }
    }
    $content = multi_replace($content, [
        '{listaTitoli}' => $lista_titoli_string,
        '{data}' => $data,
        '{filtro}' => $filtro,
        '{listaEventi}' => $lista_eventi_string,
    ]);
} else {
    $content = "<p>I sistemi sono momentaneamente fuori servizio, ci scusiamo per il disagio</p>";
}

echo multi_replace($eventiHTML, [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{menu}' => $menu,
    '{breadcrumbs}' => $breadcrumbs,
    '{content}' => $content,
    '{onload}' => $onload
]);
