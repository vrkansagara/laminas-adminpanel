<?php declare(strict_types=1);

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

$entry->setId('4f8706cf637e0-post-name');
$entry->setTitle('4f8706cf637e0 Post');
$entry->setAuthor($author);
$entry->setDraft(false);
$entry->setPublic(true);
$entry->setCreated(new DateTime('2008:12:20 07:16:16'));
$entry->setUpdated(new DateTime('2008:12:20 07:16:16'));
$entry->setTimezone('America/Chicago');
$entry->setTags([
    0 => 'holiday',
    1 => 'personal',
    2 => 'literature',
    3 => 'children',
    4 => 'conferences',
    5 => 'php',
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
