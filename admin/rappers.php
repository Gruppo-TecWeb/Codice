<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/rappers.html");

$title = 'Rappers &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$messaggiForm = '';
$righeTabella = '';

if (!isset($_SESSION["login"])) {
    header("location: ../login.php");
}

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    $messaggioForm = get_content_between_markers($content, 'messaggioForm');

    if (isset($_GET['errore'])) {
        $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore imprevisto"]);
    }

    if (isset($_GET['eliminato'])) {
        if ($_GET['eliminato'] == 0) {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore nell'eliminazione del Rapper"]);
        } else {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Rapper eliminato correttamente"]);
        }
    } elseif (isset($_GET['aggiunto'])) {
        $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Rapper aggiunto correttamente"]);
    } elseif (isset($_GET['modificato'])) {
        $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Rapper modificato correttamente"]);
    }

    $rappers = $connection->get_utenti_base();
    $rigaTabella = get_content_between_markers($content, 'rigaTabella');

    foreach ($rappers as $rapper) {
        $righeTabella .= multi_replace($rigaTabella, [
            '{username}' => $rapper['Username'],
            '{email}' => $rapper['Email']
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
