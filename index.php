<?php

namespace Utilities;
include "utilities/utilities.php";

$indexHTML = file_get_contents("template/pagina-template.html");

$title = 'Fungo';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$content = '';
$onload = '';

echo replace_in_page($indexHTML, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $content, $onload);


//carosello eventi