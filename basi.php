<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

$basiHTML = file_get_contents("template/pagina-template.html");
$content = file_get_contents("template/basi.html");

$title = 'Battle &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = "Basi per freestyle di rap";
$keywords = 'Basi, Beats, Instrumental, Freestyle, Rap';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = 'showPlayBasi()';

echo multi_replace($basiHTML, [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{menu}' => $menu,
    '{breadcrumbs}' => $breadcrumbs,
    '{content}' => $content,
    '{onload}' => $onload
]);
