<?php

namespace Utilities;
require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");
use DB\DBAccess;

session_start();


$beatsHTML = file_get_contents("template/pagina-template.html");
$content = file_get_contents("template/beats.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("template/admin/logout-template.html") : '';

$title = 'Beats &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = "Basi per freestyle di rap";
$keywords = 'Basi, Beats, Instrumental, Freestyle, Rap';
$percorso = '';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

echo multi_replace($beatsHTML, [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{menu}' => $menu,
    '{breadcrumbs}' => $breadcrumbs,
    '{content}' => $content,
    '{onload}' => $onload,
    '{percorso}' => $percorso
]);
