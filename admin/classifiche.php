<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/classifiche.html");

$title = 'Admin &minus; Classifiche &minus; Fungo';
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
    $messaggioForm = get_content_between_markers($content, 'messaggioForm');
    $lista = '';

    if (isset($_GET['errore'])) {
        $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore imprevisto"]);
    }

    if (isset($_GET['eliminato'])) {
        if ($_GET['eliminato'] == 0) {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore nell'eliminazione della classifica"]);
        } else {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Classifica eliminata correttamente"]);
        }
    } elseif (isset($_GET['aggiunto'])) {
        $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Classifica aggiunta correttamente"]);
    } elseif (isset($_GET['modificato'])) {
        $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Classifica modificata correttamente"]);
    }

    $classifiche = $connection->get_classifiche();
    foreach ($classifiche as $classifica) {
        $elementoLista = get_content_between_markers($content, 'elementoLista');
        $lista .= multi_replace($elementoLista, [
            '{titoloClassifica}' => $classifica['Titolo'],
            '{idClassifica}' => $classifica['Id']
        ]);
    }

    $content = replace_content_between_markers($content, [
        'elementoLista' => $lista,
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
