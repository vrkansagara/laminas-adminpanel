<?php declare(strict_types=1);

namespace PhlyBlog\Compiler;

interface WriterInterface
{
    public function write($filename, $data);
}
