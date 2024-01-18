<?php

namespace Utilities;
require_once("utilities/utilities.php");

session_start();

$eventiHTML = file_get_contents("template/pagina-template.html");

$title = 'Battle &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$content = '';
$onload = '';

echo replace_in_page($eventiHTML, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $content, $onload);
