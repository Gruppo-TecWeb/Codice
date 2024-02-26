<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/eventi.html");

$title = 'Eventi &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
$percorso = '';
$percorsoAdmin = 'admin/';
$menu = get_menu($pageId, $percorso);
$breadcrumbs = get_breadcrumbs($pageId, $percorso);
$onload = '';
$logout = '';

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    $titolo = isset($_GET['titolo']) ? $_GET['titolo'] : '';
    $data = isset($_GET['data']) ? $_GET['data'] : '';

    $lista_eventi_array = $connection->getListaEventi($data, $titolo);
    $lista_eventi_array;
    $lista_titoli_array = $connection->getTitoliEventi();
    $connection->closeDBConnection();

    $lista_titoli_string = '';
    $option = get_content_between_markers($content, 'listaTitoli');
    foreach ($lista_titoli_array as $evento) {
        $selected = ($evento['Titolo'] == $titolo) ? ' selected' : '';
        $lista_titoli_string .= multi_replace($option, [
            '{titoloEvento}' => $evento['Titolo'],
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
                '{idEvento}' => urlencode($evento['Id']),
                '{valueDataEvento}' => $evento['Data'],
                '{dataEvento}' => $evento['Data'],
                '{locandinaEvento}' => $evento['Locandina'],
                '{titoloEvento}' => htmlspecialchars($evento['Titolo'])
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