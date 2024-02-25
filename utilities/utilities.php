<?php

namespace Utilities;

const pages_array = [
    'index'                 => ['href' => '{percorso}index.php',                'anchor' => 'Home',                    'lang' => 'en', 'menuOrder' => 1, 'adminMenuOrder' => 0, 'parentId' => ''],
    'eventi'                => ['href' => '{percorso}eventi.php',               'anchor' => 'Eventi',                  'lang' => '',   'menuOrder' => 2, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'evento'                => ['href' => '{percorso}evento.php?id={id}',       'anchor' => '{evento}',                'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 0, 'parentId' => 'eventi'],
    'classifiche'           => ['href' => '{percorso}classifiche.php',          'anchor' => 'Classifiche',             'lang' => '',   'menuOrder' => 3, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'modalita'              => ['href' => '{percorso}modalita.php',             'anchor' => 'Modalità',                'lang' => '',   'menuOrder' => 4, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'beats'                 => ['href' => '{percorso}beats.php',                'anchor' => 'Beats',                   'lang' => 'en', 'menuOrder' => 5, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'chi-siamo'             => ['href' => '{percorso}chi-siamo.php',            'anchor' => 'Chi siamo',               'lang' => '',   'menuOrder' => 6, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'admin/registrati'      => ['href' => '{percorsoAdmin}registrati.php',      'anchor' => 'Registrati',              'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'admin/login'           => ['href' => '{percorsoAdmin}login.php',           'anchor' => 'Login',                   'lang' => 'en', 'menuOrder' => 0, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'admin/index'           => ['href' => '{percorsoAdmin}index.php',           'anchor' => 'Area riservata',          'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'admin/profilo'         => ['href' => '{percorsoAdmin}profilo.php',         'anchor' => 'Profilo',                 'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 1, 'parentId' => 'admin/index'],
    'admin/eventi'          => ['href' => '{percorsoAdmin}eventi.php',          'anchor' => 'Gestione Eventi',         'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 2, 'parentId' => 'admin/index'],
    'admin/classifiche'     => ['href' => '{percorsoAdmin}classifiche.php',     'anchor' => 'Gestione Classifiche',    'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 3, 'parentId' => 'admin/index'],
    'admin/tipievento'      => ['href' => '{percorsoAdmin}tipievento.php',      'anchor' => 'Gestione Tipi Evento',    'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 4, 'parentId' => 'admin/index'],
    'admin/rappers'         => ['href' => '{percorsoAdmin}rappers.php',         'anchor' => 'Gestione Rappers',        'lang' => 'en', 'menuOrder' => 0, 'adminMenuOrder' => 5, 'parentId' => 'admin/index'],
    'admin/amministratori'  => ['href' => '{percorsoAdmin}amministratori.php',  'anchor' => 'Gestione Amministratori', 'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 6, 'parentId' => 'admin/index'],
    'admin/logout'          => ['href' => '{percorsoAdmin}logout.php',          'anchor' => 'Logout',                  'lang' => 'en', 'menuOrder' => 0, 'adminMenuOrder' => 7, 'parentId' => 'index'],
    'errore500'             => ['href' => '{percorso}errore500.php',            'anchor' => 'Errore 500',              'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 0, 'parentId' => '']
];

function multi_replace($source, $replacements) {
    return str_replace(array_keys($replacements), $replacements, $source);
}

function get_menu($pageId, $percorso) {
    $paginaHTML = file_get_contents($percorso . "template/template-pagina.html");
    $menu = '';
    $liCurrent = get_content_between_markers($paginaHTML, 'liCurrent');
    $liNotCurrent = get_content_between_markers($paginaHTML, 'liNotCurrent');
    foreach (pages_array as $page) {
        if ($page['menuOrder'] > 0) {
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
    $paginaHTML = file_get_contents("../template/admin/template-pagina-admin.html");
    $adminMenu = '';
    $liCurrent = get_content_between_markers($paginaHTML, 'liCurrent');
    $liNotCurrent = get_content_between_markers($paginaHTML, 'liNotCurrent');
    foreach (pages_array as $page) {
        if ($page['adminMenuOrder'] > 0) {
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
    return $adminMenu;
}
function get_breadcrumbs($pageId, $percorso) {
    $paginaHTML = file_get_contents($percorso . "template/template-pagina.html");
    $breadcrumbs = get_content_between_markers($paginaHTML, 'breadcrumbs');
    $page = pages_array[$pageId];
    $parentBreadcrumb = '';
    $parent = $page['parentId'] != '' ? pages_array[$page['parentId']] : '';
    while ($parent != '') {
        $parentBreadcrumbTemplate = get_content_between_markers($paginaHTML, 'parentBreadcrumb');
        $lang_attribute = $parent['lang'] ? ' lang="' . $parent['lang'] . '"' : '';
        $parentBreadcrumb = multi_replace($parentBreadcrumbTemplate, [
            '{pageHref}' => $parent['href'],
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
