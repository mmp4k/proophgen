<?php

namespace Pilsniak\ProophGen\Model;

class FileToSave
{
    /**
     * @var string
     */
    private $filename;
    /**
     * @var string
     */
    private $fileContent;

    public function __construct(string $filename, string $fileContent)
    {
        $this->filename = $filename;
        $this->fileContent = $fileContent;
    }

    public function filename(): string
    {
        return $this->filename;
    }

    public function fileContent(): string
    {
        return $this->fileContent;
    }
}
