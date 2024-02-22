<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/pagina-template.html");
$adminHTML = file_get_contents("../template/admin/admin-template.html");
$profiloHTML = file_get_contents("../template/admin/profilo-template.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("../template/admin/logout-template.html") : '';

$title = 'Profilo personale &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina profilo contenente le informazioni relative al proprio profilo utente.';
$keywords = '';
$percorso = '../';
$percorsoAdmin = '';
$menu = get_menu($pageId);
$adminMenu = get_admin_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
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
            $formModificaDatiUtente = file_get_contents("../template/admin/form-modifica-email-template.html");
        } elseif (isset($_GET["submitPassword"])) {
            $formModificaDatiUtente = file_get_contents("../template/admin/form-modifica-password-template.html");
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
                $formModificaDatiUtente = file_get_contents("template/form-modifica-email-template.html");
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
                $formModificaDatiUtente = file_get_contents("template/form-modifica-password-template.html");
                $errori = '<ul>' . $erroriVAL . '</ul>';
            }
        }
    } else {
        header("location: login.php");
    }

    $connection->closeDBConnection();
} else {
    header("location: ../errore500.php");
}

echo multi_replace($paginaHTML, [
    '{content}' => $adminHTML,
    '{adminContent}' => $profiloHTML,
    '{username}' => $username,
    '{email}' => $email,
    '{formModificaDatiUtente}' => $formModificaDatiUtente,
    '{messaggiForm}' => $errori,
    '{formEmail}' => $formEmail,
    '{messaggiProfilo}' => $messaggiProfilo,
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{menu}' => $menu,
    '{adminMenu}' => $adminMenu,
    '{breadcrumbs}' => $breadcrumbs,
    '{onload}' => $onload,
    '{logout}' => $logout,
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin
]);
