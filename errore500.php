<?php

namespace Utilities;
require_once("utilities/utilities.php");

session_start();

$paginaHTML = file_get_contents("template/pagina-template.html");
$errore500HTML = file_get_contents("template/errore500-template.html");

$title = 'Errore 500 &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina di errore 500.';
$keywords = 'error 500';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

echo multi_replace($paginaHTML,[
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{menu}' => $menu,
    '{breadcrumbs}' => $breadcrumbs,
    '{content}' => $errore500HTML,
    '{onload}' => $onload
]);
