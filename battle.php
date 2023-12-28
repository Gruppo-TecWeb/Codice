<?php

require_once("DBAccess-utilities/utilities.php");
use Utilities\Utilities;

$utilities = new Utilities();

$eventiHTML = file_get_contents("template/pagina-template.html");

$title = 'Battle &minus; Fungo';
$pageId = 'battle';
$description = '';
$keywords = '';
$menu = $utilities -> get_menu($pageId);
$breadcrumbs = $utilities -> get_breadcrumbs($pageId);
$content = '';
$onload = '';

echo $utilities -> replace_in_page($eventiHTML, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $content, $onload);
