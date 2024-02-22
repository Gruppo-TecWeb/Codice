<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$eventiHTML = file_get_contents("template/pagina-template.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("template/logout-template.html") : '';

$title = 'Eventi &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = '';
$keywords = '';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$content = file_get_contents("template/eventi.html");
$onload = '';

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    $eventi_per_pagina = 8;
    $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
    $titolo = isset($_GET['titolo']) ? $_GET['titolo'] : '';
    $data = isset($_GET['data']) ? $_GET['data'] : '';

    $lista_eventi_array = $connection->getListaEventi($data, $titolo);
    $lista_titoli_array = $connection->getTitoliEventi();
    $connection->closeDBConnection();

    // Costruzione della paginazione
    $pagination = '';
    $numero_pagine = ceil(count($lista_eventi_array) / $eventi_per_pagina);
    if ($numero_pagine > 1) {
        $pagination .= "<span>Pagine: </span>";
        $offset = ($pagina - 1) * $eventi_per_pagina;
        $lista_eventi_array = array_slice($lista_eventi_array, $offset, $eventi_per_pagina);
        for ($i = 1; $i <= $numero_pagine; $i++) {
            $data_encoded = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            $titolo_encoded = htmlspecialchars($titolo, ENT_QUOTES, 'UTF-8');
            if($i == $pagina) {
                $pagination .= "<span>$i</span> ";
            }
            else{
                $pagination .= "<a href='?pagina=$i&data=$data_encoded&titolo=$titolo_encoded'>$i</a> ";
            }
        }
    }

    // Costruzione delle liste di titoli
    $lista_titoli_string = '';
    foreach ($lista_titoli_array as $evento) {
        $selected = ($evento['titolo'] == $titolo) ? ' selected' : '';
        $lista_titoli_string .= "<option value='" . $evento['titolo'] . "'" . $selected . ">" . $evento['titolo'] . "</option>";
    }

    // Costruzione della lista di eventi
    $lista_eventi_string = '';
    if ($lista_eventi_array == null) {
        $lista_eventi_string .= '<p>Non ci sono eventi in programma</p>';
    } else {
        foreach ($lista_eventi_array as $evento) {
            $lista_eventi_string .= '<article>';
            $lista_eventi_string .= '<a href="evento.php?id=' . urlencode($evento['id']) . '">';
            $lista_eventi_string .= '<p>' . $evento['data'] . '</p>';
            $lista_eventi_string .= '<img src="assets/media/locandine/' . $evento['locandina'] . '">';
            $lista_eventi_string .= '<p>' . htmlspecialchars($evento['titolo']) . '</p>';
            $lista_eventi_string .= '</a>';
            $lista_eventi_string .= '</article>';
        }
    }

    // Sostituzione dei segnaposto
    $content = multi_replace($content, [
        '{data}' => $data,
        '{listaTitoli}' => $lista_titoli_string,
        '{listaEventi}' => $lista_eventi_string,
        '{pagination}' => $pagination,
    ]);
} else {
    header("location: errore500.php");
}

echo multi_replace($eventiHTML, [
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
