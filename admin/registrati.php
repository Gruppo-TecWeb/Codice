<?php

namespace Utilities;
require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");
use DB\DBAccess;

session_start();

$paginaHTML = file_get_contents("../template/pagina-template.html");
$registratiHTML = file_get_contents("../template/admin/registrati-template.html");
$logout = isset($_SESSION["login"]) ? file_get_contents("../template/admin/logout-template.html") : '';

$title = 'Registrati &minus; Fungo';
$pageId = 'admin/' . basename(__FILE__, '.php');
$description = 'Pagina dove poter effettuare l\'accesso all\'area autenticata del sito.';
$keywords = 'registrati, freestyle rap, fungo, micelio, battle, eventi, classifiche';
$percorso = '../';
$percorsoAdmin = '';
$menu = get_menu($pageId);
$breadcrumbs = get_breadcrumbs($pageId);
$onload = '';
$erroriVAL = '';
$errori = '';
$username = '';
$email = '';

$connection = DBAccess::getInstance();
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
    header("location: ../errore500.php");
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
    '{onload}' => $onload,
    '{logout}' => $logout,
    '{percorso}' => $percorso,
    '{percorsoAdmin}' => $percorsoAdmin
]);