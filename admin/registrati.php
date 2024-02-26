<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/template-pagina.html");
$content = file_get_contents("../template/admin/registrati.html");

$title = 'Registrati &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina dove poter effettuare l\'accesso all\'area autenticata del sito.';
$keywords = 'registrati, freestyle rap, fungo, micelio, battle, eventi, classifiche';
$percorso = '../';
$percorsoAdmin = '';
$menu = get_menu($pageId, $percorso);
$breadcrumbs = get_breadcrumbs($pageId, $percorso);
$onload = '';
$username = '';
$email = '';
$messaggioForm = '';
$messaggiForm = '';

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    if (isset($_SESSION["login"])) {
        header("location: index.php");
    }

    $messaggioForm = get_content_between_markers($content, 'messaggioForm');
    $messaggioFormConLink = get_content_between_markers($content, 'messaggioFormConLink');
    if (isset($_POST["submit"])) {
        $errore = false;
        $username = validate_input($_POST["username"]);
        $password = validate_input($_POST["password"]);
        $confermaPassword = validate_input($_POST["confermaPassword"]);
        $email = validate_input($_POST["email"]);
        if ($username == "") {
            $errore = true;
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Inserire Username"]);
        }
        else {
            $utente = $connection -> get_utente_by_username($username);
            if (is_null($utente)) {
                $utente = $connection->get_utente_by_email($email);
            }
            if (!is_null($utente)) {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioFormConLink, [
                    '{messaggio}' => "Utente giá registrato, vai alla pagina di ",
                    '{pageHref}' => "login.php",
                    '{lang}' => " lang=\"en\"",
                    '{anchor}' => "login"]);
            }
        }
        if ($password == "") {
            $errore = true;
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Inserire Password"]);
        }
        elseif ($password != $confermaPassword) {
                $errore = true;
                $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Le password non coincidono"]);
        }
        if ($email == "") {
            $errore = true;
            $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Inserire E-Mail"]);
        }
        if (!$errore) {
            $utenteRegistrato = $connection->register($username, $password, $email);
            if ($utenteRegistrato > 0) {
                $_SESSION["datiUtente"] = array("Username" => $username, "Email" => $email);
                $_SESSION["login"] = true;
                header("location: index.php");
                $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "Registrazione avvenuta correttamente"]);
            }
            else {
                $messaggiForm .= multi_replace($messaggioForm, ['{messaggio}' => "La registrazione non é avvenuta"]);
            }
        }
        else {
            $messaggiForm = replace_content_between_markers(
                get_content_between_markers($content, 'messaggiForm'), [
                    'messaggioForm' => $messaggiForm,
                    'messaggioFormConLink' => '']);
        }
    }

    $connection->closeDBConnection();
}
else {
    header("location: ../errore500.php");
}

if (isset($_SESSION["login"])) {
    $logout = get_content_between_markers($paginaHTML, 'logout');
}

echo multi_replace(replace_content_between_markers($paginaHTML, [
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu
]), [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => replace_content_between_markers($content, ['messaggiForm' => $messaggiForm]),
    '{onload}' => $onload,
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin,
    '{valoreUsername}' => $username,
    '{valoreEmail}' => $email
]);
