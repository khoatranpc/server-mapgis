<?php

$host = 'localhost';
$database = 'WebGis';
$username = 'postgres';
$password = 'geoserver';
$connection = new PDO("pgsql:host=$host;dbname=$database", $username, $password);
if (!$connection) {
    exit;
} else {
    $query = "SELECT *,ST_asText(geom) as geometry FROM gadm41_vnm_1";
    $statement = $connection->prepare($query);
    $statement->execute();

    // Fetch data and store as an associative array
    $data = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Select * from gadm41_vnm_1 where ST_Within('POINT(105.75724074530044

    // 21.021741163182586)'::geometry,gadm41_vnm_1.geom::geometry);
    // --select * from gadm41_vnm_1;

    // Close the database connection
    $connection = null;

    // Set the response content type to JSON
    header('Content-Type: application/json');

    // Output the data as JSON
    echo json_encode($data);
}
?>