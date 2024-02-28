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
    $rigaClassifica = get_content_between_markers($adminContent, 'rigaClassifica');
    if (isset($_POST["modifica"]) || isset($_POST["mostraEventi"])) {
        $adminContent = replace_content_between_markers($adminContent, [
            'associaEventi' => get_content_between_markers($adminContent, 'associaEventi'),
            'aggiungi' => $_POST["azione"] == 'modifica' ? '' : get_content_between_markers($adminContent, 'aggiungi'),
            'conferma' => $_POST["azione"] == 'modifica' ? get_content_between_markers($adminContent, 'conferma') : '',
            'punteggi' => get_content_between_markers($adminContent, 'punteggi'),
        ]);
    } else {
        $adminContent = replace_content_between_markers($adminContent, [
            'associaEventi' => '',
            'aggiungi' => get_content_between_markers($adminContent, 'aggiungi'),
        ]);
    }

    // costruisco la lista di option per la selezione del tipo evento
    $legend = (isset($_POST["modifica"]) || isset($_POST["mostraEventi"])) ? 'Modifica classifica' : 'Aggiungi classifica';
    $listaTipoEvento = '';
    $selezioneTipoEventoDefault = isset($_POST["tipoEvento"]) && !isset($_POST["elimina"]) ? '' : ' selected';
    $tipiEvento = $connection->getTipiEvento();
    $optionTipoEvento = get_content_between_markers($adminContent, 'listaTipoEvento');
    $valueTipoEvento = '';
    foreach ($tipiEvento as $tipoEvento) {
        $selezioneTipoEvento = '';
        if (isset($_POST["tipoEvento"]) && !isset($_POST["elimina"])) {
            $selezioneTipoEvento = $_POST["tipoEvento"] == $tipoEvento['Titolo'] ? ' selected' : '';
            $valueTipoEvento = $_POST["tipoEvento"];
        }
        $listaTipoEvento .= multi_replace($optionTipoEvento, [
            '{tipoEvento}' => $tipoEvento['Titolo'],
            '{selezioneTipoEvento}' => $selezioneTipoEvento
        ]);
    }
    if (isset($_POST["modifica"])) {
        $valueDataInizio = $_POST["dataInizio"];
        $valueNuovaDataInizio = ' value="' . $_POST["dataInizio"] . '"';
        $valueNuovaDataFine = ' value="' . $_POST["dataFine"] . '"';
    } elseif (isset($_POST["mostraEventi"])) {
        $valueDataInizio = $_POST["dataInizio"];
        $valueNuovaDataInizio = ' value="' . $_POST["nuovaDataInizio"] . '"';
        $valueNuovaDataFine = ' value="' . $_POST["nuovaDataFine"] . '"';
        $adminContent = multi_replace($adminContent, ['{valueAzione}' => isset($_POST["azione"]) ? $_POST["azione"] : 'aggiungi']);
    } else {
        $valueDataInizio = '';
        $valueNuovaDataInizio = '';
        $valueNuovaDataFine = '';
    }
    $adminContent = multi_replace(replace_content_between_markers($adminContent, [
        'listaTipoEvento' => $listaTipoEvento
    ]), [
        '{legend}' => $legend,
        '{selezioneTipoEventoDefault}' => $selezioneTipoEventoDefault,
        '{valueNuovaDataInizio}' => $valueNuovaDataInizio,
        '{valueNuovaDataFine}' => $valueNuovaDataFine,
        '{valueTipoEvento}' => $valueTipoEvento,
        '{valueDataInizio}' => $valueDataInizio,
        '{valueAzione}' => isset($_POST["modifica"]) ? 'modifica' : 'aggiungi'
    ]);

    // costruisco la lista di eventi selezionabili e selezionati
    $listaEventi = '';
    $nessunEvento = '';
    if (isset($_POST["modifica"]) || isset($_POST["conferma"]) || isset($_POST["punteggi"]) || isset($_POST["mostraEventi"])) {
        $eventiSelezionati = $connection->getEventiSelezionati($_POST["tipoEvento"], $_POST["dataInizio"]);
        $eventiSelezionabili = $connection->getEventiSelezionabili(
            isset($_POST["nuovaDataInizio"]) ? $_POST["nuovaDataInizio"] : $_POST["dataInizio"],
            isset($_POST["nuovaDataFine"]) ? $_POST["nuovaDataFine"] : $_POST["dataFine"]
        );
        $checkEvento = get_content_between_markers($adminContent, 'checkEvento');

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
        $listaEventi = replace_content_between_markers(get_content_between_markers($adminContent, 'listaEventi'), [
            'checkEvento' => $listaEventi
        ]);
    }

    // aggiunta di una nuova classifica
    if (isset($_POST["aggiungi"])) {
        $messaggioForm = get_content_between_markers($adminContent, 'messaggioForm');
        $connection->insert_classifica($_POST["nuovoTipoEvento"], $_POST["nuovaDataInizio"], $_POST["nuovaDataFine"]);
        $eventiSelezionati = isset($_POST["eventi"]) ? $_POST["eventi"] : [];
        $connection->insert_classifica_eventi($_POST["nuovoTipoEvento"], $_POST["nuovaDataInizio"], $eventiSelezionati);
        //gestione errori sql da migliorare
        if (count($connection->get_classifiche($_POST["tipoEvento"], $_POST["dataInizio"])) > 0) {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Classifica aggiunta correttamente"]);
        } else {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore imprevisto"]);
        }
        $messaggiForm = replace_content_between_markers(
            get_content_between_markers($adminContent, 'messaggiForm'),
            ['messaggioForm' => $messaggiForm]
        );
    }

    // modifica di una classifica
    if (isset($_POST["conferma"]) && isset($_POST["azione"]) && $_POST["azione"] == 'modifica') {
        $messaggioForm = get_content_between_markers($adminContent, 'messaggioForm');
        $connection->update_classifica($_POST["tipoEvento"], $_POST["dataInizio"], $_POST["nuovoTipoEvento"], $_POST["nuovaDataInizio"], $_POST["nuovaDataFine"]);
        $eventiSelezionati = $_POST["eventi"];
        $connection->update_classifica_eventi($_POST["nuovoTipoEvento"], $_POST["nuovaDataInizio"], $eventiSelezionati);
        //gestione errori sql da migliorare
        if (count($connection->get_classifiche($_POST["tipoEvento"], $_POST["dataInizio"])) > 0) {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Classifica modificata correttamente"]);
        } else {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore imprevisto"]);
        }
        $messaggiForm = replace_content_between_markers(
            get_content_between_markers($adminContent, 'messaggiForm'),
            ['messaggioForm' => $messaggiForm]
        );
    }

    // eliminazione di una classifica
    if (isset($_POST["elimina"])) {
        $messaggioForm = get_content_between_markers($adminContent, 'messaggioForm');
        $connection->delete_classifica($_POST["tipoEvento"], $_POST["dataInizio"]);
        //gestione errori sql da migliorare
        if (count($connection->get_classifiche($_POST["tipoEvento"], $_POST["dataInizio"])) == 0) {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Classifica eliminata correttamente"]);
        } else {
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Errore imprevisto"]);
        }
        $messaggiForm = replace_content_between_markers(
            get_content_between_markers($adminContent, 'messaggiForm'),
            ['messaggioForm' => $messaggiForm]
        );
    }

    // visualizzazione classifiche presenti nel db
    $classifiche = $connection->get_classifiche();
    $righeClassifiche = '';
    foreach ($classifiche as $classifica) {
        $righeClassifiche .= multi_replace($rigaClassifica, [
            '{tipoEvento}' => $classifica['TipoEvento'],
            '{dataInizio}' => date_format(date_create($classifica['DataInizio']), 'd/m/y'),
            '{dataFine}' => date_format(date_create($classifica['DataFine']), 'd/m/y'),
            '{valueTipoEvento}' => $classifica['TipoEvento'],
            '{valueDataInizio}' => date_format(date_create($classifica['DataInizio']), 'Y-m-d'),
            '{valueDataFine}' => date_format(date_create($classifica['DataFine']), 'Y-m-d')
        ]);
    }

    // da completare logica per gestione punteggi

    $adminContent = multi_replace(replace_content_between_markers($adminContent, [
        'listaEventi' => $listaEventi,
        'nessunEvento' => $nessunEvento,
        'rigaClassifica' => $righeClassifiche,
        'messaggiForm' => $messaggiForm
    ]), [
        '{selezioneTipoEventoDefault}' => $selezioneTipoEventoDefault,
        '{legend}' => $legend
    ]);

    $content = multi_replace(replace_content_between_markers($content, [
        'adminMenu' => $adminMenu
    ]), [
        '{adminContent}' => $adminContent,
    ]);

    $connection->closeDBConnection();
} else {
    header("location: ../errore500.php");
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
    '{onload}' => $onload,
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin
]);
