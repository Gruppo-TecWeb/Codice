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
$logo = get_content_between_markers($paginaHTML, 'logoLink');

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
        $messaggiForm .= multi_replace($messaggioForm, [
            '{tipoMessaggio}' => 'inputError',
            '{messaggio}' => "Errore imprevisto"
        ]);
    } elseif (isset($_GET['eliminato'])) {
        if ($_GET['eliminato'] == 'false') {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'inputError',
                '{messaggio}' => "Errore nell'eliminazione dell'Evento"
            ]);
        } elseif ($_GET['eliminato'] == 'true') {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'successMessage',
                '{messaggio}' => "Evento eliminato correttamente"
            ]);
        } else {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'inputError',
                '{messaggio}' => "Errore imprevisto"
            ]);
        }
    } elseif (isset($_GET['aggiunto'])) {
        if ($_GET['aggiunto'] == 'false') {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'inputError',
                '{messaggio}' => "Errore nell'aggiunta dell'Evento"
            ]);
        } elseif ($_GET['aggiunto'] == 'true') {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'successMessage',
                '{messaggio}' => "Evento aggiunto correttamente"
            ]);
        } else {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'inputError',
                '{messaggio}' => "Errore imprevisto"
            ]);
        }
    } elseif (isset($_GET['modificato'])) {
        if ($_GET['modificato'] == 'false') {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'inputError',
                '{messaggio}' => "Errore nella modifica dell'Evento"
            ]);
        } elseif ($_GET['modificato'] == 'true') {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'successMessage',
                '{messaggio}' => "Evento modificato correttamente"
            ]);
        } else {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'inputError',
                '{messaggio}' => "Errore imprevisto"
            ]);
        }
    } elseif (isset($_GET['punteggi-eliminati'])) {
        if ($_GET['punteggi-eliminati'] == 'false') {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'inputError',
                '{messaggio}' => "Errore nell'eliminazione dei punteggi"
            ]);
        } elseif ($_GET['punteggi-eliminati'] == 'true') {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'successMessage',
                '{messaggio}' => "Punteggi eliminati correttamente"
            ]);
        } else {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'inputError',
                '{messaggio}' => "Errore imprevisto"
            ]);
        }
    } elseif (isset($_GET['punteggi-modificati'])) {
        if ($_GET['punteggi-modificati'] == 'false') {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'inputError',
                '{messaggio}' => "Errore nella modifica dei punteggi"
            ]);
        } elseif ($_GET['punteggi-modificati'] == 'true') {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'successMessage',
                '{messaggio}' => "Punteggi modificati correttamente"
            ]);
        } else {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'inputError',
                '{messaggio}' => "Errore imprevisto"
            ]);
        }
    }

    $eventi = $connection->get_eventi();
    $elementoLista = get_content_between_markers($content, 'elementoLista');
    $nessunElemento = get_content_between_markers($content, 'nessunElemento');

    foreach ($eventi as $evento) {
        $lista .= multi_replace($elementoLista, [
            '{titolo}' => $evento['Titolo'],
            '{data}' => date_format(date_create($evento['Data']), 'Y-m-d'),
            '{dataVisualizzata}' => date_format_ita($evento['Data']),
            '{idEvento}' => $evento['Id']
        ]);
    }

    $messaggiFormHTML = $messaggiForm == '' ? '' : replace_content_between_markers($messaggiFormHTML, ['messaggioForm' => $messaggiForm]);
    $lista = $lista == '' ? $nessunElemento : $lista;

    $content = replace_content_between_markers($content, [
        'elementoLista' => $lista,
        'nessunElemento' => '',
        'messaggiForm' => $messaggiFormHTML
    ]);

    $connection->close_DB_connection();
} else {
    header("location: ../errore500.php");
    exit;
}

echo multi_replace(replace_content_between_markers($paginaHTML, [
    'logo' => $logo,
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
