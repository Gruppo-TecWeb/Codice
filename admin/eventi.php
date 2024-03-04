<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/eventi.html");

$title = 'Admin &minus; Eventi &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

if (!isset($_SESSION["login"])) {
    header("location: ../login.php");
}

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

if ($connectionOk) {
    $messaggiForm = '';
    $messaggioForm = get_content_between_markers($content, 'messaggioForm');
    $righeTabella = '';

    if (isset($_GET['errore'])) {
        $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore imprevisto"]);
    }

    if (isset($_GET['eliminato'])) {
        if ($_GET['eliminato'] == 0) {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore nell'eliminazione dell'Evento"]);
        } else {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Evento eliminato correttamente"]);
        }
    } elseif (isset($_GET['aggiunto'])) {
        $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Evento aggiunto correttamente"]);
    } elseif (isset($_GET['modificato'])) {
        $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Evento modificato correttamente"]);
    }

    $eventi = $connection->get_eventi();
    $rigaTabella = get_content_between_markers($content, 'rigaTabella');

    foreach ($eventi as $evento) {
        $righeTabella .= multi_replace($rigaTabella, [
            '{titolo}' => $evento['Titolo'],
            '{dataVisualizzata}' => date_format(date_create($evento['Data']), 'd/m/y'),
            '{oraVisualizzata}' => date_format(date_create($evento['Ora']), 'G:i'),
            '{luogo}' => $evento['Luogo'],
            '{idEvento}' => $evento['Id'],
            '{data}' => $evento['Data'],
            '{ora}' => $evento['Ora'],
        ]);
    }

    $content = replace_content_between_markers($content, [
        'rigaTabella' => $righeTabella,
        'messaggiForm' => $messaggiForm
    ]);

    $connection->close_DB_connection();
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
