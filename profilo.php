<?php

namespace Utilities;
require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");
use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/pagina-template.html");
$profiloHTML = file_get_contents("template/profilo-template.html");

$title = 'Profilo personale &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina profilo contenente le informazioni relative al proprio profilo utente.';
$keywords = '';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$erroriVAL = '';
$errori = '';
$username = '';
$email = '';
$formEmail = '';
$formModificaDatiUtente = '';
$messaggiProfilo = "";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if ($connectionOk) {
    if (isset($_SESSION["login"])) {
        $utente = $_SESSION["datiUtente"];
        $username = $utente["Username"];
        $email = $utente["Email"];

        if (isset($_GET["submitEmail"])) {
            $formModificaDatiUtente = file_get_contents("template/form-modifica-email-template.html");
        }
        elseif (isset($_GET["submitPassword"])) {
            $formModificaDatiUtente = file_get_contents("template/form-modifica-password-template.html");
        }

        if (isset($_POST["submitNewEmail"])) {
            $errore = false;
            $formEmail = validate_input($_POST["email"]);
            if ($formEmail == "") {
                $errore = true;
                $erroriVAL .= "<li>Inserire nuova e-Mail.</li>";
            }
            else {
                $utente = $connection -> get_utente_by_email($formEmail);
                if (!(is_null($utente))) {
                    $errore = true;
                    $erroriVAL .= "<li>Un utente risulta giá registrato con questa e-Mail.</li>";
                }
            }
            if (!$errore) {
                $mailModificata = $connection -> change_email($username, $formEmail);
                if ($mailModificata) {
                    $_SESSION["datiUtente"]["Email"] = $formEmail;
                    $email = $formEmail;
                    $messaggiProfilo .= "<li>E-Mail aggiornata con successo.</li>";
                }
            }
            else {
                $formModificaDatiUtente = file_get_contents("template/form-modifica-email-template.html");
                $errori = '<ul>' . $erroriVAL . '</ul>';
            }
        }
        elseif (isset($_POST["submitNewPassword"])) {
            $errore = false;
            $formPassword = validate_input($_POST["password"]);
            $formConfermaPassword = validate_input($_POST["confermaPassword"]);
            if ($formPassword == "") {
                $errore = true;
                $erroriVAL .= "<li>Inserire nuova password.</li>";
            }
            elseif ($formPassword != $formConfermaPassword) {
                $errore = true;
                $erroriVAL .= "<li>Le password non coincidono.</li>";
            }
            if (!$errore) {
                $passwordModificata = $connection -> change_password($username, $formPassword);
                if ($passwordModificata) {
                    $messaggiProfilo .= "<li>Password aggiornata con successo.</li>";
                }
            }
            if ($errore) {
                $formModificaDatiUtente = file_get_contents("template/form-modifica-password-template.html");
                $errori = '<ul>' . $erroriVAL . '</ul>';
            }
        }
    }
    else {
        header("location: login.php");
    }

    $connection -> closeDBConnection();
}
else {
    $content .= '<p>I sistemi sono momentaneamente fuori servizio, ci scusiamo per il disagio.</p>';
}

$profiloHTML = str_replace('{username}', $username, $profiloHTML);
$profiloHTML = str_replace('{email}', $email, $profiloHTML);
$profiloHTML = str_replace('{formModificaDatiUtente}', $formModificaDatiUtente, $profiloHTML);
$profiloHTML = str_replace('{messaggiForm}', $errori, $profiloHTML);
$profiloHTML = str_replace('{formEmail}', $formEmail, $profiloHTML);
$profiloHTML = str_replace('{messaggiProfilo}', $messaggiProfilo, $profiloHTML);
echo multi_replace($paginaHTML,[
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{menu}' => $menu,
    '{breadcrumbs}' => $breadcrumbs,
    '{content}' => $profiloHTML,
    '{onload}' => $onload
]);
