<?php
header("Content-Type:application/json");

require __DIR__."/controllers/User.php";
require __DIR__."/controllers/Parcel.php";
require __DIR__."/Database.php";

$parts = explode("/", $_SERVER["REQUEST_URI"]);
$database = new Database;

$resource = $parts[1];
$id = $parts[2] ?? null;

switch($resource) {
    case "users":
        (new User($database))->processRequest($_SERVER["REQUEST_METHOD"], $id);
        break;
    case "parcels":
        (new Parcel($database))->processRequest($_SERVER["REQUEST_METHOD"], $id);
        break;
    default:
        http_response_code(404);
        exit();
}

?>