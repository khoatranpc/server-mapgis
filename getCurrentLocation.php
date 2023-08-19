<?php
header('Content-Type: application/json');

$host = 'localhost';
$database = 'WebGis';
$username = 'postgres';
$password = 'geoserver';
$connection = new PDO("pgsql:host=$host;dbname=$database", $username, $password);

$longitude = htmlspecialchars($_GET["longitude"]);
$latitude = htmlspecialchars($_GET["latitude"]);
// find current location click
if ($longitude && $latitude) {
    // $query = "Select *, ST_Area(geom)/1000000 AS area_km2, ST_asText(geom) as geometry from gadm41_vnm_1 where ST_Within('POINT({$longitude} {$latitude})'::geometry,gadm41_vnm_1.geom::geometry)";

    $query = "SELECT 
    statistic6m.*, gadm41_vnm_1.*, ST_Area(geom)/1000000 AS area_km2, ST_asText(gadm41_vnm_1.geom) as geometry
    FROM statistic6m
    JOIN gadm41_vnm_1 ON statistic6m.gid = gadm41_vnm_1.gid AND ST_Within('POINT({$longitude} {$latitude})'::geometry,gadm41_vnm_1.geom::geometry)";
    $statement = $connection->prepare($query);
    $statement->execute();
    $data = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
}
?>