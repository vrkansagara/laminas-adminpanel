<?php

use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$entry = new EntryEntity();
$author = new AuthorEntity();
$author->fromArray([
    'id' => 'aamilne',
    'name' => 'A.A. Milne',
    'email' => 'a.a@milne.com',
    'url' => 'http://milne.com',
]);

$entry->setId('4f8706cf6478a-post-name');
$entry->setTitle('4f8706cf6478a Post');
$entry->setAuthor($author);
$entry->setDraft(false);
$entry->setPublic(true);
$entry->setCreated(new DateTime('2009:04:18 23:07:07'));
$entry->setUpdated(new DateTime('2009:04:18 23:07:07'));
$entry->setTimezone('America/Chicago');
$entry->setTags([
    0 => 'personal',
    1 => 'programming',
    2 => 'thoughts',
    3 => 'literature',
    4 => 'children',
    5 => 'draft',
    6 => 'conferences',
    7 => 'php',
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
