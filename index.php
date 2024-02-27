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
$description = '';
$keywords = '';
$percorso = '';
$percorsoAdmin = 'admin/';
$menu = get_menu($pageId, $percorso);
$breadcrumbs = get_breadcrumbs($pageId, $percorso);
$onload = 'init_index()';
$logout = '';

// $from = 'href="index.php"';
// $to = '';
// str_replace($from, $to, $paginaHTML); da rimuovere il link alla pagina corrente

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
    if (count($listaEventi) > 0) {
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
            '{luogo}' => $luogo,
            '{locandina}' => $locandina
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
    '{onload}' => $onload,
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin
]);
