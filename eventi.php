<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/eventi.html");

$title = 'Eventi &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina che presenta gli eventi organizzati dal collettivo rap Restraining Stirpe Crew.';
$keywords = 'micelio, fungo, meal the mic, hip hop night, freestyle, freestyle rap, rap, battle, live, dj set';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$logout = '';

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

if ($connectionOk) {
    $eventi_per_pagina = 12;
    $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
    $tipoEvento = isset($_GET['tipoEvento']) ? $_GET['tipoEvento'] : '';
    $data = isset($_GET['data']) ? $_GET['data'] : '';

    $lista_eventi_array = $connection->get_lista_eventi($data, $tipoEvento);
    $lista_tipi_evento_array = $connection->get_tipi_evento();
    $oldest_date = $lista_eventi_array == null ? $connection->get_oldest_date() : '';
    $connection->close_DB_connection();

    $numero_pagine = ceil(count($lista_eventi_array) / $eventi_per_pagina);

    // Costruzione del messaggio di risultati
    $navRisultatiEventi = '';
    $risultatiEventi = '';
    if ($numero_pagine > 0) {
        $navRisultatiEventiTemplate = get_content_between_markers($content, 'navRisultatiEventi');
        $risultatiEventiTemplate = get_content_between_markers($navRisultatiEventiTemplate, 'risultatiEventi');
        $risultatiEventi .= multi_replace($risultatiEventiTemplate, [
            '{pagina}' => $pagina,
            '{numeroPagine}' => $numero_pagine,
            '{risultati}' => count($lista_eventi_array)
        ]);
        $navRisultatiEventi .= replace_content_between_markers($navRisultatiEventiTemplate, [
            'risultatiEventi' => $risultatiEventi
        ]);
    }

    // Costruzione della paginazione
    $pagination = '';
    $paginationTemplate = get_content_between_markers($content, 'pagination');
    if ($numero_pagine > 1) {
        $currentPageTemplate = get_content_between_markers($paginationTemplate, 'currentPage');
        $notCurrentPageTemplate = get_content_between_markers($paginationTemplate, 'notCurrentPage');
        $data_encoded = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        $titolo_encoded = htmlspecialchars($titolo, ENT_QUOTES, 'UTF-8');
        $pages = $pagina > 1 ? multi_replace($notCurrentPageTemplate, [
            '{numeroPagina}' => $pagina - 1,
            '{data}' => $data_encoded,
            '{titolo}' => $titolo_encoded,
            '{messaggio}' => 'Precedente',
            '{classe}' => ''
        ]) : '';
        for ($i = 1; $i <= $numero_pagine; $i++) {
            if ($i == $pagina) {
                $pages .= str_replace('{numeroPagina}', $i, $currentPageTemplate);
            } else if ($i == 1 || $i == $numero_pagine || ($i >= $pagina - 2 && $i <= $pagina + 2)) {
                $pages .= multi_replace($notCurrentPageTemplate, [
                    '{numeroPagina}' => $i,
                    '{data}' => $data_encoded,
                    '{titolo}' => $titolo_encoded,
                    '{messaggio}' => $i,
                    '{classe}' => 'number'
                ]);
            } else if ($i == $pagina - 3 || $i == $pagina + 3) {
                $pages .= multi_replace(get_content_between_markers($paginationTemplate, 'ellipsis'), [
                    '{data}' => $data_encoded,
                    '{titolo}' => $titolo_encoded
                ]);
            }
        }
        $pages .= $pagina < $numero_pagine ? multi_replace($notCurrentPageTemplate, [
            '{numeroPagina}' => $pagina + 1,
            '{data}' => $data,
            '{titolo}' => $titolo,
            '{messaggio}' => 'Successiva',
            '{classe}' => ''
        ]) : '';
        $pagination = replace_content_between_markers($paginationTemplate, [
            'pages' => $pages,
            'risultatiEventi' => $risultatiEventi
        ]);
    }

    // Costruzione delle liste di tipo evento
    $lista_tipi_evento_string = '';
    $option = get_content_between_markers($content, 'listaTipiEvento');
    foreach ($lista_tipi_evento_array as $tipoEvento) {
        $selected = ($tipoEvento['Titolo'] == $titolo) ? ' selected' : '';
        $lista_tipi_evento_string .= multi_replace($option, [
            '{tipoEvento}' => $tipoEvento['Titolo'],
            '{selezioneEvento}' => $selected
        ]);
    }

    // Costruzione della lista di eventi
    $offset = ($pagina - 1) * $eventi_per_pagina;
    $lista_eventi_array = array_slice($lista_eventi_array, $offset, $eventi_per_pagina);
    $lista_eventi_string = '';
    if ($lista_eventi_array == null) {
        $messaggioListaEventiTemplate = get_content_between_markers($content, 'messaggioListaEventi');
        if ($titolo != '' || $data != '') {
            $messaggio = 'Nessun evento corrisponde ai criteri di ricerca';
        } else {
            $messaggio = 'Non ci sono eventi in programma';
        }
        $lista_eventi_string .= multi_replace($messaggioListaEventiTemplate, [
            '{messaggio}' => $messaggio,
            '{dataEventiPassati}' => $oldest_date
        ]);
    } else {
        $lista_eventi_string .= get_content_between_markers($content, 'listaEventi');
        $eventoTemplate = get_content_between_markers($content, 'eventoElement');
        $eventi_string = '';
        foreach ($lista_eventi_array as $evento) {
            $eventi_string .= multi_replace($eventoTemplate, [
                '{idEvento}' => $evento['Id'],
                '{valueDataEvento}' => $evento['Data'],
                '{dataEvento}' => date_format_ita($evento['Data']),
                '{locandinaEvento}' => $evento['Locandina'],
                '{titoloEvento}' => $evento['Titolo']
            ]);
        }
        $lista_eventi_string = replace_content_between_markers($lista_eventi_string, [
            'eventoElement' => $eventi_string,
            'messaggioListaEventi' => ''
        ]);
    }

    $messaggioFiltri = $data == '' ? 'i prossimi eventi' : '';
    $messaggioFiltri .= $data != '' ? 'eventi a partire dalla data: ' . multi_replace(get_content_between_markers($content, 'messaggioFiltri'), [
        '{valueDataEvento}' => $data,
        '{dataEvento}' => date_format_ita($data)
    
    ]) : '';
    $messaggioFiltri .= $titolo != '' ? ' di tipo: ' . $titolo : '';

    $content = multi_replace(
        replace_content_between_markers($content, [
            'listaTipiEvento' => $lista_tipi_evento_string,
            'listaEventi' => $lista_eventi_string,
            'pagination' => $pagination,
            'navRisultatiEventi' => $navRisultatiEventi,
            'messaggioFiltri' => $messaggioFiltri
        ]),
        [
            '{data}' => $data,
        ]
    );
} else {
    header("location: errore500.php");
    exit;
}

if (isset($_SESSION["login"])) {
    $logout = get_content_between_markers($paginaHTML, 'logout');
}

echo multi_replace(replace_content_between_markers($paginaHTML, [
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu,
    'logout' => $logout
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => $content,
    '{onload}' => $onload
]);
