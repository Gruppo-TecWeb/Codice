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

// Controllo connessione
if (!$connectionOk) {
    die(json_encode(array('error' => 'Connection failed.')));
}

$input = json_decode(file_get_contents('php://input'), true);
$titolo = validate_input($input['titolo']);

$exists = count($connection->get_tipo_evento($titolo)) > 0;

echo json_encode(array('exists' => $exists));

$connection->close_DB_connection();