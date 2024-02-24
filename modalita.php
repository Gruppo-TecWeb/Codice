<?php

namespace Utilities;
require_once("utilities/utilities.php");

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/modalita.html");

$title = 'Battle &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = '';
$percorso = '';
$percorsoAdmin = 'admin/';
$keywords = '';
$menu = get_menu($pageId, $percorso);
$breadcrumbs = get_breadcrumbs($pageId, $percorso);
$onload = 'showPlayBattle()';

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