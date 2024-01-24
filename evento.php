<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$eventoHTML = file_get_contents("template/pagina-template.html");

$title = '';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = '';

$content = '';
$onload = '';

$connection = new DBAccess();
$connectionOk = $connection->openDBConnection();

$eventoId = $_GET['id'];
if ($connectionOk) {
    [$titolo, $descrizione, $data, $ora, $luogo, $locandina, $tipoEvento, $dataInizioClassifica] = $connection->getEvento($eventoId);
    $connection->closeDBConnection();

    if ($titolo == null) {
        $content .= '<p>Evento non trovato</p>';
    } else {
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
        $breadcrumbs = str_replace('{id}', $eventoId, $breadcrumbs);
        $breadcrumbs = str_replace('{evento}', $titolo.' '.$data, $breadcrumbs);
        $title = $title . ' &minus; Fungo';
    }
} else {
    header("location: errore500.php");
}

echo multi_replace($eventoHTML, [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{menu}' => $menu,
    '{breadcrumbs}' => $breadcrumbs,
    '{content}' => $content,
    '{onload}' => $onload
]);
