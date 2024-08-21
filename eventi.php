<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/eventi.html");
$style = 'eventi.css';

$title = 'Eventi &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina che presenta gli eventi organizzati dal collettivo rap Restraining Stirpe Crew.';
$keywords = 'micelio, fungo, meal the mic, hip hop night, freestyle, freestyle rap, rap, battle, live, dj set';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$logout = '';
$classList = '';
$logo = get_content_between_markers($paginaHTML, 'logoLink');

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

if ($connectionOk) {
    $eventi_per_pagina = 12;
    $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
    $tipoEvento = isset($_GET['tipoEvento']) ? $_GET['tipoEvento'] : '';
    $tipoEvento = validate_input($tipoEvento);
    $data = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');
    $data = validate_input($data);

    // controllo sui dati in GET
    if (isset($_GET['tipoEvento']) &&  $_GET['tipoEvento'] != "" &&  $_GET['tipoEvento'] != "Altri eventi" && !in_array($tipoEvento, array_column($connection->get_tipi_evento(), 'Titolo'))) {
        header("location: errore404.php");
        exit;
    }
    if ($data != '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
        header("location: errore404.php");
        exit;
    }

    $lista_eventi_array = $connection->get_lista_eventi($data, $tipoEvento);
    $eventi_senza_tipo = $connection->get_lista_eventi("", "Altri eventi");
    $lista_tipi_evento_array = $connection->get_tipi_evento();
    $oldest_date = $lista_eventi_array == null ? $connection->get_oldest_date() : '';
    if($oldest_date == null) $oldest_date = '';
    $connection->close_DB_connection();

    for ($i = 0; $i < count($lista_eventi_array); $i++) {
        $lista_eventi_array[$i] = replace_lang_array($lista_eventi_array[$i]);
    }
    $eventi_senza_tipo = replace_lang_array($eventi_senza_tipo);
    $lista_tipi_evento_array = replace_lang_array($lista_tipi_evento_array);

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
        $pages = $pagina > 1 ? multi_replace($notCurrentPageTemplate, [
            '{numeroPagina}' => $pagina - 1,
            '{data}' => $data,
            '{tipoEvento}' => $tipoEvento,
            '{messaggio}' => 'Precedente',
            '{classe}' => ''
        ]) : '';
        for ($i = 1; $i <= $numero_pagine; $i++) {
            if ($i == $pagina) {
                $pages .= str_replace('{numeroPagina}', $i, $currentPageTemplate);
            } else if ($i == 1 || $i == $numero_pagine || ($i >= $pagina - 2 && $i <= $pagina + 2)) {
                $pages .= multi_replace($notCurrentPageTemplate, [
                    '{numeroPagina}' => $i,
                    '{data}' => $data,
                    '{tipoEvento}' => $tipoEvento,
                    '{messaggio}' => $i,
                    '{classe}' => 'number'
                ]);
            } else if ($i == $pagina - 3 || $i == $pagina + 3) {
                $pages .= multi_replace(get_content_between_markers($paginationTemplate, 'ellipsis'), [
                    '{data}' => $data,
                    '{tipoEvento}' => $tipoEvento
                ]);
            }
        }
        $pages .= $pagina < $numero_pagine ? multi_replace($notCurrentPageTemplate, [
            '{numeroPagina}' => $pagina + 1,
            '{data}' => $data,
            '{tipoEvento}' => $tipoEvento,
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
    $anyoneSelected = false;
    foreach ($lista_tipi_evento_array as $tipo_evento) {
        if ($tipo_evento['Titolo'] == $tipoEvento) {
            $selected = ' selected';
            $anyoneSelected = true;
        } else {
            $selected = '';
        }
        $lista_tipi_evento_string .= multi_replace($option, [
            '{tipoEvento}' => $tipo_evento['Titolo'],
            '{selezioneTipoEvento}' => $selected
        ]);
    }
    if ($eventi_senza_tipo != null) {
        if ($tipoEvento == 'Altri eventi') {
            $selected = ' selected';
            $anyoneSelected = true;
        } else {
            $selected = '';
        }
        $lista_tipi_evento_string .= multi_replace($option, [
            '{tipoEvento}' => 'Altri eventi',
            '{selezioneTipoEvento}' => $selected
        ]);
    }

    // Costruzione della lista di eventi
    $offset = ($pagina - 1) * $eventi_per_pagina;
    $lista_eventi_array = array_slice($lista_eventi_array, $offset, $eventi_per_pagina);
    $lista_eventi_string = '';
    $messaggio_lista_eventi = '';
    if ($lista_eventi_array == null) {
        $messaggioListaEventiTemplate = get_content_between_markers($content, 'messaggioListaEventi');
        if ($tipoEvento != '' || $data != '') {
            $messaggio = 'Nessun evento corrisponde ai criteri di ricerca';
        } else {
            $messaggio = 'Non ci sono eventi in programma';
        }
        if ($oldest_date != '') {
            $olderDateTemplate = get_content_between_markers($messaggioListaEventiTemplate, 'older_date');
            $olderDateTemplate = multi_replace($olderDateTemplate, [
                '{dataEventiPassati}' => $oldest_date
            ]);
            $lista_eventi_string .= replace_content_between_markers(multi_replace($messaggioListaEventiTemplate, [
                '{messaggio}' => $messaggio,
                '{dataEventiPassati}' => $oldest_date
            ]), [
                'older_date' => $olderDateTemplate
            ]);
        } else {
            $messaggioListaEventiTemplate = replace_content_between_markers(multi_replace($messaggioListaEventiTemplate, [
                '{messaggio}' => 'Non ci sono ancora eventi disponibili, non siamo nel flow giusto per ora.'
            ]), [
                'older_date' => ''
            ]);
            $lista_eventi_string .= $messaggioListaEventiTemplate;
        }
    } else {
        $lista_eventi_string .= get_content_between_markers($content, 'listaEventi');
        $eventoImmagineTemplate = get_content_between_markers($content, 'eventoImmagine');
        $eventoSenzaImmagineTemplate = get_content_between_markers($content, 'eventoNoImmagine');
        $eventi_string = '';
        foreach ($lista_eventi_array as $evento) {
            $eventi_string .= multi_replace($evento['Locandina'] != NULL ? $eventoImmagineTemplate : $eventoSenzaImmagineTemplate, [
                '{idEvento}' => $evento['Id'],
                '{valueDataEvento}' => $evento['Data'],
                '{dataEvento}' => date_format_ita($evento['Data']),
                '{locandinaEvento}' => $evento['Locandina'],
                '{titoloEvento}' => $evento['Titolo'],
                '{titoloEventoStile}' => str_replace(' ', '_', $evento['Titolo'])
            ]);
        }
        $lista_eventi_string = replace_content_between_markers($lista_eventi_string, [
            'eventoElement' => $eventi_string,
            'messaggioListaEventi' => ''
        ]);
    }

    $messaggioFiltri = $data == date('Y-m-d') ? 'i prossimi eventi' : '';
    $messaggioFiltri .= $data != date('Y-m-d') ? 'eventi a partire dalla data: ' . multi_replace(get_content_between_markers($content, 'messaggioFiltri'), [
        '{valueDataEvento}' => $data,
        '{dataEvento}' => date_format_ita($data)

    ]) : '';
    $messaggioFiltri .= $tipoEvento != '' ? ' di tipo: ' . $tipoEvento : '';

    $content = multi_replace(
        replace_content_between_markers($content, [
            'listaTipiEvento' => $lista_tipi_evento_string,
            'listaEventi' => $lista_eventi_string,
            'pagination' => $pagination,
            'navRisultatiEventi' => $navRisultatiEventi,
            'messaggioFiltri' => $messaggioFiltri
        ]),
        [
            '{selezioneTipoEventoDefault}' => $anyoneSelected ? '' : ' selected',
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
    'logo' => $logo,
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu,
    'logout' => $logout
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{style}' => $style,
    '{content}' => $content,
    '{onload}' => $onload,
    '{classList}' => $classList
]);
