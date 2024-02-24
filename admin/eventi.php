<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/template-pagina.html");
$adminHTML = file_get_contents("../template/admin/admin-template.html");
$adminContentHTML = file_get_contents("../template/admin/eventi-template.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("../template/admin/logout-template.html") : '';

$title = 'Gestione Eventi &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = '';
$keywords = '';
$percorso = '../';
$percorsoAdmin = '';
$menu = get_menu($pageId);
$adminMenu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    if (isset($_SESSION["login"])) {
    } else {
        header("location: login.php");
    }

    $connection->closeDBConnection();
} else {
    header("location: ../errore500.php");
}

echo multi_replace($paginaHTML, [
    '{content}' => $adminHTML,
    '{adminContent}' => $profiloHTML,
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
