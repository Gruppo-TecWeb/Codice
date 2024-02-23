<?php

namespace Utilities;

const pages_array = [
    'index'                 => ['href' => '{percorso}index.php',                'anchor' => 'Home',                    'lang' => 'en', 'menuOrder' => 1, 'adminMenuOrder' => 0, 'parentId' => ''],
    'eventi'                => ['href' => '{percorso}eventi.php',               'anchor' => 'Eventi',                  'lang' => '',   'menuOrder' => 2, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'evento'                => ['href' => '{percorso}evento.php?id={id}',       'anchor' => '{evento}',                'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 0, 'parentId' => 'eventi'],
    'classifiche'           => ['href' => '{percorso}classifiche.php',          'anchor' => 'Classifiche',             'lang' => '',   'menuOrder' => 3, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'modalità'              => ['href' => '{percorso}modalità.php',             'anchor' => 'Modalità',                'lang' => '',   'menuOrder' => 4, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'beats'                 => ['href' => '{percorso}beats.php',                'anchor' => 'Beats',                   'lang' => 'en', 'menuOrder' => 5, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'chi-siamo'             => ['href' => '{percorso}chi-siamo.php',            'anchor' => 'Chi siamo',               'lang' => '',   'menuOrder' => 6, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'admin/registrati'      => ['href' => '{percorsoAdmin}registrati.php',      'anchor' => 'Registrati',              'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'admin/login'           => ['href' => '{percorsoAdmin}login.php',           'anchor' => 'Login',                   'lang' => 'en', 'menuOrder' => 0, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'admin/amministrazione' => ['href' => '{percorsoAdmin}amministrazione.php', 'anchor' => 'Area di amministrazione', 'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 0, 'parentId' => 'index'],
    'admin/profilo'         => ['href' => '{percorsoAdmin}profilo.php',         'anchor' => 'Profilo Personale',       'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 1, 'parentId' => 'admin/amministrazione'],
    'admin/eventi'          => ['href' => '{percorsoAdmin}eventi.php',          'anchor' => 'Gestione Eventi',         'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 2, 'parentId' => 'admin/amministrazione'],
    'admin/classifiche'     => ['href' => '{percorsoAdmin}classifiche.php',     'anchor' => 'Gestione Classifiche',    'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 3, 'parentId' => 'admin/amministrazione'],
    'admin/tipievento'      => ['href' => '{percorsoAdmin}tipievento.php',      'anchor' => 'Gestione Tipi Evento',    'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 4, 'parentId' => 'admin/amministrazione'],
    'admin/rappers'         => ['href' => '{percorsoAdmin}rappers.php',         'anchor' => 'Gestione Rappers',        'lang' => 'en', 'menuOrder' => 0, 'adminMenuOrder' => 5, 'parentId' => 'admin/amministrazione'],
    'admin/amministratori'  => ['href' => '{percorsoAdmin}amministratori.php',  'anchor' => 'Gestione Amministratori', 'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 6, 'parentId' => 'admin/amministrazione'],
    'admin/logout'          => ['href' => '{percorsoAdmin}logout.php',          'anchor' => 'Logout',                  'lang' => 'en', 'menuOrder' => 0, 'adminMenuOrder' => 7, 'parentId' => 'index'],
    'errore500'             => ['href' => '{percorso}errore500.php',            'anchor' => 'Errore 500',              'lang' => '',   'menuOrder' => 0, 'adminMenuOrder' => 0, 'parentId' => '']
];

function multi_replace($source, $replacements) {
    return str_replace(array_keys($replacements), $replacements, $source);
}

function get_menu($pageId) {
    $pages = array();
    foreach (pages_array as $page) {
        if ($page['menuOrder'] > 0) {
            $lang_tag = $page['lang'] ? ' lang="' . $page['lang'] . '"' : '';
            $isCurrent = $page == pages_array[$pageId];
            $menuOrder = $page['menuOrder'];
            $pages[$menuOrder] = '<li>';
            if ($isCurrent) {
                $pages[$menuOrder] .= '<span aria-current="page" id="currentLink" class="menuItem"' . $lang_tag . '>' . $page['anchor'] . '</span>';
            } else {
                $pages[$menuOrder] .= '<a class="menuItem" href="' . $page['href'] . '"' . $lang_tag . '>' . $page['anchor'] . '</a>';
            }
            $pages[$menuOrder] .= '</li>';
        }
    }
    $menu = '' . implode('
            ', $pages);
    return $menu;
}
function get_admin_menu($pageId) {
    $pages = array();
    foreach (pages_array as $page) {
        if ($page['adminMenuOrder'] > 0) {
            $lang_tag = $page['lang'] ? ' lang="' . $page['lang'] . '"' : '';
            $isCurrent = $page == pages_array[$pageId];
            $adminMenuOrder = $page['adminMenuOrder'];
            $pages[$adminMenuOrder] = '<li';
            if ($isCurrent) {
                $pages[$adminMenuOrder] .= ' id="currentLink"' . $lang_tag . '>' . $page['anchor'];
            } else {
                $pages[$adminMenuOrder] .= '><a href="' . $page['href'] . '"' . $lang_tag . '>' . $page['anchor'] . '</a>';
            }
            $pages[$adminMenuOrder] .= '</li>';
        }
    }
    $menu = '' . implode('
            ', $pages);
    return $menu;
}
function get_breadcrumbs($pageId) {
    $breadcrumbs = '';
    $page = pages_array[$pageId];
    $parent = $page['parentId'] != '' ? pages_array[$page['parentId']] : '';
    while ($parent != '') {
        $lang_tag = $parent['lang'] ? ' lang="' . $parent['lang'] . '"' : '';
        $breadcrumbs = '<a href="' . $parent['href'] . '"' . $lang_tag . '>' . $parent['anchor'] . '</a> <span aria-hidden="true">&rsaquo;&rsaquo; </span>' . $breadcrumbs;
        $parent = $parent['parentId'] != '' ? pages_array[$parent['parentId']] : '';
    }
    $lang_tag = $page['lang'] ? ' lang="' . $page['lang'] . '"' : '';
    $breadcrumbs .= '<span' . $lang_tag . '>' . $page['anchor'] . '</span>';
    $breadcrumbs = '<p><span id="ti-trovi-in">Ti trovi in: </span>' . $breadcrumbs . '</p>';
    return $breadcrumbs;
}
function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
