<?php

require_once("DBAccess-utilities/utilities.php");

$eventiHTML = file_get_contents("template/pagina-template.html");

$title = 'Eventi &minus; Fungo';
$pageId = 'eventi';
$description = '';
$keywords = '';
$menu = get_menu($pageId);
$breadcrumbs = '
<p>Ti trovi in: <span lang="en">Home</span> &gt; &gt; Eventi</p>';
$content = '';
$onload = '';

echo replace_in_page($eventiHTML, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $content, $onload);
