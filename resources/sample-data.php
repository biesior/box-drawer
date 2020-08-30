<?php
declare(strict_types=1);

// --- SAMPLE DATA to test box drawer

$multiLineText = "This is multiline text
Which \e[1;32mshould be\e[0m displayed properly.
And we hope it will...

Let's \e[1;32mhope\e[0m it finally \e[1;32mwill\e[0m work as expected!!!
Let's hope it will!";


$multilineAligned = "

                           Hello World!
                        I hope you are happy
                  This text is centered by assigment.


";

$flatData = ['Header',
    'Hello, World!',
    'Box drawing is alive after all these years!!!! :D',
    '',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum arcu.'
];

$fakeRes = [
    ['uid' => 1, 'very_long_header' => 'Foo', 'price' => 19.99],
    ['uid' => 2, 'very_long_header' => 'Bar', 'price' => 2999999.99],
    ['uid' => 3, 'very_long_header' => 'Foo', 'price' => 9.99],
    ['uid' => 4, 'very_long_header' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum arcu.', 'price' => 9.99],
    ['uid' => 5, 'very_long_header' => 'Foo', 'price' => 9.99],
    ['uid' => 6, 'very_long_header' => 'ażźćńłąóę', 'price' => 9.99],
];

$flatArrays = [
    [1, 'Foo', 19.99],
    [2, 'Bar', 2999999.99],
    [3, 'Foo', 9.99],
    [4, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum arcu.', 9.99],
    [5, 'Foo', 9.99],
    [6, 'ażźćńłąóę', 9.99],
];

$fakeRes = [
    ['uid' => 1, 'ślążek' => 'Foo', 'price' => 19.99],
    ['uid' => 2, 'ślążek' => 'Bar', 'price' => 2999999.99],
    ['uid' => 3, 'ślążek' => 'Foo', 'price' => 9.99],
    ['uid' => 4, 'ślążek' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum arcu.', 'price' => 9.99],
    ['uid' => 5, 'ślążek' => 'Foo', 'price' => 9.99],
    ['uid' => 6, 'ślążek' => 'ażźćńłąóę', 'price' => 9.99],
];
$walterData = [
    ['idprocess' => 'c-cron-0001', 'idform' => 'c-cron-0001'],
    ['idprocess' => 'c-cron-0004', 'idform' => 'c-cron-0004'],
    ['idprocess' => 'c-cron-0005', 'idform' => 'c-cron-0005'],
    ['idprocess' => 'c-cron-0006', 'idform' => 'c-cron-0006'],
    ['idprocess' => 'c-cron-0007', 'idform' => 'c-cron-0007']
];


// --- DB sample START: fetch data from Db as an associative array
$pdo = new PDO('sqlite:resources/box-drawer-example-db.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$res = $pdo->query('SELECT * FROM airports');
$dbData = $res->fetchAll();
// --- DB sample END