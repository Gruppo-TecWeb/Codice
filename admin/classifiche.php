<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/template-pagina.html");
$content = file_get_contents("../template/admin/template-pagina-admin.html");
$adminContent = file_get_contents("../template/admin/classifiche.html");

$title = 'Tipi Evento &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = '';
$keywords = '';
$percorso = '../';
$percorsoAdmin = '';
$menu = get_menu($pageId, $percorso);
$adminMenu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId, $percorso);
$onload = '';
$logout = '';
$messaggiForm = '';

if (!isset($_SESSION["login"])) {
    header("location: login.php");
}

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    if (isset($_GET["modifica"]) || isset($_GET["conferma"]) || isset($_GET["aggiungi"]) || isset($_GET["punteggi"]) || isset($_GET["mostraEventi"])) {
        $adminContent = replace_content_between_markers($adminContent, [
            'associaEventi' => get_content_between_markers($adminContent, 'associaEventi'),
            'aggiungi' => '',
            'conferma' => get_content_between_markers($adminContent, 'conferma'),
            'punteggi' => get_content_between_markers($adminContent, 'punteggi'),]);
    } else {
        $adminContent = replace_content_between_markers($adminContent, [
            'associaEventi' => '',
            'aggiungi' => get_content_between_markers($adminContent, 'aggiungi'),]);
    }
    
    // costruisco la lista di option per la selezione del tipo evento
    $legend = isset($_GET["modifica"]) ? 'Modifica classifica' : 'Aggiungi classifica';
    $listaTipoEvento = '';
    $selezioneTipoEventoDefault = isset($_GET["tipoEvento"]) && !isset($_GET["elimina"]) ? '' : ' selected';
    $valueDataInizio = isset($_GET["dataInizio"]) ? ' value="' . $_GET["dataInizio"] . '"' : '';
    $valueDataFine = isset($_GET["dataFine"]) ? ' value="' . $_GET["dataFine"] . '"' : '';
    $tipiEvento = $connection->getTipiEvento();
    $optionTipoEvento = get_content_between_markers($adminContent, 'listaTipoEvento');
    foreach ($tipiEvento as $tipoEvento) {
        $selezioneTipoEvento = '';
        if (isset($_GET["tipoEvento"]) && !isset($_GET["elimina"])) {
            $selezioneTipoEvento = $_GET["tipoEvento"] == $tipoEvento['Titolo'] ? ' selected' : '';
        }
        $listaTipoEvento .= multi_replace($optionTipoEvento, [
            '{tipoEvento}' => $tipoEvento['Titolo'],
            '{selezioneTipoEvento}' => $selezioneTipoEvento
        ]);
    }

    // costruisco la lista di eventi selezionabili e selezionati
    $listaEventi = '';
    $nessunEvento = '';
    if (isset($_GET["mostraEventi"])) {
        $eventiSelezionati = $connection->getEventiSelezionati($_GET["tipoEvento"], $_GET["dataInizio"]);
        $eventiSelezionabili = $connection->getEventiSelezionabili($_GET["dataInizio"], $_GET["dataFine"]);
        $checkEvento = get_content_between_markers($adminContent, 'listaEventi');

        foreach ($eventiSelezionati as $eventoSelezionato) {
            $listaEventi .= multi_replace($checkEvento, [
                '{idEvento}' => $eventoSelezionato['Id'],
                '{titoloEvento}' => $eventoSelezionato['Titolo'],
                '{dataEvento}' => date_format(date_create($eventoSelezionato['Data']), 'Y-m-d'),
                '{dataVisualizzataEvento}' => date_format(date_create($eventoSelezionato['Data']), 'd/m/y'),
                '{eventoChecked}' => ' checked'
            ]);
        }
        foreach ($eventiSelezionabili as $eventoSelezionabile) {
            $listaEventi .= multi_replace($checkEvento, [
                '{idEvento}' => $eventoSelezionabile['Id'],
                '{titoloEvento}' => $eventoSelezionabile['Titolo'],
                '{dataEvento}' => date_format(date_create($eventoSelezionabile['Data']), 'Y-m-d'),
                '{dataVisualizzataEvento}' => date_format(date_create($eventoSelezionabile['Data']), 'd/m/y'),
                '{eventoChecked}' => ''
            ]);
        }

        if ($listaEventi == '') {
            $nessunEvento = get_content_between_markers($adminContent, 'nessunEvento');
        }
    }

    // elimino una classifica
    if (isset($_GET["elimina"])) {
        $messaggioForm = get_content_between_markers($adminContent, 'messaggioForm');
        $connection->delete_classifica($_GET["tipoEvento"], $_GET["dataInizio"]);
        if (count($connection->get_classifica($_GET["tipoEvento"], $_GET["dataInizio"])) == 0) {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Classifica eliminata correttamente"]);
        } else {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore imprevisto"]);
        }
        $messaggiForm = replace_content_between_markers(
            get_content_between_markers($adminContent, 'messaggiForm'), ['messaggioForm' => $messaggiForm]);
    }

    // visualizzazione classificche presenti nel db
    $classifiche = $connection->get_classifiche();
    $rigaClassifica = get_content_between_markers($adminContent, 'rigaClassifica');
    $righeClassifiche = '';
    foreach ($classifiche as $classifica) {
        $righeClassifiche .= multi_replace($rigaClassifica, [
            '{tipoEvento}' => $classifica['TipoEvento'],
            '{dataInizio}' => date_format(date_create($classifica['DataInizio']), 'd/m/y'),
            '{dataFine}' => date_format(date_create($classifica['DataFine']), 'd/m/y'),
            '{valueTipoEvento}' => $classifica['TipoEvento'],
            '{valueDataInizio}' => date_format(date_create($classifica['DataInizio']), 'Y-m-d')
        ]);
    }

    // da completare logica per creazione classifica

    // da completare logica per gestione punteggi

    // da completare logica per modifica classifica

    $adminContent = multi_replace(replace_content_between_markers($adminContent, [
        'listaTipoEvento' => $listaTipoEvento,
        'listaEventi' => $listaEventi,
        'nessunEvento' => $nessunEvento,
        'rigaClassifica' => $righeClassifiche]), [
            '{selezioneTipoEventoDefault}' => $selezioneTipoEventoDefault,
            '{legend}' => $legend,
            '{valueDataInizio}' => $valueDataInizio,
            '{valueDataFine}' => $valueDataFine]);

    $connection->closeDBConnection();
} else {
    header("location: ../errore500.php");
}

if (isset($_SESSION["login"])) {
    $logout = get_content_between_markers($paginaHTML, 'logout');
}

echo multi_replace(replace_content_between_markers(
    multi_replace(
        replace_content_between_markers($paginaHTML, [
            'breadcrumbs' => $breadcrumbs,
            'menu' => $menu,
            'logout' => $logout
        ]),
        [
            '{title}' => $title,
            '{description}' => $description,
            '{keywords}' => $keywords,
            '{pageId}' => $pageId,
            '{content}' => $content,
            '{onload}' => $onload,
            '{percorso}' => $percorso,
            '{adminContent}' => replace_content_between_markers($adminContent, ['messaggiForm' => $messaggiForm])
        ]
    ),
    [
        'adminMenu' => $adminMenu
    ]
), ['{percorsoAdmin}' => $percorsoAdmin]);
