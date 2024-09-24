<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/beats.html");

$title = 'Beats &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = "Basi per freestyle di rap";
$keywords = 'Basi, Beats, Instrumental, Freestyle, Rap';
$percorso = '';
$percorsoAdmin = 'admin/';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = "init_beats(), onJavaScript(), autoPlay('11 - Goodbye - Big Joe.mp3')";
$logout = '';

if (isset($_SESSION["login"])) {
    $logout = get_content_between_markers($paginaHTML, 'logout');
}

// Connessione al database
$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

if ($connectionOk) {
    // Estrazione dati dalla tabella Basi
    $result = $connection->get_beats();

    // Preparazione del contenuto dinamico
    $beatsList = '';
    $beatItem = get_content_between_markers($content, 'beats');
    foreach ($result as $row) {
        $newBeatItem = $beatItem;
        // Sostituzione dei segnaposto nel template HTML
        $newBeatItem = multi_replace($newBeatItem, [
            
            '{titoloConEstensione}' => $row['Titolo'],
            $titleClean= str_replace('.mp3','',$row['Titolo']),
            '{titolo}' => $titleClean,
            '{descrizione}' => $row['Descrizione'],
            '{id}' => $row['Id']
        ]);
        $beatsList .= $newBeatItem;
    }
    

    // Sostituzione del blocco {beats} nel contenuto HTML
    $content = replace_content_between_markers($content,[
    'beats' => $beatsList,
    ]);
    $content=multi_replace($content, [
        '{titolo}' => $percorso,
        '{percorsoAdmin}' => $percorsoAdmin
    ]);

    // Chiusura della connessione al DB
    $connection->close_DB_connection();
} else {
    $content = "<p>Errore di connessione al database</p>";
}

// Output della pagina
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