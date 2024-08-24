<?php

namespace Utilities;

require_once("utilities/utilities.php");

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/errore404.html");

$style = 'errore/errore.css';
$styleMobile = 'errore/errore.mobile.css';

$linkStyle = get_content_between_markers($paginaHTML, 'linkStyle');
$linkStyle = multi_replace($linkStyle, ['{style}' => $style]);
$linkStyleMobile = get_content_between_markers($paginaHTML, 'linkStyleMobile');
$linkStyleMobile = multi_replace($linkStyleMobile, ['{styleMobile}' => $styleMobile]);
$linkStyleMobile = '';
$linkStylePrint = '';

$title = 'Errore 404 &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina di errore 404.';
$keywords = 'Fungo, errore, 404';
$percorso = '';
$percorsoAdmin = 'admin/';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$logout = '';
$classList = '';
$logo = get_content_between_markers($paginaHTML, 'logoLink');

http_response_code(404);

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
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin,
    '{classList}' => $classList
]);
