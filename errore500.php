<?php

namespace Utilities;

require_once("utilities/utilities.php");

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/errore500.html");

$title = 'Errore 500 &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina di errore 500.';
$keywords = 'error 500';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$logout = '';
$classList = '';
$logo = get_content_between_markers($paginaHTML, 'logoLink');

http_response_code(500);

if (isset($_SESSION["login"])) {
    $logout = get_content_between_markers($paginaHTML, 'logout');
}

echo multi_replace(replace_content_between_markers($paginaHTML, [
    'logo' => $logo,
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu,
    'logout' => $logout
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => $content,
    '{onload}' => $onload,
    '{classList}' => $classList
]);
