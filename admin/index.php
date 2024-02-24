<?php

namespace Utilities;
require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");
use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/template-pagina.html");
$content = file_get_contents("../template/admin/template-admin.html");
$adminContent = file_get_contents("../template/admin/admin-content.html");

$title = 'Area di amministrazione &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina principale dell\'area di amministrazione del sito.';
$keywords = '';
$percorso = '../';
$percorsoAdmin = '';
$menu = get_menu($pageId, $percorso);
$adminMenu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId, $percorso);
$onload = '';

if (!isset($_SESSION["login"])) {
    header("location: login.php");
}

if (isset($_SESSION["login"])) {
    $paginaHTML = replace_content_between_markers($paginaHTML, [
        'logout' => get_content_between_markers($paginaHTML, 'logout')
    ]);
} else {
    $paginaHTML = replace_content_between_markers($paginaHTML, [
        'logout' => ''
    ]);
}

echo multi_replace(replace_content_between_markers(
    multi_replace(
        replace_content_between_markers($paginaHTML, [
            'breadcrumbs' => $breadcrumbs,
            'menu' => $menu
        ]), [
        '{title}' => $title,
        '{description}' => $description,
        '{keywords}' => $keywords,
        '{pageId}' => $pageId,
        '{content}' => $content,
        '{onload}' => $onload,
        '{percorso}' => $percorso,
        '{adminContent}' => $adminContent
    ]), [
    'adminMenu' => $adminMenu
]), ['{percorsoAdmin}' => $percorsoAdmin]);