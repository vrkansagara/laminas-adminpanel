<?php

declare(strict_types=1);

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

$entry->setId('4f8706cf64462-post-name');
$entry->setTitle('4f8706cf64462 Post');
$entry->setAuthor($author);
$entry->setDraft(false);
$entry->setPublic(true);
$entry->setCreated(new DateTime('2010:06:04 22:24:24'));
$entry->setUpdated(new DateTime('2010:06:04 22:24:24'));
$entry->setTimezone('America/Chicago');
$entry->setTags([
    0 => 'personal',
    1 => 'programming',
    2 => 'thoughts',
    3 => 'conferences',
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
