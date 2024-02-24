<?php

namespace Utilities;
require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");
use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/template-pagina.html");
$content = file_get_contents("../template/admin/registrati-template.html");

$title = 'Registrati &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina dove poter effettuare l\'accesso all\'area autenticata del sito.';
$keywords = 'registrati, freestyle rap, fungo, micelio, battle, eventi, classifiche';
$percorso = '../';
$percorsoAdmin = '';
$menu = get_menu($pageId, $percorso);
$breadcrumbs = get_breadcrumbs($pageId, $percorso);
$onload = '';
$erroriVAL = '';
$errori = '';
$username = '';
$email = '';

$connection = DBAccess::getInstance();
$connectionOk = $connection -> openDBConnection();

if ($connectionOk) {
    if (isset($_SESSION["login"])) {
        header("location: index.php");
    }
    if (isset($_POST["submit"])) {
        $errore = false;
        $username = validate_input($_POST["username"]);
        $password = validate_input($_POST["password"]);
        $confermaPassword = validate_input($_POST["confermaPassword"]);
        $email = validate_input($_POST["email"]);
        if ($username == "") {
            $errore = true;
            $erroriVAL .= "<li>Inserire Username.</li>";
        }
        else {
            $utente = $connection -> get_utente_by_username($username);
            if (is_null($utente)) {
                $utente = $connection -> get_utente_by_email($email);
            }
            if (!is_null($utente)) {
                $errore = true;
                $erroriVAL .= "<li>Utente giá registrato. Vai alla pagina di <a href=\"admin/login.php\" lang=\"en\">login</a>.</li>";
            }
        }
        if ($password == "") {
            $errore = true;
            $erroriVAL .= "<li>Inserire Password.</li>";
        }
        elseif ($password != $confermaPassword) {
                $errore = true;
                $erroriVAL .= "<li>Le password non coincidono.</li>";
        }
        if ($email == "") {
            $errore = true;
            $erroriVAL .= "<li>Inserire E-Mail.</li>";
        }
        if (!$errore) {
            $utenteRegistrato = $connection -> register($username, $password, $email);
            if ($utenteRegistrato > 0) {
                $_SESSION["datiUtente"] = array("Username" => $username, "Email" => $email);
                $_SESSION["login"] = true;
                header("location: index.php");
                $messaggiPerForm .= "<li>Registrazione avvenuta correttamente.</li>";
            }
            else {
                $messaggiPerForm .= "<li>La registrazione non é avvenuta.</li>";
            }
        }
        else {
            $errori = '<ul>' . $erroriVAL . '</ul>';
        }
    }
}
else {
    header("location: ../errore500.php");
}

if (isset($_SESSION["login"])) {
    $paginaHTML = replace_content_between_markers($paginaHTML, [
        'logout' => get_content_between_markers($paginaHTML, 'logout')
    ]);
} else {
    $paginaHTML = replace_content_between_markers($paginaHTML, [
        'logout' => ''
    ]);
}

echo multi_replace(replace_content_between_markers(
    multi_replace(
        replace_content_between_markers($paginaHTML, [
            'breadcrumbs' => $breadcrumbs,
            'menu' => $menu
        ]), [
        '{title}' => $title,
        '{description}' => $description,
        '{keywords}' => $keywords,
        '{pageId}' => $pageId,
        '{content}' => $content,
        '{onload}' => $onload,
        '{percorso}' => $percorso,
        '{adminContent}' => $adminContent,
        '{messaggiForm}' => $errori,
        '{valoreUsername}' => $username,
        '{valoreEmail}' => $email
    ]), [
    'adminMenu' => $adminMenu
]), ['{percorsoAdmin}' => $percorsoAdmin]);