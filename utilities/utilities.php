<?php

namespace Utilities;

const pages_array = [
    'index'       => ['href' => 'index.php',          'anchor' => 'Home',                                  'lang' => 'en', 'menuOrder' => 1, 'parentId' => ''],
    'eventi'      => ['href' => 'eventi.php',         'anchor' => 'Eventi',                                'lang' => '',   'menuOrder' => 2, 'parentId' => 'index'],
    'classifiche' => ['href' => 'classifiche.php',    'anchor' => 'Classifiche',                           'lang' => '',   'menuOrder' => 3, 'parentId' => 'index'],
    'battle'      => ['href' => 'battle.php',         'anchor' => 'Tipi di <span lang="en">Battle</span>', 'lang' => '',   'menuOrder' => 4, 'parentId' => 'index'],
    'beats'       => ['href' => 'beats.php',          'anchor' => 'Beats',                                 'lang' => '',   'menuOrder' => 5, 'parentId' => 'index'],
    'chi-siamo'   => ['href' => 'chi-siamo.php',      'anchor' => 'Chi siamo',                             'lang' => '',   'menuOrder' => 6, 'parentId' => 'index'],
    'login'       => ['href' => 'login.php',          'anchor' => 'Login',                                 'lang' => 'en', 'menuOrder' => 0, 'parentId' => 'index'],
    'profilo'     => ['href' => 'profilo.php',        'anchor' => 'Profilo',                               'lang' => '',   'menuOrder' => 0, 'parentId' => 'index'],
    'logout'      => ['href' => 'logout.php',         'anchor' => 'Logout',                                'lang' => 'en', 'menuOrder' => 0, 'parentId' => 'index'],
    'registrati'  => ['href' => 'registrati.php',     'anchor' => 'Registrati',                            'lang' => '',   'menuOrder' => 0, 'parentId' => 'index'],
    'evento'      => ['href' => 'evento.php?id={id}', 'anchor' => '{evento}',                              'lang' => '',   'menuOrder' => 0, 'parentId' => 'eventi'],
    'errore500'   => ['href' => 'errore500.php',      'anchor' => 'Errore 500',                            'lang' => '',   'menuOrder' => 0, 'parentId' => 'index']
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