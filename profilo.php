<?php

namespace Utilities;

require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/pagina-template.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("template/logout.html") : '';

$title = 'Profilo personale &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina profilo contenente le informazioni relative al proprio profilo utente.';
$keywords = '';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$content = file_get_contents("template/profilo.html");
$onload = '';
$erroriVAL = '';
$errori = '';
$username = '';
$email = '';
$formEmail = '';
$formModificaDatiUtente = '';
$messaggiProfilo = "";

$connection = DBAccess::getInstance();
$connectionOk = $connection->openDBConnection();
if ($connectionOk) {
    if (isset($_SESSION["login"])) {
        $utente = $_SESSION["datiUtente"];
        $username = $utente["Username"];
        $email = $utente["Email"];

        if (isset($_GET["submitEmail"])) {
            $formModificaDatiUtente = get_content_between_markers($content, 'formModificaEmail');
        } elseif (isset($_GET["submitPassword"])) {
            $formModificaDatiUtente = get_content_between_markers($content, 'formModificaPassword');
        }

        if (isset($_POST["submitNewEmail"])) {
            $errore = false;
            $formEmail = validate_input($_POST["email"]);
            if ($formEmail == "") {
                $errore = true;
                $erroriVAL .= "<li>Inserire nuova e-Mail.</li>";
            } else {
                $utente = $connection->get_utente_by_email($formEmail);
                if (!(is_null($utente))) {
                    $errore = true;
                    $erroriVAL .= "<li>Un utente risulta giá registrato con questa e-Mail.</li>";
                }
            }
            if (!$errore) {
                $mailModificata = $connection->change_email($username, $formEmail);
                if ($mailModificata) {
                    $_SESSION["datiUtente"]["Email"] = $formEmail;
                    $email = $formEmail;
                    $messaggiProfilo .= "<li>E-Mail aggiornata con successo.</li>";
                }
            } else {
                $formModificaDatiUtente = get_content_between_markers($content, 'formModificaEmail');
                $errori = '<ul>' . $erroriVAL . '</ul>';
            }
        } elseif (isset($_POST["submitNewPassword"])) {
            $errore = false;
            $formPassword = validate_input($_POST["password"]);
            $formConfermaPassword = validate_input($_POST["confermaPassword"]);
            if ($formPassword == "") {
                $errore = true;
                $erroriVAL .= "<li>Inserire nuova password.</li>";
            } elseif ($formPassword != $formConfermaPassword) {
                $errore = true;
                $erroriVAL .= "<li>Le password non coincidono.</li>";
            }
            if (!$errore) {
                $passwordModificata = $connection->change_password($username, $formPassword);
                if ($passwordModificata) {
                    $messaggiProfilo .= "<li>Password aggiornata con successo.</li>";
                }
            }
            if ($errore) {
                $formModificaDatiUtente = get_content_between_markers($content, 'formModificaPassword');
                $errori = '<ul>' . $erroriVAL . '</ul>';
            }
        }
    } else {
        header("location: login.php");
    }

    $connection->closeDBConnection();
} else {
    header("location: errore500.php");
}

$content = multi_replace(replace_content_between_markers($content, [
    'formModificaDatiUtente' => $formModificaDatiUtente,
]), [
    '{username}' => $username,
    '{email}' => $email,
    '{messaggiForm}' => $errori,
    '{formEmail}' => $formEmail,
    '{messaggiProfilo}' => $messaggiProfilo
]);

echo replace_content_between_markers(multi_replace($paginaHTML, [
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{content}' => $content,
    '{onload}' => $onload,
    '{logout}' => $logout
]), [
    'breadcrumbs' => $breadcrumbs,
    'menu' => $menu,
]);
