<?php

declare(strict_types=1);

namespace PhlyBlog\Compiler;

class ResponseFile
{
    protected $filename;

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }
}
