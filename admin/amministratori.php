<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/amministratori.html");

$title = 'Admin &minus; Amministratori &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

if (!isset($_SESSION["login"])) {
    header("location: ../login.php");
}

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    $messaggiForm = '';
    $righeTabella = '';
    $messaggioForm = get_content_between_markers($content, 'messaggioForm');

    if (isset($_GET['errore'])) {
        $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore imprevisto"]);
    }

    if (isset($_GET['eliminato'])) {
        if ($_GET['eliminato'] == 0) {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore nell'eliminazione dell'Amministratore"]);
        } else {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Amministratore eliminato correttamente"]);
        }
    } elseif (isset($_GET['aggiunto'])) {
        $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Amministratore aggiunto correttamente"]);
    } elseif (isset($_GET['modificato'])) {
        $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Amministratore modificato correttamente"]);
    }

    $amministratori = $connection->get_utenti_admin();
    $rigaTabella = get_content_between_markers($content, 'rigaTabella');

    foreach ($amministratori as $amministratore) {
        $righeTabella .= multi_replace($rigaTabella, [
            '{username}' => $amministratore['Username'],
            '{email}' => $amministratore['Email']
        ]);
    }

    $content = replace_content_between_markers($content, [
        'rigaTabella' => $righeTabella,
        'messaggiForm' => $messaggiForm
    ]);

    $connection->closeDBConnection();
} else {
    header("location: ../errore500.php");
}

echo multi_replace(replace_content_between_markers($paginaHTML, [
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => $content,
    '{onload}' => $onload
]);
