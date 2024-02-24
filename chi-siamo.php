<?php

namespace Utilities;
require_once("utilities/utilities.php");

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/chi-siamo.html");

$title = 'Chi siamo &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina di presentazione del collettivo rap Restraining Stirpe Crew. Organizziamo e gestiamo gli eventi legati al Fungo e al Micelio, durante i quali si svolgono battle di freestyle rap, live di musica rap e dj set.';
$keywords = 'restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
$percorso = '';
$percorsoAdmin = 'admin/';
$menu = get_menu($pageId, $percorso);
$breadcrumbs = get_breadcrumbs($pageId, $percorso);
$onload = '';

if (isset($_SESSION["login"])) {
    $paginaHTML = replace_content_between_markers($paginaHTML, [
        'logout' => get_content_between_markers($paginaHTML, 'logout')
    ]);
} else {
    $paginaHTML = replace_content_between_markers($paginaHTML, [
        'logout' => ''
    ]);
}

echo multi_replace(replace_content_between_markers($paginaHTML, [
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu
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