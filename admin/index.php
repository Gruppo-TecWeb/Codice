<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/template-pagina.html");
$content = file_get_contents("../template/admin/template-pagina-admin.html");
$adminContent = file_get_contents("../template/admin/index.html");

$title = 'Area di amministrazione &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina principale dell\'area di amministrazione del sito.';
$keywords = 'amministrazione, admin, restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
$percorso = '../';
$percorsoAdmin = '';
$menu = get_menu($pageId, $percorso);
$adminMenu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId, $percorso);
$onload = '';
$logout = '';

if (!isset($_SESSION["login"])) {
    header("location: login.php");
}

if (isset($_SESSION["login"])) {
    $logout = get_content_between_markers($paginaHTML, 'logout');
}

$content = multi_replace(replace_content_between_markers($content, [
    'adminMenu' => $adminMenu
]), [
    '{adminContent}' => $adminContent,
]);

echo multi_replace(replace_content_between_markers($paginaHTML, [
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
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin
]);
