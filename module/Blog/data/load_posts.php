<?php declare(strict_types=1);

$db = new PDO('sqlite:' . __DIR__ . '/Blog.db');
$fh = fopen(__DIR__ . '/posts.schema.sql', 'r');
while ($line = fread($fh, 4096)) {
    $line = trim($line);
    $db->exec($line);
}
fclose($fh);
