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

$entry->setId('4f8706cf6594d-post-name');
$entry->setTitle('4f8706cf6594d Post');
$entry->setAuthor($author);
$entry->setDraft(false);
$entry->setPublic(true);
$entry->setCreated(new DateTime('2010:02:22 12:35:35'));
$entry->setUpdated(new DateTime('2010:02:22 12:35:35'));
$entry->setTimezone('America/Chicago');
$entry->setTags([
    0 => 'holiday',
    1 => 'programming',
    2 => 'thoughts',
    3 => 'war',
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
