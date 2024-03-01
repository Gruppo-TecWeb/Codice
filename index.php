<?php

namespace Utilities;

require_once "utilities/utilities.php";

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/index.html");

$title = 'Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina ufficiale del collettivo rap Restraining Stirpe Crew.';
$keywords = 'restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$logout = '';

if (isset($_SESSION["login"])) {
    $logout = get_content_between_markers($paginaHTML, 'logout');
}

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
