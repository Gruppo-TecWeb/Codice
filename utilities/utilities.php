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

function replace_in_page($pageHTML, $title, $description, $keywords, $pageId, $menu, $breadCrumbs, $content, $onload = '')
{
    $pageHTML = str_replace("{title}", $title, $pageHTML);
    $pageHTML = str_replace("{description}", $description, $pageHTML);
    $pageHTML = str_replace("{keywords}", $keywords, $pageHTML);
    $pageHTML = str_replace("{pageId}", $pageId, $pageHTML);
    $pageHTML = str_replace("{menu}", $menu, $pageHTML);
    $pageHTML = str_replace("{breadcrumbs}", $breadCrumbs, $pageHTML);
    $pageHTML = str_replace("{content}", $content, $pageHTML);
    $pageHTML = str_replace("{onload}", $onload, $pageHTML);

    return $pageHTML;
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
            ', $pages) . get_reserved_menu($logged, $pageId) . '';
    return $menu;
}
function get_reserved_menu($logged, $pageId) {
    if ($logged) {
        switch ($pageId) {
            case 'profilo':
                $menu = '<li id="currentLink"' . (pages_array['profilo']['lang']? ' lang="' . pages_array['profilo']['lang'] . '"' : '')
                        .'>'.pages_array['profilo']['anchor'].'</li>
                        <li><a href="' . pages_array['logout']['href'] . '"'
                        . (pages_array['logout']['lang']? ' lang="' . pages_array['logout']['lang'] . '"' : '')
                        .'>'.pages_array['logout']['anchor'].'</a></li>';
                break;
            default:
                $menu = '<ul><li><a href="' . pages_array['profilo']['href'] . '"'
                    . (pages_array['profilo']['lang']? ' lang="' . pages_array['profilo']['lang'] . '"' : '')
                    .'>'.pages_array['profilo']['anchor'].'</a></li>
                    <li><a href="' . pages_array['logout']['href'] . '"'
                    . (pages_array['logout']['lang']? ' lang="' . pages_array['logout']['lang'] . '"' : '')
                    .'>'.pages_array['logout']['anchor'].'</a></li>';
                break;
        }
    } else {
        switch ($pageId) {
            case 'registrati':
                $menu = '<li><a href="' . pages_array['login']['href'] . '"'
                        . (pages_array['login']['lang']? ' lang="' . pages_array['login']['lang'] . '"' : '')
                        .'>'.pages_array['login']['anchor'].'</a></li>
                        <li id="currentLink"' . (pages_array['registrati']['lang']? ' lang="' . pages_array['registrati']['lang'] . '"' : '')
                        .'>'.pages_array['registrati']['anchor'].'</li>';
                break;
            case 'login':
                $menu = '<li id="currentLink"' . (pages_array['login']['lang']? ' lang="' . pages_array['login']['lang'] . '"' : '')
                        .'>'.pages_array['login']['anchor'].'</li>
                        <li><a href="' . pages_array['registrati']['href'] . '"'
                        . (pages_array['registrati']['lang']? ' lang="' . pages_array['registrati']['lang'] . '"' : '')
                        .'>'.pages_array['registrati']['anchor'].'</a></li>';
                break;
            default:
                $menu = '<li><a href="' . pages_array['login']['href'] . '"'
                    . (pages_array['login']['lang']? ' lang="' . pages_array['login']['lang'] . '"' : '')
                    .'>'.pages_array['login']['anchor'].'</a></li>
                    <li><a href="' . pages_array['registrati']['href'] . '"'
                    . (pages_array['registrati']['lang']? ' lang="' . pages_array['registrati']['lang'] . '"' : '')
                    .'>'.pages_array['registrati']['anchor'].'</a></li>';
                break;
        }
    }
    return $menu;
}

function get_breadcrumbs($pageId) {
    $breadcrumbs = '<p><span id="ti-trovi-in">Ti trovi in: </span>';
    $page = pages_array[$pageId];
    $parent = $page['parentId'] != '' ? pages_array[$page['parentId']] : '';
    while ($parent != '') {
        $lang_tag = $parent['lang'] ? ' lang="' . $parent['lang'] . '"' : '';
        $breadcrumbs .= '<a href="' . $parent['href'] . '"' . $lang_tag . '>' . $parent['anchor'] . '</a> <span aria-hidden="true">&rsaquo;&rsaquo; </span>';
        $parent = $parent['parentId'] != '' ? pages_array[$parent['parentId']] : '';
    }
    $lang_tag = $page['lang'] ? ' lang="' . $page['lang'] . '"' : '';
    $breadcrumbs .= '<span' . $lang_tag . '>' . $page['anchor'] . '</span>';
    $breadcrumbs .= '</p>';
    return $breadcrumbs;
}
function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
