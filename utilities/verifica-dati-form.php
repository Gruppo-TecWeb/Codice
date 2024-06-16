<?php

namespace Utilities;

require_once("../utilities/utilities.php");
require_once("../utilities/DBAccess.php");

use DB\DBAccess;

session_start();

if (!isset($_SESSION["login"])) {
    header("location: ../login.php");
    exit;
}

header('Content-Type: application/json');

$connection = DBAccess::get_instance();
$connectionOk = $connection->open_DB_connection();

if (!$connectionOk) {
    die(json_encode(array('error' => 'Connection failed.')));
}

$input = json_decode(file_get_contents('php://input'), true);
$tipo = $input['tipo'];
$elementToEdit = isset($input['elementToEdit']) ? $input['elementToEdit'] : null;
$response = array('error' => 'Invalid type');

switch ($tipo) {
    case 'email-profilo':
        $email = validate_input($input['email-profilo']);
        $user = $connection->get_utente_by_email($email);
        if (count($user) > 0) {
            $exists = $user['Username'] !== $_SESSION['username'];
        } else {
            $exists = false;
        }
        $response = array('exists' => $exists);
        break;

    case 'username':
        $username = validate_input($input['username']);
        if ($elementToEdit && strtolower($connection->get_utente_by_username($elementToEdit)['Username']) == strtolower($username)) {
            $exists = false;
        } else {
            $exists = count($connection->get_utente_by_username($username)) > 0;
        }
        $response = array('exists' => $exists);
        break;

    case 'email':
        $email = validate_input($input['email']);
        if ($elementToEdit && strtolower($connection->get_utente_by_username($elementToEdit)['Email']) == strtolower($email)) {
            $exists = false;
        } else {
            $exists = count($connection->get_utente_by_email($email)) > 0;
        }
        $response = array('exists' => $exists);
        break;

    case 'titolo-classifica':
        $titolo = validate_input($input['titolo-classifica']);
        if ($elementToEdit && strtolower($connection->get_classifica($elementToEdit)['Titolo']) == strtolower($titolo)) {
            $exists = false;
        } else {
            $exists = count($connection->get_classifiche($titolo)) > 0;
        }
        $response = array('exists' => $exists);
        break;

    case 'titolo-tipo-evento':
        $titolo = validate_input($input['titolo-tipo-evento']);
        if ($elementToEdit && strtolower($connection->get_tipo_evento($elementToEdit)['Titolo']) == strtolower($titolo)) {
            $exists = false;
        } else {
            $exists = count($connection->get_tipo_evento($titolo)) > 0;
        }
        $response = array('exists' => $exists);
        break;

    default:
        $response = array('error' => 'Invalid request type.');
}

echo json_encode($response);
$connection->close_DB_connection();
