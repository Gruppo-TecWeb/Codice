<?php

namespace Utilities;
class Utilities {

    private const pages_array = [
        'home'          => ['href' => 'index.php',         'anchor' => 'Home',         'lang' => 'en', 'menuOrder' => 1, 'parentId' => ''],
        'eventi'        => ['href' => 'eventi.php',        'anchor' => 'Eventi',       'lang' => '',   'menuOrder' => 2, 'parentId' => 'home'],
        'classifiche'   => ['href' => 'classifiche.php',  'anchor' => 'Classifiche',  'lang' => '',   'menuOrder' => 3, 'parentId' => 'home'],
        'battle'        => ['href' => 'battle.php',        'anchor' => 'Battle',       'lang' => 'en', 'menuOrder' => 4, 'parentId' => 'home'],
        'chi-siamo'     => ['href' => 'chi-siamo.php',    'anchor' => 'Chi siamo',    'lang' => '',   'menuOrder' => 5, 'parentId' => 'home']
    ];

    public function replace_in_page($pageHTML, $title, $description, $keywords, $pageId, $menu, $breadCrumbs, $content, $onload = '')
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

    public function get_menu($pageId)
    {
        $pages = array();
        foreach (self::pages_array as $page) {
            $lang_tag = $page['lang'] ? ' lang="' . $page['lang'] . '"' : '';
            $isCurrent = $page == self::pages_array[$pageId];
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

    public function get_breadcrumbs($pageId) {
        $breadcrumbs = '<p>Ti trovi in: ';
        $page = self::pages_array[$pageId];
        $parent = self::pages_array[$page['parentId']];
        while ($parent != '') {
            $lang_tag = $parent['lang'] ? ' lang="' . $parent['lang'] . '"' : '';
            $breadcrumbs .= '<a href="' . $parent['href'] . '"' . $lang_tag . '>' . $parent['anchor'] . '</a> &gt;&gt; ';
            $parent = self::pages_array[$parent['parentId']];
        }
        $lang_tag = $page['lang'] ? ' lang="' . $page['lang'] . '"' : '';
        $breadcrumbs .= '<span' . $lang_tag . '>' . $page['anchor'] . '</span>';
        $breadcrumbs .= '</p>';
        return $breadcrumbs;
    }

}