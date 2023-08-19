<?php
header('Content-Type: application/json');

$host = 'localhost';
$database = 'WebGis';
$username = 'postgres';
$password = 'geoserver';
$connection = new PDO("pgsql:host=$host;dbname=$database", $username, $password);

try {
    $query = "SELECT 
        statistic6m.*, gadm41_vnm_1.*,ST_asText(gadm41_vnm_1.geom) as geometry
        FROM statistic6m
        JOIN gadm41_vnm_1 ON statistic6m.gid = gadm41_vnm_1.gid AND  gadm41_vnm_1.engtype_1='City'
        ORDER BY statistic6m.numberofcar DESC, statistic6m.numberofcompleted DESC
LIMIT 5 
        ";
    $statement = $connection->prepare($query);
    $statement->execute();
    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
    // $queryGadm_1 = "SELECT * FROM gadm41_vnm_1";
    // $statementGetdataGadm1 = $connection->prepare($queryGadm_1);
    // $statementGetdataGadm1->execute();

    // $dataGadm_1 = $statementGetdataGadm1->fetchAll(PDO::FETCH_ASSOC);
    // foreach ($dataGadm_1 as $row) {
    //     $gid = json_encode($row['gid']);
    //     $randomCar = json_encode($row['engtype_1']) == 'City' ? rand(1000, 1500) : rand(100, 500);
    //     $numberOfCompleted = json_encode($row['engtype_1']) == 'City' ? rand(3000, 3000) : rand(100, 1000);
    //     $queryInsertData = "INSERT INTO
    //     --  statistic6m(gid,numberOfPopulation,numberOfCar,numberOfCompleted,)
    //     statistic6m(gid,numberOfCar,numberOfCompleted)
    //      VALUES ({$gid}, {$randomCar}, {$numberOfCompleted})
    //      ";
    //     $insertData = $connection->prepare($queryInsertData);
    //     $insertData->execute();
    // }
    // echo json_encode ($checkExisted);
} catch (\Throwable $th) {
    if ($th->getCode() == '42P01') {
        $query = "CREATE TABLE statistic6m(
            id SERIAL  PRIMARY KEY,
            gid INT NOT NULL,
            FOREIGN KEY(gid) REFERENCES gadm41_vnm_1(gid),
            -- numberOfPopulation INT NOT NULL,
            numberOfCar INT NOT NULL,
            numberOfCompleted int NOT NULL
            )";
        $statementCreateTable = $connection->prepare($query);
        $statementCreateTable->execute();

        $queryGadm_1 = "SELECT * FROM gadm41_vnm_1";
        $statementGetdataGadm1 = $connection->prepare($queryGadm_1);
        $statementGetdataGadm1->execute();

        $dataGadm_1 = $statementGetdataGadm1->fetchAll(PDO::FETCH_ASSOC);
        while ($data = $dataGadm_1) {
            echo ($data);
        }
    } else {
        echo ($th);
    }
}
?>