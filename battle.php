<?php

namespace Utilities;
require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");
use DB\DBAccess;

session_start();

$battleHTML = file_get_contents("template/pagina-template.html");
$content = file_get_contents("template/battle.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("template/logout.html") : '';

$title = 'Battle &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = "Introduzione regole e modalità freestyle rap battle con esempi";
$keywords = 'Tipi di battle, 4/4, Minuto, Chyper, 3/4, Kickback, Royal rumble, Argomento, Acapella, Oggetti';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = 'showPlayBattle()';

/* Io(BRE) non uso il DB
$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if(!$connectionOk){
    header("location: errore500.php");
}
*/

echo multi_replace($battleHTML, [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{menu}' => $menu,
    '{breadcrumbs}' => $breadcrumbs,
    '{content}' => $content,
    '{onload}' => $onload,
    '{logout}' => $logout
]);