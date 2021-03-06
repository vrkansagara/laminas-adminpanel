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

$entry->setId('4f8706cf654c5-post-name');
$entry->setTitle('4f8706cf654c5 Post');
$entry->setAuthor($author);
$entry->setDraft(false);
$entry->setPublic(true);
$entry->setCreated(new DateTime('2006:03:01 04:57:57'));
$entry->setUpdated(new DateTime('2006:03:01 04:57:57'));
$entry->setTimezone('America/Chicago');
$entry->setTags([
    0 => 'personal',
    1 => 'programming',
    2 => 'thoughts',
    3 => 'literature',
    4 => 'draft',
    5 => 'conferences',
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
