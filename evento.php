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
        $content .= '<p><a href="eventi.php">Torna alla lista degli eventi</a></p>';
        $content .= '<h2>' . htmlspecialchars($titolo) . ' ' . htmlspecialchars($data) . '</h2>';
        $content .= '<div id="evento-box">';
        $content .= '<img src="images/evento' . $eventoId . '.jpg" alt="">';
        $content .= '<div id="evento-info">';
        $content .= '<p>Ora: ' . htmlspecialchars($ora) . '</p>';
        $content .= '<p>Luogo: ' . htmlspecialchars($luogo) . '</p>';
        $content .= '<p>Descrizione: ' . htmlspecialchars($descrizione) . '</p>';
        $content .= '<p>Stagione: ' . htmlspecialchars($annoinizio) . ' ' . htmlspecialchars($meseinizio) . '</p>';
        $content .= '</div></div>';
        $title = $titolo . ' ' . $data;
        $breadcrumbs = get_breadcrumbs(basename('eventi.php', '.php'), $title);
        $title = $title . ' &minus; Fungo';
    }
}

echo replace_in_page($eventiHTML, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $content, $onload);
