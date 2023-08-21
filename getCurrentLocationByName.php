<?php
header('Content-Type: application/json');

$host = 'localhost';
$database = 'WebGis';
$username = 'postgres';
$password = 'geoserver';
$connection = new PDO("pgsql:host=$host;dbname=$database", $username, $password);

$name = htmlspecialchars($_GET["name"]);
if($name){
$query = "SELECT 
statistic6m.*, gadm41_vnm_1.*,ST_asText(gadm41_vnm_1.geom) as geometry
FROM statistic6m
JOIN gadm41_vnm_1 ON statistic6m.gid = gadm41_vnm_1.gid AND  varname_1 ILIKE '%{$name}%'";
$statement = $connection->prepare($query);
$statement->execute();
$data = $statement->fetchAll(PDO::FETCH_ASSOC);
// Set the response content type to JSON
header('Content-Type: application/json');

// Output the data as JSON
echo json_encode($data);
}else{
    echo json_encode([]);
}

$connection = null;
?>