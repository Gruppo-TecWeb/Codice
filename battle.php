<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

$battleHTML = file_get_contents("template/pagina-template.html");
$content = file_get_contents("template/battle.html");

$title = 'Battle &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = "Introduzione regole e modalità freestyle per neofiti dell'argomento";
$keywords = 'Modalità freestyle, Chyper, Kickback, Royal rumble';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';

/*ELIMINABILE
$connection = new DBAccess();
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
    '{onload}' => $onload
]);
