<?php

declare(strict_types=1);

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

$entry->setId('4f8706cf638fe-post-name');
$entry->setTitle('4f8706cf638fe Post');
$entry->setAuthor($author);
$entry->setDraft(false);
$entry->setPublic(true);
$entry->setCreated(new DateTime('2004:07:29 09:13:13'));
$entry->setUpdated(new DateTime('2004:07:29 09:13:13'));
$entry->setTimezone('America/Chicago');
$entry->setTags([
    0 => 'personal',
    1 => 'literature',
    2 => 'conferences',
    3 => 'php',
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
