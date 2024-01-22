<?php

namespace Utilities;
require_once "utilities/utilities.php";

session_start();

$paginaHTML = file_get_contents("template/pagina-template.html");
$content = file_get_contents("template/home-template.html");

$title = 'Fungo';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

echo replace_in_page($paginaHTML, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $content, $onload);
?>