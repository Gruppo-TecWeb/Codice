<?php

namespace Utilities;
require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");
use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/template-pagina.html");
$loginHTML = file_get_contents("../template/admin/login-template.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("template/admin/logout-template.html") : '';

$title = 'Login &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina dove poter effettuare l\'accesso all\'area autenticata del sito.';
$keywords = 'login, freestyle rap, fungo, micelio, battle, eventi, classifiche';
$percorso = '../';
$percorsoAdmin = '';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$erroriVAL = '';
$errori = '';
$username = '';

$connection = DBAccess::getInstance();
$connectionOk = $connection -> openDBConnection();

if ($connectionOk) {
    if (isset($_SESSION["login"])) {
        header("location: amministrazione.php");
    }
    if (isset($_POST["submit"])) {
        $errore = false;
        $username = validate_input($_POST["username"]);
        $password = validate_input($_POST["password"]);
        if ($username == "") {
            $errore = true;
            $erroriVAL .= "<li>Inserire Username.</li>";
        }
        if ($password == "") {
            $errore = true;
            $erroriVAL .= "<li>Inserire Password.</li>";
        }
        if (!$errore) {
            $utente = $connection -> login($username, $password);
            if (!(is_null($utente))) {
                $_SESSION["datiUtente"] = $utente;
                $_SESSION["login"] = true;
                header("location: amministrazione.php");
            } else {
                $errore = true;
                $erroriVAL .= "<li>Username e/o password errati.</li>";
            }
        }
        if ($errore) {
            $errori = '<ul>' . $erroriVAL . '</ul>';
        }
    }
}
else {
    header("location: ../errore500.php");
}

$loginHTML = str_replace("{messaggiForm}", $errori, $loginHTML);
$loginHTML = str_replace("{valoreUsername}", $username, $loginHTML);
echo multi_replace($paginaHTML,[
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{menu}' => $menu,
    '{breadcrumbs}' => $breadcrumbs,
    '{content}' => $loginHTML,
    '{onload}' => $onload,
    '{logout}' => $logout,
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin
]);
