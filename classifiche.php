<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$content = file_get_contents("template/classifiche.html");
$style = 'classifiche.css';

$title = 'Classifiche &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Classifiche attuali sulla base dei punteggi ottenuti durante le battle di freestyle rap degli eventi Fungo e Micelio.';
$keywords = 'classifiche, fungo, micelio, freestyle, rap, freestyle rap, battle';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$logout = '';
$classList = '';
$logo = get_content_between_markers($paginaHTML, 'logoLink');

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

if ($connectionOk) {
    $classifiche = '';
    $validIdClassifica = isset($_GET['classifica']) ? validate_input($_GET['classifica']) : "";
    $classifica = $connection->get_classifica($validIdClassifica);
    if ($classifica != null)
        $classifica = replace_lang_dictionary($classifica);
    if (((isset($_GET['classifica']) && $_GET['classifica'] != "") && $validIdClassifica == "") ||
        ((isset($_GET['classifica']) && $_GET['classifica'] != "") && $classifica == null)
    ) {
        header("location: classifiche.php?errore=invalid");
        exit;
    }

    if (isset($_GET['errore'])) {
        header("location: errore404.php");
        exit;
    }

    if (isset($_GET['reset'])) {
        header("location: classifiche.php");
        exit;
    }

    $resultClassifiche = $connection->get_classifiche();
    $resultClassifiche = replace_lang_dictionary($resultClassifiche);
    if (!isset($_GET['classifica'])) {
        $classifica = $connection->get_classifica_corrente();
        $classifica = replace_lang_dictionary($classifica);
        if ($classifica == null || $connection->get_punteggi_classifica($classifica['TipoEvento'], $classifica['DataInizio'], $classifica['DataFine']) == null) {
            // ordino le classifiche per data di fine in modo decrescente
            usort($resultClassifiche, function ($a, $b) {
                return strtotime($b['DataFine']) - strtotime($a['DataFine']);
            });
            // per ogni classifica passata controllo se ci sono punteggi
            foreach ($resultClassifiche as $resultClassifica) {
                $punteggiClassifica = $connection->get_punteggi_classifica($resultClassifica['TipoEvento'], $resultClassifica['DataInizio'], $resultClassifica['DataFine']);
                if ($punteggiClassifica != null) {
                    $classifica = $resultClassifica;
                    break;
                }
            }
        }
    }

    // creo la lista delle classifiche per la scelta dall'archivio
    foreach ($resultClassifiche as $resultClassifica) {
        $idClassifica = $resultClassifica['Id'];
        $titoloClassifica = $resultClassifica['Titolo'];
        $tipoEvento = $resultClassifica['TipoEvento'];
        $selected = $idClassifica == $classifica['Id'] ? ' selected' : '';

        $option = get_content_between_markers($content, 'listaClassifiche');
        $classifiche .= multi_replace($option, [
            '{idClassifica}' => $idClassifica,
            '{selezioneClassifica}' => $selected,
            '{opzioneClassifica}' => $titoloClassifica
        ]);
    }

    // creo le classifiche per la visualizzazione
    if ($classifica != null) {
        $tipoEvento = $classifica['TipoEvento'];
        $descrizioneEvento = $connection->get_tipo_evento($tipoEvento)['Descrizione'];
        $descrizioneEvento = replace_lang($descrizioneEvento);
        $punteggiClassifica = $connection->get_punteggi_classifica($tipoEvento, $classifica['DataInizio'], $classifica['DataFine']);
        $titoloClassifica = $classifica['Titolo'];
        if ($punteggiClassifica != null) {
            $classificaHTML = get_content_between_markers($content, 'tabellaClassifica');
            $tabella = multi_replace($classificaHTML, [
                '{tipoEvento}' => $tipoEvento,
                '{desTipoEvento}' => $descrizioneEvento,
                '{titoloClassifica}' => $titoloClassifica
            ]);
            $righe = '';
            $rigaHTML = get_content_between_markers($content, 'rigaClassifica');
            foreach ($punteggiClassifica as $riga) {
                $righe .= multi_replace($rigaHTML, [
                    '{ranking}' => $riga['ranking'],
                    '{freestyler}' => $riga['partecipante'],
                    '{punti}' => $riga['punti']
                ]);
            }
            $content = replace_content_between_markers($content, [
                'listaClassificheDefault' => '',
                'listaClassifiche' => $classifiche,
                'tabellaClassifica' => $tabella,
                'rigaClassifica' => $righe,
                'nessunaClassifica' => '',
                'nessunPunteggio' => ''
            ]);
        } else {
            $content = replace_content_between_markers($content, [
                'listaClassificheDefault' => '',
                'listaClassifiche' => $classifiche,
                'tabellaClassifica' => '',
                'nessunaClassifica' => '',
                'nessunPunteggio' => get_content_between_markers($content, 'nessunPunteggio')
            ]);
        }
    } else {
        $content = replace_content_between_markers($content, [
            'listaClassificheDefault' => get_content_between_markers($content, 'listaClassificheDefault'),
            'listaClassifiche' => '',
            'tabellaClassifica' => '',
            'nessunaClassifica' => get_content_between_markers($content, 'nessunaClassifica'),
            'nessunPunteggio' => ''
        ]);
    }

    $connection->close_DB_connection();
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
