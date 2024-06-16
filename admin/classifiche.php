<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/classifiche.html");

$title = 'Classifiche &minus; Admin &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina di amministrazione delle classifiche dei tipi di evento organizzati dal collettivo rap Restraining Stirpe Crew.';
$keywords = 'classifiche, tipi evento, restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
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
        $messaggiForm .= multi_replace($messaggioForm, [
            '{tipoMessaggio}' => 'inputError',
            '{messaggio}' => "Errore imprevisto"
        ]);
    } elseif (isset($_GET['eliminato'])) {
        if ($_GET['eliminato'] == 'false') {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'inputError',
                '{messaggio}' => "Errore nell'eliminazione della classifica"
            ]);
        } elseif ($_GET['eliminato'] == 'true') {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'successMessage',
                '{messaggio}' => "Classifica eliminata correttamente"
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
                '{messaggio}' => "Errore nell'aggiunta della classifica"
            ]);
        } elseif ($_GET['aggiunto'] == 'true') {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'successMessage',
                '{messaggio}' => "Classifica aggiunta correttamente"
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
                '{messaggio}' => "Errore nella modifica della classifica"
            ]);
        } elseif ($_GET['modificato'] == 'true') {
            $messaggiForm .= multi_replace($messaggioForm, [
                '{tipoMessaggio}' => 'successMessage',
                '{messaggio}' => "Classifica modificata correttamente"
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

    $classifiche = $connection->get_classifiche();
    foreach ($classifiche as $classifica) {
        $elementoLista = get_content_between_markers($content, 'elementoLista');
        $lista .= multi_replace($elementoLista, [
            '{titoloClassifica}' => $classifica['Titolo'],
            '{dataInizio}' => date_format(date_create($classifica['DataInizio']), 'Y-m-d'),
            '{dataFine}' => date_format(date_create($classifica['DataFine']), 'Y-m-d'),
            '{dataInizioVisualizzata}' => date_format(date_create($classifica['DataInizio']), 'd/m/y'),
            '{dataFineVisualizzata}' => date_format(date_create($classifica['DataFine']), 'd/m/y'),
            '{idClassifica}' => $classifica['Id']
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
