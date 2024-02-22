<?php

namespace Utilities;

require_once("../utilities/utilities.php");

session_start();

$paginaHTML = file_get_contents("../template/pagina-template.html");
$adminHTML = file_get_contents("../template/admin/admin-template.html");
$adminContentHTML = file_get_contents("../template/admin/admin-content.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("../template/admin/logout-template.html") : '';

$title = 'Area di amministrazione &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina principale dell\'area di amministrazione del sito.';
$keywords = '';
$percorso = '../';
$percorsoAdmin = '';
$menu = get_menu($pageId);
$adminMenu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

if (!isset($_SESSION["login"])) {
    header("location: login.php");
}

echo multi_replace($paginaHTML, [
    '{content}' => $adminHTML,
    '{adminContent}' => $adminContentHTML,
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{menu}' => $menu,
    '{adminMenu}' => $adminMenu,
    '{breadcrumbs}' => $breadcrumbs,
    '{onload}' => $onload,
    '{logout}' => $logout,
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin
]);
