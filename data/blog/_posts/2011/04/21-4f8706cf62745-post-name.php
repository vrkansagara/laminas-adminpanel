<?php

use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$entry = new EntryEntity();
$author = new AuthorEntity();
$author->fromArray([
    'id' => 'crazyhorse',
    'name' => 'Crazy Horse',
    'email' => 'crazyhorse@siouxnation.org',
    'url' => 'http://crazyhorse.siouxnation.org',
]);

$entry->setId('4f8706cf62745-post-name');
$entry->setTitle('4f8706cf62745 Post');
$entry->setAuthor($author);
$entry->setDraft(false);
$entry->setPublic(true);
$entry->setCreated(new DateTime('2011:04:21 03:07:07'));
$entry->setUpdated(new DateTime('2011:04:21 03:07:07'));
$entry->setTimezone('America/Chicago');
$entry->setTags([
    0 => 'holiday',
    1 => 'personal',
    2 => 'programming',
    3 => 'thoughts',
    4 => 'war',
    5 => 'literature',
    6 => 'children',
    7 => 'draft',
    8 => 'conferences',
    9 => 'php',
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
