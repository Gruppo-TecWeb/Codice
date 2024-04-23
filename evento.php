<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");

$title = 'Evento &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina di presentazione di un evento organizzato dal collettivo rap Restraining Stirpe Crew.';
$keywords = 'restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);

$content = '';
$onload = 'init_evento()';
$logout = '';

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

$eventoId = $_GET['id'];
if ($connectionOk) {
    $evento = $connection->get_evento($eventoId);
    $classifica = $connection->get_classifica_evento($eventoId);
    $connection->close_DB_connection();
    if ($evento == null) {
        $content .= '<p>Evento non trovato</p>';
    } else {
        [$tipoEvento, $titolo, $descrizione, $data, $ora, $luogo, $locandina] = array_values($evento);

        $content = file_get_contents("template/evento.html");

        // Creazione della descrizione dell'evento
        $descrizioneEvento = '';
        if ($descrizione != null) {
            $descrizioneEventoTemplate = get_content_between_markers($content, 'descrizioneEvento');
            $descrizioneEvento = multi_replace($descrizioneEventoTemplate, [
                '{descrizione}' => $descrizione
            ]);
        }

        // Creazione della stagione dell'evento
        $stagioneEvento = '';
        if ($tipoEvento  != null) {
            $stagioneEventoTemplate = get_content_between_markers($content, 'stagioneEvento');
            $stagioneEvento = multi_replace($stagioneEventoTemplate, [
                '{tipoEvento}' => $tipoEvento
            ]);
        }

        // Creazione della classifica dell'evento
        $classificaEvento = '';
        if ($classifica != null) {
            $classificaTemplate = get_content_between_markers($content, 'classificaEvento');
            $rigaTabellaTemplate = get_content_between_markers($content, 'rigaClassifica');
            $righeTabella = '';
            foreach ($classifica as $riga) {
                $righeTabella .= multi_replace($rigaTabellaTemplate, [
                    '{ranking}' => $riga['ranking'],
                    '{freestyler}' => $riga['partecipante'],
                    '{punti}' => $riga['punti']
                ]);
            }
            $classificaEvento = replace_content_between_markers($classificaTemplate, [
                'rigaClassifica' => $righeTabella
            ]);
        }

        $content = multi_replace(replace_content_between_markers($content, [
            'stagioneEvento' => $stagioneEvento,
            'descrizioneEvento' => $descrizioneEvento,
            'classificaEvento' => $classificaEvento
        ]), [
            '{titolo}' => $titolo,
            '{data}' => date_format(date_create($data), 'd/m/Y'),
            '{ora}' => date_format(date_create($ora), 'G:i'),
            '{luogo}' => $luogo,
            '{locandina}' => $locandina,
        ]);
        $breadcrumbs = multi_replace($breadcrumbs, [
            '{id}' => $eventoId,
            '{evento}' => 'Evento',
        ]);
    }
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
