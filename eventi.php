<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$eventiHTML = file_get_contents("template/pagina-template.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("template/logout.html") : '';

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
    $option = get_content_between_markers($content, 'listaTitoli');
    foreach ($lista_titoli_array as $evento) {
        $selected = ($evento['titolo'] == $titolo) ? ' selected' : '';
        $lista_titoli_string .= multi_replace($option, [
            '{titoloEvento}' => $evento['titolo'],
            '{selezioneEvento}' => $selected
        ]);
    }

    $lista_eventi_string = '';
    if ($lista_eventi_array == null) {
        $lista_eventi_string .= '<p>Non ci sono eventi in programma</p>';
    } else {
        $article = get_content_between_markers($content, 'listaEventi');
        foreach ($lista_eventi_array as $evento) {
            $lista_eventi_string .= multi_replace($article, [
                '{idEvento}' => urlencode($evento['id']),
                '{dataEvento}' => $evento['data'],
                '{locandinaEvento}' => $evento['locandina'],
                '{titoloEvento}' => htmlspecialchars($evento['titolo'])
            ]);
        }
    }
    $content = str_replace('{data}', $data, $content);
    $content = replace_content_between_markers($content, [
        'listaTitoli' => $lista_titoli_string,
        'listaEventi' => $lista_eventi_string
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
    '{onload}' => $onload,
    '{logout}' => $logout
]);
