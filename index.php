<?php

require_once("DBAccess-utilities/utilities.php");

$indexHTML = file_get_contents("template/pagina-template.html");

$title = 'Fungo';
$pageId = '';
$description = '';
$keywords = '';
$menu = '
<li id="currentLink" lang="en">Home</li>
<li><a href="eventi.html">Eventi</a></li>
<li><a href="classifiche.html">Classifiche</a></li>
<li><a href="battle.php">Battle</a></li>
<li><a href="chi-siamo.html">Chi siamo</a></li>
';
$breadcrumbs = '
<p>Ti trovi in: <span lang="en">Home</span></p>';
$content='';

echo replace_in_page($indexHTML, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $content);
