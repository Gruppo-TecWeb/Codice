<?php

namespace Utilities;

const pages_array = [
    'index'       => ['href' => 'index.php',          'anchor' => 'Home',                                  'lang' => 'en', 'menuOrder' => 1, 'parentId' => ''],
    'eventi'      => ['href' => 'eventi.php',         'anchor' => 'Eventi',                                'lang' => '',   'menuOrder' => 2, 'parentId' => 'index'],
    'classifiche' => ['href' => 'classifiche.php',    'anchor' => 'Classifiche',                           'lang' => '',   'menuOrder' => 3, 'parentId' => 'index'],
    'battle'      => ['href' => 'battle.php',         'anchor' => 'Tipi di <span lang="en">Battle</span>', 'lang' => '',   'menuOrder' => 4, 'parentId' => 'index'],
    'chi-siamo'   => ['href' => 'chi-siamo.php',      'anchor' => 'Chi siamo',                             'lang' => '',   'menuOrder' => 5, 'parentId' => 'index'],
    'login'       => ['href' => 'login.php',          'anchor' => 'Login',                                 'lang' => 'en', 'menuOrder' => 0, 'parentId' => 'index'],
    'profilo'     => ['href' => 'profilo.php',        'anchor' => 'Profilo',                               'lang' => '',   'menuOrder' => 0, 'parentId' => 'index'],
    'logout'      => ['href' => 'logout.php',         'anchor' => 'Logout',                                'lang' => 'en', 'menuOrder' => 0, 'parentId' => 'index'],
    'registrati'  => ['href' => 'registrati.php',     'anchor' => 'Registrati',                            'lang' => '',   'menuOrder' => 0, 'parentId' => 'index'],
    'evento'      => ['href' => 'evento.php?id={id}', 'anchor' => '{evento}',                              'lang' => '',   'menuOrder' => 0, 'parentId' => 'eventi']
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
            ', $pages) . '
            ' . get_reserved_menu($logged, $pageId) . '';
    return $menu;
}
function get_reserved_menu($logged, $pageId) {
    $liMenu = '<li><a href="{href}"{lang}>{anchor}</a></li>';
    $currentLiMenu = '<li id="currentLink"{lang}>{anchor}</li>';
    $menu = '';
    $langTagProfilo = pages_array['profilo']['lang'] ? ' lang="' . pages_array['profilo']['lang'] . '"' : '';
    $langTagLogout = pages_array['logout']['lang'] ? ' lang="' . pages_array['logout']['lang'] . '"' : '';
    $langTagRegistrati = pages_array['registrati']['lang'] ? ' lang="' . pages_array['registrati']['lang'] . '"' : '';
    $langTagLogin = pages_array['login']['lang'] ? ' lang="' . pages_array['login']['lang'] . '"' : '';
    if ($logged) {
        switch ($pageId) {
            case 'profilo':
                $menu .= str_replace('{lang}', $langTagProfilo, $currentLiMenu);
                $menu = str_replace('{anchor}', pages_array['profilo']['anchor'], $menu);
                $menu .= str_replace('{href}', pages_array['logout']['href'], $liMenu);
                $menu = str_replace('{lang}', $langTagLogout, $menu);
                $menu = str_replace('{anchor}', pages_array['logout']['anchor'], $menu);
                break;
            default:
                $menu .= str_replace('{href}', pages_array['profilo']['href'], $liMenu);
                $menu = str_replace('{lang}', $langTagProfilo, $menu);
                $menu = str_replace('{anchor}', pages_array['profilo']['anchor'], $menu);
                $menu .= str_replace('{href}', pages_array['logout']['href'], $liMenu);
                $menu = str_replace('{lang}', $langTagLogout, $menu);
                $menu = str_replace('{anchor}', pages_array['logout']['anchor'], $menu);
                break;
        }
    } else {
        switch ($pageId) {
            case 'registrati':
                $menu .= str_replace('{lang}', $langTagRegistrati, $currentLiMenu);
                $menu = str_replace('{anchor}', pages_array['registrati']['anchor'], $menu);
                $menu .= str_replace('{href}', pages_array['login']['href'], $liMenu);
                $menu = str_replace('{lang}', $langTagLogin, $menu);
                $menu = str_replace('{anchor}', pages_array['login']['anchor'], $menu);
                break;
            case 'login':
                $menu .= str_replace('{href}', pages_array['registrati']['href'], $liMenu);
                $menu = str_replace('{lang}', $langTagRegistrati, $menu);
                $menu = str_replace('{anchor}', pages_array['registrati']['anchor'], $menu);
                $menu .= str_replace('{lang}', $langTagLogin, $currentLiMenu);
                $menu = str_replace('{anchor}', pages_array['login']['anchor'], $menu);
                break;
            default:
                $menu .= str_replace('{href}', pages_array['registrati']['href'], $liMenu);
                $menu = str_replace('{lang}', $langTagRegistrati, $menu);
                $menu = str_replace('{anchor}', pages_array['registrati']['anchor'], $menu);
                $menu .= str_replace('{href}', pages_array['login']['href'], $liMenu);
                $menu = str_replace('{lang}', $langTagLogin, $menu);
                $menu = str_replace('{anchor}', pages_array['login']['anchor'], $menu);
                break;
        }
    }
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
