<?php

namespace Utilities;

require_once "utilities/utilities.php";
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/index.html");

$title = 'Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina ufficiale del collettivo rap Restraining Stirpe Crew.';
$keywords = 'restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = 'init_index()';
$logout = '';

if (isset($_SESSION["login"])) {
    $logout = get_content_between_markers($paginaHTML, 'logout');
}


$eventoHome = get_content_between_markers($content, 'eventoHome');
$contentEvento = '';

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();
if ($connectionOk) {
    $headingEvento = '';

    $listaEventi  = $connection->getListaEventi(); // lista eventi futuri
    if (count($listaEventi) > 0) { // se ci sono eventi futuri
        $headingEvento = get_content_between_markers($eventoHome, 'prossimoEvento');
    } else { // altrimenti prendo i pasaati
        $listaEventi = $connection->getListaEventi('', '', false); // lista eventi passati
        $headingEvento = get_content_between_markers($eventoHome, 'ultimoEvento');
    }
    if (count($listaEventi) > 0) { // se ho ottenuto eventi
        $eventoId = $listaEventi[0]['Id'];
        $evento = $connection->getEvento($eventoId);
        [$titolo, $descrizione, $data, $ora, $luogo, $locandina, $tipoEvento, $dataInizioClassifica] = array_values($evento);

        $contentEvento = multi_replace(replace_content_between_markers($eventoHome, [
            'intestazione' => $headingEvento
        ]), [
            '{id}' => $eventoId,
            '{titolo}' => $titolo,
            '{data}' => $data,
            '{ora}' => $ora,
            '{luogo}' => $luogo
        ]);
    }
    $connection->closeDBConnection();
}

$content = replace_content_between_markers($content, [
    'eventoHome' => $contentEvento
]);

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
    '{onload}' => $onload
]);
