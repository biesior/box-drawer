<?php
declare(strict_types=1);
$pdo = new PDO('sqlite:resources/box-drawer-example-db.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$res = $pdo->query('SELECT * FROM airports');
$dbData = $res->fetchAll();
return $dbData;