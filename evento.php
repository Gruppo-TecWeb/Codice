<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/pagina-template.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("template/logout-template.html") : '';

$title = '';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = '';

$content = '';
$onload = '';

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

$eventoId = $_GET['id'];
if ($connectionOk) {
    $evento = $connection->getEvento($eventoId);
    $connection->closeDBConnection();
    if ($evento == null) {
        $content .= '<p>Evento non trovato</p>';
    } else {
        [$titolo, $descrizione, $data, $ora, $luogo, $locandina, $tipoEvento, $dataInizioClassifica] = array_values($evento);

        $content = file_get_contents("template/evento.html");
        $content = multi_replace($content, [
            '{titolo}' => $titolo,
            '{descrizione}' => $descrizione,
            '{data}' => $data,
            '{ora}' => $ora,
            '{luogo}' => $luogo,
            '{locandina}' => $locandina,
            '{tipoEvento}' => $tipoEvento,
            '{dataInizioClassifica}' => $dataInizioClassifica
        ]);
        $breadcrumbs = get_breadcrumbs($pageId);
        $title = $titolo . ' ' . $data;
        $breadcrumbs = multi_replace($breadcrumbs, [
            '{id}' => $eventoId,
            '{evento}' => $title
        ]);
        $title = $title . ' &minus; Fungo';
    }
} else {
    header("location: errore500.php");
}

echo replace_content_between_markers(multi_replace($paginaHTML, [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{breadcrumbs}' => $breadcrumbs,
    '{content}' => $content,
    '{onload}' => $onload,
    '{logout}' => $logout
]), [
    'menu' => $menu,
]);
