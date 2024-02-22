<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$eventiHTML = file_get_contents("template/pagina-template.html");

$title = 'Eventi &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$content = file_get_contents("template/eventi.html");
$onload = '';

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    $titolo = isset($_GET['titolo']) ? $_GET['titolo'] : '';
    $data = isset($_GET['data']) ? $_GET['data'] : '';

    $lista_eventi_array = $connection->getListaEventi($data, $titolo);
    $lista_titoli_array = $connection->getTitoliEventi();
    $connection->closeDBConnection();

    $lista_titoli_string = '';
    foreach ($lista_titoli_array as $evento) {
        $selected = ($evento['titolo'] == $titolo) ? ' selected' : '';
        $lista_titoli_string .= "<option value='" . $evento['titolo'] . "'" . $selected . ">" . $evento['titolo'] . "</option>";
    }

    $lista_eventi_string = '';
    if ($lista_eventi_array == null) {
        $lista_eventi_string .= '<p>Non ci sono eventi in programma</p>';
    } else {
        foreach ($lista_eventi_array as $evento) {
            $lista_eventi_string .= '<article>';
            $lista_eventi_string .= '<a href="evento.php?id=' . urlencode($evento['id']) . '">';
            $lista_eventi_string .= '<time datetime="' . $evento['data'] . '">' . $evento['data'] . '</time>';
            $lista_eventi_string .= '<img src="assets/media/locandine/' . $evento['locandina'] . '">';
            $lista_eventi_string .= '<p>' . htmlspecialchars($evento['titolo']) . '</p>';
            $lista_eventi_string .= '</a>';
            $lista_eventi_string .= '</article>';
        }
    }
    $content = multi_replace($content, [
        '{data}' => $data,
        '{listaTitoli}' => $lista_titoli_string,
        '{listaEventi}' => $lista_eventi_string,
    ]);
} else {
    header("location: errore500.php");
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
