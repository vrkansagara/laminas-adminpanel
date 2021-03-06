<?php

use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$entry = new EntryEntity();
$author = new AuthorEntity();
$author->fromArray([
    'id' => 'jdoe',
    'name' => 'John Doe',
    'email' => 'john@doe.com',
    'url' => 'http://john.doe.com',
]);

$entry->setId('4f8706cf62229-post-name');
$entry->setTitle('4f8706cf62229 Post');
$entry->setAuthor($author);
$entry->setDraft(false);
$entry->setPublic(true);
$entry->setCreated(new DateTime('2006:07:23 22:18:18'));
$entry->setUpdated(new DateTime('2006:07:23 22:18:18'));
$entry->setTimezone('America/Chicago');
$entry->setTags([
    0 => 'personal',
    1 => 'programming',
    2 => 'thoughts',
    3 => 'war',
    4 => 'literature',
    5 => 'children',
    6 => 'draft',
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
