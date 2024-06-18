<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");

$title = 'Evento {titoloEvento} &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina di presentazione di un evento organizzato dal collettivo rap Restraining Stirpe Crew.';
$keywords = 'restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);

$content = '';
$onload = 'init_evento()';
$logout = '';
$classList = '';
$logo = get_content_between_markers($paginaHTML, 'logoLink');

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

$eventoId = $_GET['id'];
if ($connectionOk) {
    $evento = $connection->get_evento($eventoId);
    $classificaEvento = $connection->get_classifica_evento($eventoId);
    $classifiche = $connection->get_classifiche();
    $connection->close_DB_connection();
    if ($evento == null) {
        header("location: errore404.php");
        exit;
    } else {
        [$tipoEvento, $titolo, $descrizione, $data, $ora, $luogo, $locandina] = array_values($evento);
        
        $title = str_replace('{titoloEvento}', strip_tags($titolo), $title);

        $content = file_get_contents("template/evento.html");

        // Creazione della locandina dell'evento
        $locandinaEvento = '';
        if ($locandina != null) {
            $eventoConLocandinaTemplate = get_content_between_markers($content, 'conLocandina');
            $locandinaEvento = multi_replace($eventoConLocandinaTemplate, [
                '{locandina}' => $locandina
            ]);
        } else {
            $locandinaEvento = get_content_between_markers($content, 'noLocandina');
        }

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
        $classificaGenerale = null;
        foreach ($classifiche as $classifica) {
            if ($classifica['TipoEvento'] == $tipoEvento && $classifica['DataInizio'] <= $data && $classifica['DataFine'] >= $data) {
                $classificaGenerale = $classifica;
                break;
            }
        }
        if ($tipoEvento != null) {
            $tipoEventoTemplate = get_content_between_markers($content, 'sezioneTipoEvento');
            $stagioneEvento .= multi_replace($tipoEventoTemplate, [
                '{tipoEvento}' => $tipoEvento
            ]);
        }
        if ($classificaGenerale != null) {
            $classificaEventoTemplate = get_content_between_markers($content, 'classifica');
            $stagioneEvento .= multi_replace($classificaEventoTemplate, [
                '{dataInizioClassifica}' => date_format(date_create($classificaGenerale['DataInizio']), 'Y-m-d'),
                '{dataInizioClassificaVisualizzata}' => date_format_ita($classificaGenerale['DataInizio']),
                '{dataFineClassifica}' => date_format(date_create($classificaGenerale['DataFine']), 'Y-m-d'),
                '{dataFineClassificaVisualizzata}' => date_format_ita($classificaGenerale['DataFine']),
                '{idClassifica}' => $classificaGenerale['Id'],
                '{titoloClassifica}' => $classificaGenerale['Titolo']
            ]);
        }

        // Creazione della classifica dell'evento
        $classificaEventoHTML = '';
        if ($classificaEvento != null) {
            $classificaTemplate = get_content_between_markers($content, 'classificaEvento');
            $rigaTabellaTemplate = get_content_between_markers($content, 'rigaClassifica');
            $righeTabella = '';
            foreach ($classificaEvento as $riga) {
                $righeTabella .= multi_replace($rigaTabellaTemplate, [
                    '{ranking}' => $riga['ranking'],
                    '{freestyler}' => $riga['partecipante'],
                    '{punti}' => $riga['punti']
                ]);
            }
            $classificaEventoHTML = replace_content_between_markers($classificaTemplate, [
                'rigaClassifica' => $righeTabella
            ]);
        }

        $content = multi_replace(replace_content_between_markers($content, [
            'locandinaEvento' => $locandinaEvento,
            'stagioneEvento' => $stagioneEvento,
            'descrizioneEvento' => $descrizioneEvento,
            'classificaEvento' => $classificaEventoHTML
        ]), [
            '{titolo}' => $titolo,
            '{data}' => date_format(date_create($data), 'Y-m-d'),
            '{dataVisualizzata}' => date_format_ita($data),
            '{ora}' => date_format(date_create($ora), 'G:i'),
            '{luogo}' => $luogo,
            '{locandina}' => $locandina,
            '{titoloStile}' => str_replace(' ', '_', $titolo)
        ]);
        $breadcrumbs = multi_replace($breadcrumbs, [
            '{id}' => $eventoId,
            '{evento}' => $titolo
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
    'logo' => $logo,
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu,
    'logout' => $logout
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => $content,
    '{onload}' => $onload,
    '{classList}' => $classList
]);
