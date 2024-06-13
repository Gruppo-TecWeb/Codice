<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/profilo.html");

$title = 'Profilo personale &minus; Admin &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina profilo contenente le informazioni relative al proprio profilo utente.';
$keywords = 'profilo, amministrazione, admin, restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
$menu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

$immagineProfiloDefault = 'default_profile_pic.png';

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
    if (isset($_GET['errore'])) {
        $messaggiForm .= multi_replace($messaggioForm, ['{testo}' => "Errore imprevisto"]);
    }

    $utente = $connection->get_utente_by_username($_SESSION["username"]);

    if ($utente === null) {
        header("location: errore500.php");
        exit;
    }

    $messaggiFormHTML = $messaggiForm == '' ? '' : replace_content_between_markers($messaggiFormHTML, ['messaggioForm' => $messaggiForm]);

    $content = replace_content_between_markers(multi_replace($content, [
        '{username}' => $utente["Username"],
        '{email}' => $utente["Email"],
        '{immagineProfilo}' => $utente["ImmagineProfilo"] == '' ? $immagineProfiloDefault : $utente["ImmagineProfilo"]
    ]), [
        'messaggiForm' => $messaggiFormHTML
    ]);
} else {
    header("location: errore500.php");
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
