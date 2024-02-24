<?php

namespace Utilities;
require_once "utilities/utilities.php";

session_start();

$paginaHTML = file_get_contents("template/pagina-template.html");
$content = file_get_contents("template/home.html");

$title = 'Fungo';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = 'init_home()';

// $from = 'href="index.php"';
// $to = '';
// str_replace($from, $to, $paginaHTML); da rimuovere il link alla pagina corrente

echo multi_replace($paginaHTML,[
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{menu}' => $menu,
    '{breadcrumbs}' => $breadcrumbs,
    '{content}' => $content,
    '{onload}' => $onload
]);
?>