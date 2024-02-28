<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");

$title = 'Evento &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina di presentazione di un evento organizzato dal collettivo rap Restraining Stirpe Crew.';
$keywords = 'restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
$percorso = '';
$percorsoAdmin = 'admin/';
$menu = get_menu($pageId, $percorso);
$breadcrumbs = get_breadcrumbs($pageId, $percorso);

$content = '';
$onload = '';
$logout = '';

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
        $stagioneEvento = '';
        $descrizioneEvento = '';
        if ($tipoEvento  != null && $dataInizioClassifica != null) {
            $stagioneEventoTemplate = get_content_between_markers($content, 'stagioneEvento');
            $stagioneEvento = multi_replace($stagioneEventoTemplate, [
                '{tipoEvento}' => $tipoEvento,
                '{dataInizioClassifica}' => $dataInizioClassifica
            ]);
        }
        if ($descrizione != null) {
            $descrizioneEventoTemplate = get_content_between_markers($content, 'descrizioneEvento');
            $descrizioneEvento = multi_replace($descrizioneEventoTemplate, [
                '{descrizione}' => $descrizione
            ]);
        }
        $content = multi_replace(replace_content_between_markers($content, [
            'stagioneEvento' => $stagioneEvento,
            'descrizioneEvento' => $descrizioneEvento
        ]), [
            '{titolo}' => $titolo,
            '{data}' => $data,
            '{ora}' => $ora,
            '{luogo}' => $luogo,
            '{locandina}' => $locandina,
        ]);
        $breadcrumbs = multi_replace($breadcrumbs, [
            '{id}' => $eventoId,
            '{evento}' => 'Evento',
        ]);
    }
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
