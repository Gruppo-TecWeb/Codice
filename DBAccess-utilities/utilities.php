<?php

## inserire funzioni utili php qui ##

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
    $menu = '<ul>';
    $menu_array = [
        ['link' => 'index.php', 'name' => 'Home', 'lang' => 'en', 'id' => 'home'],
        ['link' => 'eventi.php', 'name' => 'Eventi', 'lang' => '', 'id' => 'eventi'],
        ['link' => 'classifiche.html', 'name' => 'Classifiche', 'lang' => '', 'id' => 'classifiche'],
        ['link' => 'battle.php', 'name' => 'Battle', 'lang' => 'en', 'id' => 'battle'],
        ['link' => 'chi-siamo.html', 'name' => 'Chi siamo', 'lang' => '', 'id' => 'chi-siamo']
    ];

    for ($i = 0; $i < count($menu_array); $i++) {
        $lang_tag = ($menu_array[$i]['lang'] ? ' lang="' . $menu_array[$i]['lang'] . '"' : '');
        $isCurrent = ($pageId == $menu_array[$i]['id']);
        $menu .= '<li';
        if ($isCurrent) {
            $menu .= ' id="currentLink"' . $lang_tag . '>' . $menu_array[$i]['name'];
        } else {
            $menu .= '><a href="' . $menu_array[$i]['link'] . '"' . $lang_tag . '>' . $menu_array[$i]['name'] . '</a>';
        }
        $menu .= '</li>';
    }
    $menu .= '</ul>';
    return $menu;
}
