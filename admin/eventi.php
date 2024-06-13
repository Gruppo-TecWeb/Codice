<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/eventi.html");

$title = 'Eventi &minus; Admin &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'pagina di amministrazione per la gestione degli eventi';
$keywords = 'Fungo, amministrazione, eventi';
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
    $messaggiFormHTML = get_content_between_markers($content, 'messaggiForm');
    $messaggioForm = get_content_between_markers($messaggiFormHTML, 'messaggioForm');
    $lista = '';

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
    $elementoLista = get_content_between_markers($content, 'elementoLista');

    foreach ($eventi as $evento) {
        $lista .= multi_replace($elementoLista, [
            '{titolo}' => $evento['Titolo'],
            '{data}' => date_format(date_create($evento['Data']), 'Y-m-d'),
            '{dataVisualizzata}' => date_format_ita($evento['Data']),
            '{idEvento}' => $evento['Id']
        ]);
    }

    $messaggiFormHTML = $messaggiForm == '' ? '' : replace_content_between_markers($messaggiFormHTML, ['messaggioForm' => $messaggiForm]);

    $content = replace_content_between_markers($content, [
        'elementoLista' => $lista,
        'messaggiForm' => $messaggiFormHTML
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
