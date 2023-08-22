<?php
header('Content-Type: application/json');

$host = 'localhost';
$database = 'WebGis';
$username = 'postgres';
$password = 'geoserver';
$connection = new PDO("pgsql:host=$host;dbname=$database", $username, $password);

    $query = "SELECT 
        statistic6m.*, gadm41_vnm_1.*,ST_asText(gadm41_vnm_1.geom) as geometry
        FROM statistic6m
        JOIN gadm41_vnm_1 ON statistic6m.gid = gadm41_vnm_1.gid AND  gadm41_vnm_1.engtype_1='City'
LIMIT 5 
        ";
    $statement = $connection->prepare($query);
    $statement->execute();
    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);

?>