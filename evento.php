<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

$eventiHTML = file_get_contents("template/pagina-template.html");

$title = '';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_menu(basename('eventi.php', '.php'));
$breadcrumbs = '';

$content = '';
$onload = '';

$connection = new DBAccess();
$connectionOk = $connection->openDBConnection();

$eventoId = $_GET['id'];
if ($connectionOk) {
    [$titolo, $descrizione, $data, $ora, $luogo, $annoinizio, $meseinizio, $tipoevento] = $connection->getEvento($eventoId);
    $connection->closeDBConnection();

    if ($titolo == null) {
        $content .= '<p>Evento non trovato</p>';
    } else {
        $content .= '<div id="' . $eventoId . '">';
        $content .= '<h2>' . htmlspecialchars($titolo) . '</h2>';
        $content .= '<p>Data: ' . htmlspecialchars($data) . '</p>';
        $content .= '<p>Ora: ' . htmlspecialchars($ora) . '</p>';
        $content .= '<p>Luogo: ' . htmlspecialchars($luogo) . '</p>';
        $content .= '<p>Descrizione: ' . htmlspecialchars($descrizione) . '</p>';
        $content .= '<p>Stagione: ' . htmlspecialchars($annoinizio) . ' ' . htmlspecialchars($meseinizio) . '</p>';
        $content .= '</div>';
        $title = $titolo . ' ' . $data;
        $breadcrumbs = get_breadcrumbs(basename('eventi.php', '.php'), $title);
        $title = $title . ' &minus; Fungo';
    }
}

echo replace_in_page($eventiHTML, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $content, $onload);
