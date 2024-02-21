<?php

namespace Utilities;

const pages_array = [
    'index'       => ['href' => '{percorso}index.php',            'anchor' => 'Home',                                  'lang' => 'en', 'menuOrder' => 1, 'parentId' => ''],
    'eventi'      => ['href' => '{percorso}eventi.php',           'anchor' => 'Eventi',                                'lang' => '',   'menuOrder' => 2, 'parentId' => 'index'],
    'classifiche' => ['href' => '{percorso}classifiche.php',      'anchor' => 'Classifiche',                           'lang' => '',   'menuOrder' => 3, 'parentId' => 'index'],
    'battle'      => ['href' => '{percorso}battle.php',           'anchor' => 'Tipi di <span lang="en">Battle</span>', 'lang' => '',   'menuOrder' => 4, 'parentId' => 'index'],
    'beats'       => ['href' => '{percorso}beats.php',            'anchor' => 'Beats',                                 'lang' => '',   'menuOrder' => 5, 'parentId' => 'index'],
    'chi-siamo'   => ['href' => '{percorso}chi-siamo.php',        'anchor' => 'Chi siamo',                             'lang' => '',   'menuOrder' => 6, 'parentId' => 'index'],
    'login'       => ['href' => '{percorso}admin/login.php',      'anchor' => 'Login',                                 'lang' => 'en', 'menuOrder' => 0, 'parentId' => 'index'],
    'profilo'     => ['href' => '{percorso}admin/profilo.php',    'anchor' => 'Profilo',                               'lang' => '',   'menuOrder' => 0, 'parentId' => 'index'],
    'logout'      => ['href' => '{percorso}admin/logout.php',     'anchor' => 'Logout',                                'lang' => 'en', 'menuOrder' => 0, 'parentId' => 'index'],
    'registrati'  => ['href' => '{percorso}admin/registrati.php', 'anchor' => 'Registrati',                            'lang' => '',   'menuOrder' => 0, 'parentId' => 'index'],
    'evento'      => ['href' => '{percorso}evento.php?id={id}',   'anchor' => '{evento}',                              'lang' => '',   'menuOrder' => 0, 'parentId' => 'eventi'],
    'errore500'   => ['href' => '{percorso}errore500.php',        'anchor' => 'Errore 500',                            'lang' => '',   'menuOrder' => 0, 'parentId' => 'index']
];

function multi_replace($source, $replacements) {
    return str_replace(array_keys($replacements), $replacements, $source);
}

function get_menu($logged, $pageId) {
    $pages = array();
    foreach (pages_array as $page) {
        if ($page['menuOrder'] > 0) {
            $lang_tag = $page['lang'] ? ' lang="' . $page['lang'] . '"' : '';
            $isCurrent = $page == pages_array[$pageId];
            $menuOrder = $page['menuOrder'];
            $pages[$menuOrder] = '<li';
            if ($isCurrent) {
                $pages[$menuOrder] .= ' id="currentLink"' . $lang_tag . '>' . $page['anchor'];
            } else {
                $pages[$menuOrder] .= '><a href="' . $page['href'] . '"' . $lang_tag . '>' . $page['anchor'] . '</a>';
            }
            $pages[$menuOrder] .= '</li>';
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
