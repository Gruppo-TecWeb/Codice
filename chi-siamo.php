<?php

namespace Utilities;
require_once("utilities/utilities.php");

session_start();

$paginaHTML = file_get_contents("template/pagina.html");
$contentHTML = file_get_contents("template/chi-siamo-content.html");

$title = 'Chi siamo &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina di presentazione del collettivo rap Restraining Stirpe Crew. Organizziamo e gestiamo gli eventi legati al Fungo e al Micelio, durante i quali si svolgono battle di freestyle rap, live di musica rap e dj set.';
$keywords = 'restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

echo multi_replace($paginaHTML,[
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{menu}' => $menu,
    '{breadcrumbs}' => $breadcrumbs,
    '{content}' => $contentHTML,
    '{onload}' => $onload
]);
