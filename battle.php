<?php

namespace Utilities;
require_once("utilities/utilities.php");

$pagTemplate = file_get_contents("template/pagina-template.html");


$title = 'Battle &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = "Introduzione regole e modalità freestyle per neofiti dell'argomento";
$keywords = 'Modalità freestyle, Chyper, Kickback, Royal rumble';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$content = file_get_contents("battle.html");
$onload = '';

echo replace_in_page($pagTemplate, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $content, $onload);
