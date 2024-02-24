<?php

namespace Utilities;
require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");
use DB\DBAccess;

session_start();

$battleHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/modalità.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("template/admin/logout-template.html") : '';

$title = 'Modlità &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = "Introduzione regole e modalità freestyle rap battle con esempi";
$keywords = 'Modalità battle, regole , 4/4, Minuto, Chyper, 3/4, Kickback, Royal rumble, Argomento, Acapella, Oggetti';
$percorso = '';
$percorsoAdmin = 'admin/';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

/* Io(BRE) non uso il DB
$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    $lista_basi = '';
    $percorso_basi = 'assets/media/basi/';
    $resultBasi = $connection->get_basi();
    foreach ($resultBasi as $base) {
        $titolo = basename($base['nome']);
        $nome = $base['nome'];
        $lista_basi .= "<li class='base'> <dl> <dt>";
        $lista_basi .= $titolo;
        $lista_basi .= "</dt> <dd> <audio controls> <source src='";
        $lista_basi .= $percorso_basi . $nome;
        $lista_basi .= "'type='audio/mp3'> </audio> </dd> </dl> </li>";
    }
    $content = str_replace("{lista_basi}", $lista_basi, $content);
} else {
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
    '{logout}' => $logout,
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin
]);