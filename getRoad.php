<?php

$host = 'localhost';
$database = 'WebGis';
$username = 'postgres';
$password = 'geoserver';
$connection = new PDO("pgsql:host=$host;dbname=$database", $username, $password);
if (!$connection) {
    exit;
} else {
    $query = "SELECT *,ST_asText(road_wgs.geom) as geometry,gadm41_vnm_1.name_1 FROM road_wgs
    JOIN gadm41_vnm_1 ON ST_Within(road_wgs.geom, gadm41_vnm_1.geom)";
    $statement = $connection->prepare($query);
    $statement->execute();

    // Fetch data and store as an associative array
    $data = $statement->fetchAll(PDO::FETCH_ASSOC);

    $connection = null;

    // Set the response content type to JSON
    header('Content-Type: application/json');

    // Output the data as JSON
    echo json_encode($data);
}
?>