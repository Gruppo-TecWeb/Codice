<?php

namespace Utilities;

require_once("utilities/utilities.php");

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/chi-siamo.html");
$style = 'chi-siamo/chi-siamo.css';
$styleMobile = 'chi-siamo/chi-siamo.mobile.css';
$stylePrint = 'chi-siamo/chi-siamo.print.css';

$linkStyle = get_content_between_markers($paginaHTML, 'linkStyle');
$linkStyle = multi_replace($linkStyle, ['{style}' => $style]);
$linkStyleMobile = get_content_between_markers($paginaHTML, 'linkStyleMobile');
$linkStyleMobile = multi_replace($linkStyleMobile, ['{styleMobile}' => $styleMobile]);
$linkStylePrint = get_content_between_markers($paginaHTML, 'linkStylePrint');
$linkStylePrint = multi_replace($linkStylePrint, ['{stylePrint}' => $stylePrint]);

$title = 'Chi siamo &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina di presentazione del collettivo rap Restraining Stirpe Crew. Organizziamo e gestiamo gli eventi legati al Fungo e al Micelio, durante i quali si svolgono battle di freestyle rap, live di musica rap e dj set.';
$keywords = 'restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
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
    'logout' => $logout,
    'linkStyle' => $linkStyle,
    'linkStyleMobile' => $linkStyleMobile,
    'linkStylePrint' => $linkStylePrint
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => $content,
    '{onload}' => $onload,
    '{classList}' => $classList
]);
