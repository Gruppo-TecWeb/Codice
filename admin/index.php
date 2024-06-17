<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/admin/template-admin.html");
$content = file_get_contents("../template/admin/index.html");

$title = 'Area Admin &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina principale dell\'area di amministrazione del sito.';
$keywords = 'amministrazione, admin, restraining stirpe, freestyle, freestyle rap, rap, battle, live, dj set, micelio, fungo';
$menu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$classList = '';

if (!isset($_SESSION["login"])) {
    header("location: ../login.php");
    exit;
}

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

if ($connectionOk) {
    $dashboardAdminHTML = get_content_between_markers($content, 'dashboardAdmin');
    $dashboardAdmin = '';

    // creazione dashboard admin
    if (($connection->get_utente_by_username($_SESSION["username"]))["TipoUtente"] == 'A') {
        $prossimoEventoHTML = get_content_between_markers($dashboardAdminHTML, 'prossimoEvento');
        $eventiProgrammatiHTML = get_content_between_markers($dashboardAdminHTML, 'eventiProgrammati');
        $punteggiMancantiHTML = get_content_between_markers($dashboardAdminHTML, 'punteggiMancanti');
        $nessunEventoProgrammatoHTML = get_content_between_markers($dashboardAdminHTML, 'nessunEventoProgrammato');
        $nessunPunteggioMancanteHTML = get_content_between_markers($dashboardAdminHTML, 'nessunPunteggioMancante');

        // dashboard prossimo evento
        $prossimiEventi = $connection->get_lista_eventi(date('Y-m-d'));
        if (count($prossimiEventi) > 0) {
            $locandinaHTML = '';
            if ($prossimiEventi[0]['Locandina'] != null) {
                $locandinaHTML = get_content_between_markers($prossimoEventoHTML, 'locandinaHTML');
                $locandinaHTML = multi_replace($locandinaHTML, [
                    '{locandina}' => $prossimiEventi[0]['Locandina']
                ]);
            }
            $prossimoEventoHTML = multi_replace($prossimoEventoHTML, [
                '{titoloEvento}' => $prossimiEventi[0]['Titolo'],
                '{data}' => date_format(date_create($prossimiEventi[0]['Data']), 'Y-m-d'),
                '{dataVisualizzata}' => date_format_ita($prossimiEventi[0]['Data']),
                '{ora}' => date_format(date_create($prossimiEventi[0]['Ora']), 'H:i'),
                '{luogo}' => $prossimiEventi[0]['Luogo'],
                '{descrizione}' => $prossimiEventi[0]['Descrizione'],
                '{tipoEvento}' => $prossimiEventi[0]['TipoEvento'],
                '{idEvento}' => $prossimiEventi[0]['Id']
            ]);
            $prossimoEventoHTML = replace_content_between_markers($prossimoEventoHTML, [
                'locandinaHTML' => $locandinaHTML
            ]);
        } else {
            $prossimoEventoHTML = $nessunEventoProgrammatoHTML;
        }

        // dashboard eventi programmati
        if (count($prossimiEventi) > 0) {
            $listaEventiProgrammati = '';
            $eventoProgrammatoHTML = get_content_between_markers($eventiProgrammatiHTML, 'eventoProgrammato');
            foreach ($prossimiEventi as $evento) {
                $listaEventiProgrammati .= multi_replace($eventoProgrammatoHTML, [
                    '{titolo}' => $evento['Titolo'],
                    '{data}' => date_format(date_create($prossimiEventi[0]['Data']), 'Y-m-d'),
                    '{dataVisualizzata}' => date_format_ita($evento['Data']),
                    '{idEvento}' => $evento['Id']
                ]);
            }
            $eventiProgrammatiHTML = replace_content_between_markers($eventiProgrammatiHTML, [
                'eventoProgrammato' => $listaEventiProgrammati
            ]);
        } else {
            $eventiProgrammatiHTML = $nessunEventoProgrammatoHTML;
        }

        // dashboard punteggi mancanti
        $classifiche = $connection->get_classifiche();
        $punteggioMancanteHTML = get_content_between_markers($punteggiMancantiHTML, 'punteggioMancante');
        $listaPunteggiMancanti = '';
        foreach ($classifiche as $classifica) {
            $eventiClassifica = $connection->get_eventi_classifica($classifica['Id']);
            foreach ($eventiClassifica as $evento) {
                if ($evento['Data'] < date('Y-m-d') && $connection->get_punteggi_evento($evento['Id']) == null) {
                    $listaPunteggiMancanti .= multi_replace($punteggioMancanteHTML, [
                        '{titolo}' => $evento['Titolo'],
                        '{data}' => date_format(date_create($prossimiEventi[0]['Data']), 'Y-m-d'),
                        '{dataVisualizzata}' => date_format_ita($evento['Data']),
                        '{idEvento}' => $evento['Id']
                    ]);
                }
            }
        }
        if ($listaPunteggiMancanti == '') {
            $punteggiMancantiHTML = $nessunPunteggioMancanteHTML;
        } else {
            $punteggiMancantiHTML = replace_content_between_markers($punteggiMancantiHTML, [
                'punteggioMancante' => $listaPunteggiMancanti
            ]);
        }

        // dashboard utenti attivi
        $utentiBase = $connection->get_utenti_base();
        $utentiAttivi = 0;
        foreach ($utentiBase as $utente) {
            if ($utente['Attivo'] == 1) {
                $utentiAttivi ++;
            }
        }

        $dashboardAdmin = multi_replace(replace_content_between_markers($dashboardAdminHTML, [
            'prossimoEvento' => $prossimoEventoHTML,
            'nessunEventoProgrammato' => '',
            'eventiProgrammati' => $eventiProgrammatiHTML,
            'punteggiMancanti' => $punteggiMancantiHTML,
            'nessunPunteggioMancante' => ''
        ]), [
            '{numeroUtenti}' => $utentiAttivi
        ]);
    }
    $content = replace_content_between_markers($content, [
        'dashboardAdmin' => $dashboardAdmin
    ]);
} else {
    header("location: ../errore500.php");
    exit;
}

echo multi_replace(replace_content_between_markers($paginaHTML, [
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => $content,
    '{onload}' => $onload,
    '{classList}' => $classList
]);
