<?php

namespace Utilities;

const pages_array = [
    'index'         => ['href' => 'index.php',          'anchor' => 'Home',         'lang' => 'en', 'menuOrder' => 1, 'parentId' => ''],
    'eventi'        => ['href' => 'eventi.php',         'anchor' => 'Eventi',       'lang' => '',   'menuOrder' => 2, 'parentId' => 'index'],
    'classifiche'   => ['href' => 'classifiche.php',    'anchor' => 'Classifiche',  'lang' => '',   'menuOrder' => 3, 'parentId' => 'index'],
    'battle'        => ['href' => 'battle.php',         'anchor' => 'Battle',       'lang' => 'en', 'menuOrder' => 4, 'parentId' => 'index'],
    'chi-siamo'     => ['href' => 'chi-siamo.php',      'anchor' => 'Chi siamo',    'lang' => '',   'menuOrder' => 5, 'parentId' => 'index']
];

function multi_replace($source, $replacements)
{
    foreach ($replacements as $key => $value) {
        $source = str_replace($key, $value, $source);
    }
    return $source;
}

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

function get_menu($pageId)
{
    $pages = array();
    foreach (pages_array as $page) {
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
    $menu = '<ul>' . implode('', $pages) . '</ul>';
    return $menu;
}

function get_breadcrumbs($pageId, $other = '')
{
    $breadcrumbs = '<p>Ti trovi in: ';
    $page = pages_array[$pageId];
    $parent = isset(pages_array[$page['parentId']]) ? pages_array[$page['parentId']] : null;
    while ($parent != null) {
        $lang_tag = $parent['lang'] ? ' lang="' . $parent['lang'] . '"' : '';
        $breadcrumbs .= '<a href="' . $parent['href'] . '"' . $lang_tag . '>' . $parent['anchor'] . '</a> &gt;&gt; ';
        $parent = isset(pages_array[$parent['parentId']]) ? pages_array[$parent['parentId']] : null;
    }
    $lang_tag = $page['lang'] ? ' lang="' . $page['lang'] . '"' : '';
    if ($other != '') {
        $breadcrumbs .= '<a href="' . $page['href'] . '"' . $lang_tag . '>' . $page['anchor'] . '</a> &gt;&gt; ';
        $breadcrumbs .= $other;
    } else {
        $breadcrumbs .= '<span' . $lang_tag . '>' . $page['anchor'] . '</span>';
    }
    $breadcrumbs .= '</p>';
    return $breadcrumbs;
}

function format_date($data) {
    $mesi = [
        1 => 'gennaio',
        2 => 'febbraio',
        3 => 'marzo',
        4 => 'aprile',
        5 => 'maggio',
        6 => 'giugno',
        7 => 'luglio',
        8 => 'agosto',
        9 => 'settembre',
        10 => 'ottobre',
        11 => 'novembre',
        12 => 'dicembre'
    ];

    $dataOggetto = date_create_from_format('Y-m-d', $data);
    $giorno = $dataOggetto->format('j');
    $mese = $mesi[(int)$dataOggetto->format('n')];
    $anno = $dataOggetto->format('Y');

    return $giorno . ' ' . $mese . ' ' . $anno;
}