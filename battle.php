<?php

namespace Utilities;
require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");
use DB\DBAccess;

$pagTemplate = file_get_contents("template/pagina-template.html");


$title = 'Battle &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = "Introduzione regole e modalità freestyle per neofiti dell'argomento";
$keywords = 'Modalità freestyle, Chyper, Kickback, Royal rumble';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$content = file_get_contents("battle.html");
$onload = '';




$connection=new DBAccess();
$connectionOk=$connection->openDBConnection();
if($connectionOk)
    $resultBasi=$connection->get_basi();
    foreach($resultBasi as $base){
        $titolo=basename($base['nome']);
        $percorso=$base['nome'];
        $lista_basi.="<li class='base'> <dl> <dt>";
        $lista_basi.=$titolo;
        $lista_basi.="</dt> <dd> <audio> <source src='";
        $lista_basi.=$percorso;
        $lista_basi.="'type='audio/mp3'> </audio> </dd> </dl> </li>";
    }
    $content=str_replace("{lista_basi}", $lista_basi, $content);

echo replace_in_page($pagTemplate, $title, $description, $keywords, $pageId, $menu, $breadcrumbs, $content, $onload);
