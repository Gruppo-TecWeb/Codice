<?php

namespace Utilities;

const pages_array = [
    'index'                         => ['href' => 'index.php',                   'anchor' => 'Home',                                      'lang' => 'en', 'menuOrder' => 1, 'admin' => 0, 'parentId' => ''],
    'eventi'                        => ['href' => 'eventi.php',                  'anchor' => 'Eventi',                                    'lang' => '',   'menuOrder' => 2, 'admin' => 0, 'parentId' => 'index'],
    'evento'                        => ['href' => 'evento.php?id={id}',          'anchor' => '{evento}',                                  'lang' => '',   'menuOrder' => 0, 'admin' => 0, 'parentId' => 'eventi'],
    'classifiche'                   => ['href' => 'classifiche.php',             'anchor' => 'Classifiche',                               'lang' => '',   'menuOrder' => 3, 'admin' => 0, 'parentId' => 'index'],
    'modalita'                      => ['href' => 'modalita.php',                'anchor' => 'Modalità',                                  'lang' => '',   'menuOrder' => 4, 'admin' => 0, 'parentId' => 'index'],
    'beats'                         => ['href' => 'beats.php',                   'anchor' => 'Beats',                                     'lang' => 'en', 'menuOrder' => 5, 'admin' => 0, 'parentId' => 'index'],
    'chi-siamo'                     => ['href' => 'chi-siamo.php',               'anchor' => 'Chi siamo',                                 'lang' => '',   'menuOrder' => 6, 'admin' => 0, 'parentId' => 'index'],
    'registrati'                    => ['href' => 'registrati.php',              'anchor' => 'Registrati',                                'lang' => '',   'menuOrder' => 0, 'admin' => 0, 'parentId' => 'index'],
    'login'                         => ['href' => 'login.php',                   'anchor' => 'Login',                                     'lang' => 'en', 'menuOrder' => 0, 'admin' => 0, 'parentId' => 'index'],
    'admin/index'                   => ['href' => 'index.php',                   'anchor' => 'Area riservata',                            'lang' => '',   'menuOrder' => 0, 'admin' => 1, 'parentId' => 'index'],
    'admin/profilo'                 => ['href' => 'profilo.php',                 'anchor' => 'Profilo',                                   'lang' => '',   'menuOrder' => 1, 'admin' => 1, 'parentId' => 'admin/index'],
    'admin/eventi'                  => ['href' => 'eventi.php',                  'anchor' => 'Eventi',                                    'lang' => '',   'menuOrder' => 2, 'admin' => 1, 'parentId' => 'admin/index'],
    'admin/classifiche'             => ['href' => 'classifiche.php',             'anchor' => 'Classifiche',                               'lang' => '',   'menuOrder' => 3, 'admin' => 1, 'parentId' => 'admin/index'],
    'admin/tipi-evento'             => ['href' => 'tipi-evento.php',             'anchor' => 'Tipi Evento',                               'lang' => '',   'menuOrder' => 4, 'admin' => 1, 'parentId' => 'admin/index'],
    'admin/rappers'                 => ['href' => 'rappers.php',                 'anchor' => 'Rappers',                                   'lang' => 'en', 'menuOrder' => 5, 'admin' => 1, 'parentId' => 'admin/index'],
    'admin/amministratori'          => ['href' => 'amministratori.php',          'anchor' => 'Amministratori',                            'lang' => '',   'menuOrder' => 6, 'admin' => 1, 'parentId' => 'admin/index'],
    'admin/gestione-amministratori' => ['href' => 'gestione-amministratori.php', 'anchor' => 'Gestione Amministratori',                   'lang' => '',   'menuOrder' => 0, 'admin' => 1, 'parentId' => 'admin/amministratori'],
    'admin/gestione-rappers'        => ['href' => 'gestione-rappers.php',        'anchor' => 'Gestione <span lang=\'en\'>Rappers</span>', 'lang' => '',   'menuOrder' => 0, 'admin' => 1, 'parentId' => 'admin/rappers'],
    'admin/gestione-tipi-evento'    => ['href' => 'gestione-tipi-evento.php',    'anchor' => 'Gestione Tipi Evento',                      'lang' => '',   'menuOrder' => 0, 'admin' => 1, 'parentId' => 'admin/tipi-evento'],
    'admin/gestione-eventi'         => ['href' => 'gestione-eventi.php',         'anchor' => 'Gestione Eventi',                           'lang' => '',   'menuOrder' => 0, 'admin' => 1, 'parentId' => 'admin/eventi'],
    'admin/gestione-punteggi'       => ['href' => 'gestione-punteggi.php',       'anchor' => 'Gestione Punteggi',                         'lang' => '',   'menuOrder' => 0, 'admin' => 1, 'parentId' => 'admin/eventi'],
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
    foreach (pages_array as $page) {
        if ($page['menuOrder'] > 0 && $page['admin'] == 0) {
            $lang_attribute = $page['lang'] ? ' lang="' . $page['lang'] . '"' : '';
            $isCurrent = $page == pages_array[$pageId];
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
    foreach (pages_array as $page) {
        if ($page['menuOrder'] > 0 && $page['admin'] == 1) {
            $lang_attribute = $page['lang'] ? ' lang="' . $page['lang'] . '"' : '';
            $isCurrent = $page == pages_array[$pageId];
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
        '{pageHref}' => '../' . pages_array['index']['href'],
        '{lang}' => pages_array['index']['lang'] ? ' lang="' . pages_array['index']['lang'] . '"' : '',
        '{anchor}' => 'Area Utente'
    ]);
    return $adminMenu;
}
function get_breadcrumbs($pageId) {
    $page = pages_array[$pageId];
    $paginaHTML = (($page['admin'] == 0) ? file_get_contents("template/template-pagina.html") : file_get_contents("../template/admin/template-admin.html"));
    $breadcrumbs = get_content_between_markers($paginaHTML, 'breadcrumbs');
    $parentBreadcrumb = '';
    $parent = $page['parentId'] != '' ? pages_array[$page['parentId']] : '';
    while ($parent != '') {
        $parentBreadcrumbTemplate = get_content_between_markers($paginaHTML, 'parentBreadcrumb');
        $lang_attribute = $parent['lang'] ? ' lang="' . $parent['lang'] . '"' : '';
        $parentBreadcrumb = multi_replace($parentBreadcrumbTemplate, [
            '{pageHref}' => $page['admin'] != $parent['admin'] ? '../' . $parent['href'] : $parent['href'],
            '{lang}' => $lang_attribute,
            '{parent}' => $parent['anchor']
        ]) . $parentBreadcrumb;

        $parent = $parent['parentId'] != '' ? pages_array[$parent['parentId']] : '';
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
    } elseif ($file["size"] > 500000) {
        array_push($errori, "Il file è troppo grande");
    } elseif (!move_uploaded_file($file["tmp_name"], $nomeCompleto)) {
        array_push($errori, "Errore nel caricamento del file");
    }
    return $errori;
}