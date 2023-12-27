<?php

require_once("DBAccess-utilities/utilities.php");

$indexHTML = file_get_contents("template/pagina-template.html");

$title = 'Fungo';
$pageId = 'home';
$description = '';
$keywords = '';
$menu = get_menu($pageId);
$breadcrumbs = '
<p>Ti trovi in: <span lang="en">Home</span></p>';
$content = '';
$onload = '';

echo replace_in_page($indexHTML, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $content, $onload);
