<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/beats.html");
$style = 'beats/beats.css';
$styleMobile = 'beats/beats.mobile.css';
$stylePrint = 'beats/beats.print.css';

$linkStyle = get_content_between_markers($paginaHTML, 'linkStyle');
$linkStyle = multi_replace($linkStyle, ['{style}' => $style]);
$linkStyleMobile = get_content_between_markers($paginaHTML, 'linkStyleMobile');
$linkStyleMobile = multi_replace($linkStyleMobile, ['{styleMobile}' => $styleMobile]);
$linkStylePrint = get_content_between_markers($paginaHTML, 'linkStylePrint');
$linkStylePrint = multi_replace($linkStylePrint, ['{stylePrint}' => $stylePrint]);

$title = 'Beats &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = "Basi per freestyle di rap";
$keywords = 'Basi, Beats, Instrumental, Freestyle, Rap';
$percorso = '';
$percorsoAdmin = 'admin/';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = "init_beats(), autoPlay('11 - Goodbye - Big Joe.mp3')";
$logout = '';
$classList = 'fullMenu';
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
