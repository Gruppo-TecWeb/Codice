<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

$eventiHTML = file_get_contents("template/pagina-template.html");

$title = 'Eventi &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$content = '';
$onload = '';

$connection = new DBAccess();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    $lista_eventi = $connection->getlistaEventi();
    $connection->closeDBConnection();

    if ($lista_eventi == null) {
        $content .= '<p>Non ci sono eventi in programma</p>';
    } else {
        $content .= '<div id="lista-eventi">';
        foreach ($lista_eventi as $evento) {
            $content .= '<div class="evento">';
            $content .= '<h2><a href="evento.php?id=' . urlencode($evento['id']) . '">' . htmlspecialchars($evento['titolo']) .' '. htmlspecialchars($evento['data']) . '</a></h2>';
            $content .= '</div>';
        }
        $content .= '</div>';
    }
}

echo replace_in_page($eventiHTML, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $content, $onload);
