<?php

namespace Utilities;
require_once("utilities/utilities.php");

session_start();

$paginaHTML = file_get_contents("template/pagina-template.html");
$content = file_get_contents("template/errore500.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("template/logout-template.html") : '';

$title = 'Errore 500 &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina di errore 500.';
$keywords = 'error 500';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

echo replace_content_between_markers(multi_replace($paginaHTML, [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{breadcrumbs}' => $breadcrumbs,
    '{content}' => $content,
    '{onload}' => $onload,
    '{logout}' => $logout
]), [
    'menu' => $menu,
]);