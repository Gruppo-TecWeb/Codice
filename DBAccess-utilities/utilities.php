<?php

## inserire funzioni utili php qui ##

function replace_in_page($pageHTML, $title, $description, $keywords, $pageId, $menu, $breadCrumbs, $content)
{
    $pageHTML = str_replace("{title}", $title, $pageHTML);
    $pageHTML = str_replace("{description}", $description, $pageHTML);
    $pageHTML = str_replace("{keywords}", $keywords, $pageHTML);
    $pageHTML = str_replace("{pageId}", $pageId, $pageHTML);
    $pageHTML = str_replace("{menu}", $menu, $pageHTML);
    $pageHTML = str_replace("{breadcrumbs}", $breadCrumbs, $pageHTML);
    $pageHTML = str_replace("{content}", $content, $pageHTML);

    return $pageHTML;
}
