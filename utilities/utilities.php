<?php

namespace Utilities;

const MB = 1048576;
const MAX_FILE_SIZE = 10*MB;
const PAGES_ARRAY = [
    'index'                         => ['href' => 'index.php',                   'anchor' => 'Home',                                      'lang' => 'en', 'menuOrder' => 1, 'admin' => 0, 'parentId' => ''],
    'eventi'                        => ['href' => 'eventi.php',                  'anchor' => 'Eventi',                                    'lang' => '',   'menuOrder' => 2, 'admin' => 0, 'parentId' => 'index'],
    'evento'                        => ['href' => 'evento.php?id={id}',          'anchor' => '{evento}',                                  'lang' => '',   'menuOrder' => 0, 'admin' => 0, 'parentId' => 'eventi'],
    'classifiche'                   => ['href' => 'classifiche.php',             'anchor' => 'Classifiche',                               'lang' => '',   'menuOrder' => 3, 'admin' => 0, 'parentId' => 'index'],
    'modalita'                      => ['href' => 'modalita.php',                'anchor' => 'Modalità',                                  'lang' => '',   'menuOrder' => 4, 'admin' => 0, 'parentId' => 'index'],
    'beats'                         => ['href' => 'beats.php',                   'anchor' => 'Beats',                                     'lang' => 'en', 'menuOrder' => 5, 'admin' => 0, 'parentId' => 'index'],
    'chi-siamo'                     => ['href' => 'chi-siamo.php',               'anchor' => 'Chi siamo',                                 'lang' => '',   'menuOrder' => 6, 'admin' => 0, 'parentId' => 'index'],
    'login'                         => ['href' => 'login.php',                   'anchor' => 'Login',                                     'lang' => 'en', 'menuOrder' => 0, 'admin' => 0, 'parentId' => 'index'],
    'admin/index'                   => ['href' => 'index.php',                   'anchor' => 'Area riservata',                            'lang' => '',   'menuOrder' => 0, 'admin' => 1, 'parentId' => 'index'],
    'admin/profilo'                 => ['href' => 'profilo.php',                 'anchor' => 'Profilo',                                   'lang' => '',   'menuOrder' => 1, 'admin' => 1, 'parentId' => 'admin/index'],
    'admin/eventi'                  => ['href' => 'eventi.php',                  'anchor' => 'Eventi',                                    'lang' => '',   'menuOrder' => 2, 'admin' => 1, 'parentId' => 'admin/index'],
    'admin/classifiche'             => ['href' => 'classifiche.php',             'anchor' => 'Classifiche',                               'lang' => '',   'menuOrder' => 3, 'admin' => 1, 'parentId' => 'admin/index'],
    'admin/tipi-evento'             => ['href' => 'tipi-evento.php',             'anchor' => 'Tipi Evento',                               'lang' => '',   'menuOrder' => 4, 'admin' => 1, 'parentId' => 'admin/index'],
    'admin/rappers'                 => ['href' => 'rappers.php',                 'anchor' => 'Rappers',                                   'lang' => 'en', 'menuOrder' => 5, 'admin' => 1, 'parentId' => 'admin/index'],
    'admin/amministratori'          => ['href' => 'amministratori.php',          'anchor' => 'Amministratori',                            'lang' => '',   'menuOrder' => 6, 'admin' => 1, 'parentId' => 'admin/index'],
    'admin/gestione-profilo'        => ['href' => 'gestione-profilo.php',        'anchor' => 'Gestione Profilo',                          'lang' => '',   'menuOrder' => 0, 'admin' => 1, 'parentId' => 'admin/profilo'],
    'admin/gestione-amministratori' => ['href' => 'gestione-amministratori.php', 'anchor' => 'Gestione Amministratori',                   'lang' => '',   'menuOrder' => 0, 'admin' => 1, 'parentId' => 'admin/amministratori'],
    'admin/gestione-rappers'        => ['href' => 'gestione-rappers.php',        'anchor' => 'Gestione <span lang=\'en\'>Rappers</span>', 'lang' => '',   'menuOrder' => 0, 'admin' => 1, 'parentId' => 'admin/rappers'],
    'admin/gestione-tipi-evento'    => ['href' => 'gestione-tipi-evento.php',    'anchor' => 'Gestione Tipi Evento',                      'lang' => '',   'menuOrder' => 0, 'admin' => 1, 'parentId' => 'admin/tipi-evento'],
    'admin/gestione-eventi'         => ['href' => 'gestione-eventi.php',         'anchor' => 'Gestione Eventi',                           'lang' => '',   'menuOrder' => 0, 'admin' => 1, 'parentId' => 'admin/eventi'],
    'admin/gestione-punteggi'       => ['href' => 'gestione-punteggi.php',       'anchor' => 'Gestione Punteggi {evento}',                'lang' => '',   'menuOrder' => 0, 'admin' => 1, 'parentId' => 'admin/eventi'],
    'admin/gestione-classifiche'    => ['href' => 'gestione-classifiche.php',    'anchor' => 'Gestione Classifiche',                      'lang' => '',   'menuOrder' => 0, 'admin' => 1, 'parentId' => 'admin/classifiche'],
    'logout'                        => ['href' => 'logout.php',                  'anchor' => 'Logout',                                    'lang' => 'en', 'menuOrder' => 0, 'admin' => 0, 'parentId' => ''],
    'errore404'                     => ['href' => 'errore404.php',               'anchor' => 'Errore 404',                                'lang' => '',   'menuOrder' => 0, 'admin' => 0, 'parentId' => ''],
    'errore403'                     => ['href' => 'errore403.php',               'anchor' => 'Errore 403',                                'lang' => '',   'menuOrder' => 0, 'admin' => 0, 'parentId' => ''],
    'errore500'                     => ['href' => 'errore500.php',               'anchor' => 'Errore 500',                                'lang' => '',   'menuOrder' => 0, 'admin' => 0, 'parentId' => '']
];

