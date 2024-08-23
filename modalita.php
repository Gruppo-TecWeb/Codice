<?php

namespace Utilities;

require_once("utilities/utilities.php");

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/modalita.html");
$style = 'modalita.css';
$styleMobile = 'modalita.mobile.css';
$stylePrint = 'modalita.print.css';

$title = 'Modalità &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina di presentazione delle modalità di battaglia e delle regole dei live organizzati dal collettivo rap Restraining Stirpe Crew.';
$keywords = 'restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = 'initIframe()';
$logout = '';
$classList = '';
$logo = get_content_between_markers($paginaHTML, 'logoLink');

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
    '{style}' => $style,
    '{styleMobile}' => $styleMobile,
    '{stylePrint}' => $stylePrint,
    '{content}' => $content,
    '{onload}' => $onload,
    '{classList}' => $classList
]);
