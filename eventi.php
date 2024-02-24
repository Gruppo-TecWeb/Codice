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
    $eventi_per_pagina = 8;
    $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
    $titolo = isset($_GET['titolo']) ? $_GET['titolo'] : '';
    $data = isset($_GET['data']) ? $_GET['data'] : '';

    $lista_eventi_array = $connection->getListaEventi($data, $titolo);
    $lista_titoli_array = $connection->getTitoliEventi();
    $connection->closeDBConnection();

    // Costruzione della paginazione
    $pagination = '';
    $numero_pagine = ceil(count($lista_eventi_array) / $eventi_per_pagina);
    $paginationTemplate = get_content_between_markers($content, 'pagination');
    if ($numero_pagine > 1) {
        $pages = '';
        $offset = ($pagina - 1) * $eventi_per_pagina;
        $lista_eventi_array = array_slice($lista_eventi_array, $offset, $eventi_per_pagina);
        for ($i = 1; $i <= $numero_pagine; $i++) {
            $data_encoded = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            $titolo_encoded = htmlspecialchars($titolo, ENT_QUOTES, 'UTF-8');
            $currentPageTemplate = get_content_between_markers($paginationTemplate, 'currentPage');
            $notCurrentPageTemplate = get_content_between_markers($paginationTemplate, 'notCurrentPage');
            if ($i == $pagina) {
                $pages .= str_replace('{numeroPagina}', $i, $currentPageTemplate);
            } else {
                $pages .= multi_replace($notCurrentPageTemplate, [
                    '{numeroPagina}' => $i,
                    '{data}' => $data_encoded,
                    '{titolo}' => $titolo_encoded
                ]);
            }
        }
        $pagination = replace_content_between_markers($paginationTemplate, [
            'pages' => $pages
        ]);
    }

    // Costruzione delle liste di titoli
    $lista_titoli_string = '';
    $option = get_content_between_markers($content, 'listaTitoli');
    foreach ($lista_titoli_array as $evento) {
        $selected = ($evento['Titolo'] == $titolo) ? ' selected' : '';
        $lista_titoli_string .= multi_replace($option, [
            '{titoloEvento}' => $evento['Titolo'],
            '{selezioneEvento}' => $selected
        ]);
    }

    // Costruzione della lista di eventi
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
    $content = multi_replace($content, [
        '{data}' => $data,
    ]);
    $content = replace_content_between_markers($content, [
        'listaTitoli' => $lista_titoli_string,
        'listaEventi' => $lista_eventi_string,
        'pagination' => $pagination,
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