function multi_replace($source, $replacements) {
    return str_replace(array_keys($replacements), $replacements, $source);
}

function get_menu($pageId) {
    $paginaHTML = file_get_contents("template/template-pagina.html");
    $menu = '';
    $liCurrent = get_content_between_markers($paginaHTML, 'liCurrent');
    $liNotCurrent = get_content_between_markers($paginaHTML, 'liNotCurrent');
    foreach (PAGES_ARRAY as $page) {
        if ($page['menuOrder'] > 0 && $page['admin'] == 0) {
            $lang_attribute = $page['lang'] ? ' lang="' . $page['lang'] . '"' : '';
            $isCurrent = $page == PAGES_ARRAY[$pageId];
            if ($isCurrent) {
                $menu .= multi_replace($liCurrent, [
                    '{lang}' => $lang_attribute,
                    '{anchor}' => $page['anchor']
                ]);
            } else {
                $menu .= multi_replace($liNotCurrent, [
                    '{pageHref}' => $page['href'],
                    '{lang}' => $lang_attribute,
                    '{anchor}' => $page['anchor']
                ]);
            }
        }
    }
    return $menu;
}
function get_admin_menu($pageId) {
    $paginaHTML = file_get_contents("../template/admin/template-admin.html");
    $adminMenu = '';
    $liCurrent = get_content_between_markers($paginaHTML, 'liCurrent');
    $liNotCurrent = get_content_between_markers($paginaHTML, 'liNotCurrent');
    foreach (PAGES_ARRAY as $page) {
        if ($page['menuOrder'] > 0 && $page['admin'] == 1) {
            $lang_attribute = $page['lang'] ? ' lang="' . $page['lang'] . '"' : '';
            $isCurrent = $page == PAGES_ARRAY[$pageId];
            if ($isCurrent) {
                $adminMenu .= multi_replace($liCurrent, [
                    '{lang}' => $lang_attribute,
                    '{anchor}' => $page['anchor']
                ]);
            } else {
                $adminMenu .= multi_replace($liNotCurrent, [
                    '{pageHref}' => $page['href'],
                    '{lang}' => $lang_attribute,
                    '{anchor}' => $page['anchor']
                ]);
            }
        }
    }
    $adminMenu .= multi_replace($liNotCurrent, [
        '{pageHref}' => '../' . PAGES_ARRAY['index']['href'],
        '{lang}' => PAGES_ARRAY['index']['lang'] ? ' lang="' . PAGES_ARRAY['index']['lang'] . '"' : '',
        '{anchor}' => 'Torna al sito'
    ]);
    return $adminMenu;
}
function get_breadcrumbs($pageId) {
    $page = PAGES_ARRAY[$pageId];
    $paginaHTML = (($page['admin'] == 0) ? file_get_contents("template/template-pagina.html") : file_get_contents("../template/admin/template-admin.html"));
    $breadcrumbs = get_content_between_markers($paginaHTML, 'breadcrumbs');
    $parentBreadcrumb = '';
    $parent = $page['parentId'] != '' ? PAGES_ARRAY[$page['parentId']] : '';
    while ($parent != '') {
        $parentBreadcrumbTemplate = get_content_between_markers($paginaHTML, 'parentBreadcrumb');
        $lang_attribute = $parent['lang'] ? ' lang="' . $parent['lang'] . '"' : '';
        $parentBreadcrumb = multi_replace($parentBreadcrumbTemplate, [
            '{pageHref}' => $page['admin'] != $parent['admin'] ? '../' . $parent['href'] : $parent['href'],
            '{lang}' => $lang_attribute,
            '{parent}' => $parent['anchor']
        ]) . $parentBreadcrumb;

        $parent = $parent['parentId'] != '' ? PAGES_ARRAY[$parent['parentId']] : '';
    }
    $lang_attribute = $page['lang'] ? ' lang="' . $page['lang'] . '"' : '';
    $breadcrumbs = replace_content_between_markers(
        multi_replace($breadcrumbs, [
            '{lang}' => $lang_attribute,
            '{anchor}' => $page['anchor']
        ]),
        [
            'parentBreadcrumb' => $parentBreadcrumb
        ]
    );
    return $breadcrumbs;
}
function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validate_date_time($date, $format = 'Y-m-d H:i:s') {
    $d = \DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function get_content_between_markers($content, $marker) {
    $start = strpos($content, '{' . $marker . '}');
    $end = strpos($content, '{/' . $marker . '}');
    if ($start === false || $end === false) {
        return '';
    }
    $start += strlen($marker) + 2;
    return substr($content, $start, $end - $start);
}

function replace_content_between_markers($content, $replacements) {
    foreach ($replacements as $marker => $replacement) {
        $start = strpos($content, '{' . $marker . '}');
        $end = strpos($content, '{/' . $marker . '}');
        if ($start !== false && $end !== false) {
            $start += strlen($marker) + 2;
            $content = substr_replace($content, $replacement, $start, $end - $start);
            $content = str_replace('{' . $marker . '}', '', $content);
            $content = str_replace('{/' . $marker . '}', '', $content);
        }
    }
    return $content;
}

function carica_file($file, $percorso, $nome) {
    $errori = [];
    $nomeCompleto = $percorso . $nome;
    if (file_exists($nomeCompleto)) {
        array_push($errori, "Esiste già un file con questo nome in questo percorso");
    } elseif ($file["size"] > MAX_FILE_SIZE) {
        array_push($errori, "Il file è troppo grande");
    } elseif (!move_uploaded_file($file["tmp_name"], $nomeCompleto)) {
        array_push($errori, "Errore nel caricamento del file");
    }
    return $errori;
}

function date_format_ita($data) {
    $mesi = [
        1 => 'Gennaio',
        2 => 'Febbraio',
        3 => 'Marzo',
        4 => 'Aprile',
        5 => 'Maggio',
        6 => 'Giugno',
        7 => 'Luglio',
        8 => 'Agosto',
        9 => 'Settembre',
        10 => 'Ottobre',
        11 => 'Novembre',
        12 => 'Dicembre'
    ];

    $dataOggetto = date_create_from_format('Y-m-d', $data);
    $giorno = $dataOggetto->format('j');
    $mese = $mesi[(int)$dataOggetto->format('n')];
    $anno = $dataOggetto->format('Y');

    return $giorno . ' ' . $mese . ' ' . $anno;
}