<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$eventoHTML = file_get_contents("template/template-pagina.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("template/admin/logout-template.html") : '';

$title = 'Evento &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
$percorso = '';
$percorsoAdmin = 'admin/';
$menu = get_menu($pageId);
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
        $breadcrumbs = multi_replace($breadcrumbs, [
            '{id}' => $eventoId,
            '{evento}' => 'Evento',
        ]);
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
    '{onload}' => $onload,
    '{logout}' => $logout,
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin
]);
