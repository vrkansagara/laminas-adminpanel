<?php

use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$entry = new EntryEntity();
$author = new AuthorEntity();
$author->fromArray([
    'id' => 'custer',
    'name' => 'George Armstrong Custer',
    'email' => 'me@gacuster.com',
    'url' => 'http://www.gacuster.com',
]);

$entry->setId('4f8706cf65e71-post-name');
$entry->setTitle('4f8706cf65e71 Post');
$entry->setAuthor($author);
$entry->setDraft(false);
$entry->setPublic(true);
$entry->setCreated(new DateTime('2010:10:24 15:09:09'));
$entry->setUpdated(new DateTime('2010:10:24 15:09:09'));
$entry->setTimezone('America/Chicago');
$entry->setTags([
    0 => 'personal',
    1 => 'war',
    2 => 'literature',
    3 => 'conferences',
    4 => 'php',
]);

$body = <<<'EOT'
This is it!
EOT;
$entry->setBody($body);

$extended = <<<'EOT'
This is the extended portion of the entry.
EOT;
$entry->setExtended($extended);

return $entry;
