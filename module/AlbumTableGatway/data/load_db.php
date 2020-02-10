<?php

// sqlite data/AlbumTableGatway.db < data/schema.sql
$db = new PDO('sqlite:' . realpath(__DIR__) . '/AlbumTableGatway.db');
$fh = fopen(__DIR__ . '/schema.sql', 'r');
while ($line = fread($fh, 4096)) {
    $db->exec($line);
}
fclose($fh);
