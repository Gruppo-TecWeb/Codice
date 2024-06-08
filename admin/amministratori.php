<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/amministratori.html");

$title = 'Amministratori &minus; Admin &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'pagina di amministrazione per la gestione degli amministratori';
$keywords = 'Fungo, amministrazione, amministratori';
$menu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

if (!isset($_SESSION["login"])) {
    header("location: ../login.php");
    exit;
}

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

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
            '{idUtente}' => $amministratore['Id'],
            '{username}' => $amministratore['Username'],
            '{email}' => $amministratore['Email']
        ]);
    }

    $content = replace_content_between_markers($content, [
        'rigaTabella' => $righeTabella,
        'messaggiForm' => $messaggiForm
    ]);

    $connection->close_DB_connection();
} else {
    header("location: ../errore500.php");
    exit;
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
