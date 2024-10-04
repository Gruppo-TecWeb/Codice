<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/modalita.html");

$title = 'Modalità &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina di presentazione delle modalità di battaglia e delle regole dei live organizzati dal collettivo rap Restraining Stirpe Crew.';
$keywords = 'restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = 'initIframe()';
$logout = '';
$classList = '';
$logo = get_content_between_markers($paginaHTML, 'logoLink');

if (isset($_SESSION["login"])) {
    $logout = get_content_between_markers($paginaHTML, 'logout');
}


// Connessione al database
$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

if ($connectionOk) {
    // Estrazione dati dalla tabella Basi
    $result = $connection->get_modalità();

    // Preparazione del contenuto dinamico
    $modalitàList = '';
    $modalitàItem = get_content_between_markers($content, 'listaModalità');
    foreach ($result as $row) {
        $newModalitàItem = $modalitàItem;
        // Sostituzione dei segnaposto nel template HTML
        if($row['Titolo']=="KickBack" || $row['Titolo']=="Royal Rumble"||$row['Titolo']=="Cypher"){
            $modalitàTag='<span lang="en">'.$row['Titolo'].'</span>';
            $modalitàEstesa = $row['Titolo'];
        }elseif($row['Titolo']=="3/4"){
            $modalitàTag= '<sup>3</sup>/<sub>4</sub>';
            $modalitàEstesa = 'tre quarti';
        }elseif($row['Titolo']=="4/4"){
            $modalitàEstesa = 'quattro quarti';
            $modalitàTag= '<sup>4</sup>/<sub>4</sub>';
        }else{
            $modalitàEstesa = $row['Titolo'];
            $modalitàTag = $row['Titolo'];
        }
        $newModalitàItem = multi_replace($newModalitàItem, [           
            '{modalità}' => $row['Titolo'],
            '{modalitàEstesa}' => $modalitàEstesa,       
            '{modalitàTag}' => $modalitàTag,
            '{link}' => $row['Link'],
            '{descrizioneVideo}' => $row['DescrizioneVideo'],
            '{descrizioneModalità}' => $row['DescrizioneModalità'],
            '{id}' => $row['Id']-1
        ]);
        $modalitàList .= $newModalitàItem;
    }
    

    // Sostituzione del blocco {beats} nel contenuto HTML
    $content = replace_content_between_markers($content,[
    'listaModalità' => $modalitàList,
    ]);
    // Chiusura della connessione al DB
    $connection->close_DB_connection();
} else {
    $content = "<p>Errore di connessione al database</p>";
}




echo multi_replace(replace_content_between_markers($paginaHTML, [
    'logo' => $logo,
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
    '{classList}' => $classList
]);
