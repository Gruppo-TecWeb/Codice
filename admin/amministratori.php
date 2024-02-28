<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/template-pagina.html");
$content = file_get_contents("../template/admin/template-pagina-admin.html");
$adminContent = file_get_contents("../template/admin/amministratori.html");

$title = 'Tipi Evento &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = '';
$keywords = '';
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

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    // fare quello che c'è da fare...
    $connection->closeDBConnection();
    $content = multi_replace(replace_content_between_markers($content, [
        'adminMenu' => $adminMenu
    ]), [
        '{adminContent}' => $adminContent,
    ]);
} else {
    header("location: ../errore500.php");
}

if (isset($_SESSION["login"])) {
    $logout = get_content_between_markers($paginaHTML, 'logout');
}

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
