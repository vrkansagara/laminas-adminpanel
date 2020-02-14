<?php

declare(strict_types=1);

namespace PhlyBlog\Compiler\TestAsset;

use PhlyBlog\Compiler\WriterInterface;

class MockWriter implements WriterInterface
{
    public $files = [];

    public function write($filename, $data)
    {
        $this->files[$filename] = $data;
    }
}
