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

$entry->setId('4f8706cf658c2-post-name');
$entry->setTitle('4f8706cf658c2 Post');
$entry->setAuthor($author);
$entry->setDraft(false);
$entry->setPublic(true);
$entry->setCreated(new DateTime('2009:04:16 23:17:17'));
$entry->setUpdated(new DateTime('2009:04:16 23:17:17'));
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
