<?php

header("Content-Type: application/json");
$data = json_decode(file_get_contents('php://input'), true);

// directory path ../../../../ with this demo (adjust with your own application structure)
$root = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . 'xampp/htdocs/adid/';
$targetDir = $root . "images/";

$fields = array();
foreach ($data as $item) {
    $fields["file"] = $item["file"];
    if (file_exists($targetDir . "" . $fields["file"]))
        unlink($targetDir . "" . $fields["file"]);
}


echo json_encode(array('status' => 'success', 'message' => "record removed"));
?>