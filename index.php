<?php

namespace Utilities;
require_once "utilities/utilities.php";

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/home-template.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("template/admin/logout-template.html") : '';

$title = 'Fungo';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
$percorso = '';
$percorsoAdmin = 'admin/';
$menu = get_menu($pageId);
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
    '{onload}' => $onload,
    '{logout}' => $logout,
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin
]);
