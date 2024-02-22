<?php

namespace Utilities;
require_once("utilities/utilities.php");
require_once("utilities/DBAccess.php");
use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("template/template-pagina.html");
$registratiHTML = file_get_contents("template/registrati-template.html");

$title = 'Registrati &minus; Fungo';
$pageId = basename(__FILE__, '.php');
$description = 'Pagina dove poter effettuare l\'accesso all\'area autenticata del sito.';
$keywords = 'registrati, freestyle rap, fungo, micelio, battle, eventi, classifiche';
$menu = get_menu(isset($_SESSION["login"]), $pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$erroriVAL = '';
$errori = '';
$username = '';
$email = '';

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();

if ($connectionOk) {
    if (isset($_SESSION["login"])) {
        header("location: profilo.php");
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
            if (!(is_null($utente))) {
                $errore = true;
                $erroriVAL .= "<li>Utente giá registrato. Vai alla pagina di <a href=\"login.php\" lang=\"en\">login</a>.</li>";
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
                header("location: profilo.php");
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
    $content .= '<p>I sistemi sono momentaneamente fuori servizio, ci scusiamo per il disagio.</p>';
}

$registratiHTML = str_replace("{messaggiForm}", $errori, $registratiHTML);
$registratiHTML = str_replace("{valoreUsername}", $username, $registratiHTML);
$registratiHTML = str_replace("{valoreEmail}", $email, $registratiHTML);
echo multi_replace($paginaHTML,[
    '{title}' => $title,
    '{description}' => $description,
    '{keywords}' => $keywords,
    '{pageId}' => $pageId,
    '{menu}' => $menu,
    '{breadcrumbs}' => $breadcrumbs,
    '{content}' => $registratiHTML,
    '{onload}' => $onload

]);