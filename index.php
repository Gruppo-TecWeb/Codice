<?php

namespace Utilities;
require_once "utilities/utilities.php";

session_start();

$paginaHTML = file_get_contents("template/pagina-template.html");
$content = file_get_contents("template/index.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("template/logout.html") : '';

$title = 'Fungo';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
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
    '{content}' => $content,
    '{onload}' => $onload,
    '{logout}' => $logout
]);
