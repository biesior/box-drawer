<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reimport data</title>
</head>
<body>
<form action="import-data-to-sql.php" method="post">
    <input type="hidden" name="confirm" value="1">
    <input type="submit" value="Import">
</form>
</body>
</html>
<?php
$confirm = $_POST['confirm'] ?? null;

if (!is_null($confirm)) {

    $conn = new PDO('sqlite:box-drawer-example-db.sqlite');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn->exec("DELETE FROM airports");
    $conn->exec("VACUUM");
    $conn->exec("DELETE FROM sqlite_sequence WHERE name='airports'");

    $conn->exec("
        INSERT INTO airports (name, icao, iata, address) VALUES ('Wrocław Airport', 'EPWR', 'WRO', 'Wrocław Strachowice');
        INSERT INTO airports (name, icao, iata, address) VALUES ('Berlin Tegel Airport', 'EDDT', 'TXL', 'Berlin Tegel');
        INSERT INTO airports (name, icao, iata, address) VALUES ('Warsaw Frederic Chopin Airport', 'EPWA', 'WAW', 'Warsaw');
        INSERT INTO airports (name, icao, iata, address) VALUES ('Munich International Airport ', 'EDDM', 'MUC','Munich');
        
        -- INSERT INTO airports (name, icao, iata, address) VALUES ('', '', '','');
");


//$stmt = $conn->prepare("INSERT INTO airports (name, icao, address) VALUES (:name, :icao, :address)");
//$stmt->bindParam('name', $name);
//$stmt->bindParam('icao', $icao);
//$stmt->bindParam('address', $address);

//for ($i = 1; $i <= 1000; $i++) {
//    $name = "Airport $i";
//    $icao = "AIR{$i}";
//    $address="Address {$i}";
//    $stmt->execute();
//}

    die("Data (re)imported");
}
