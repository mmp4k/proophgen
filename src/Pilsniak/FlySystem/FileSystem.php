<?php

namespace Pilsniak\FlySystem;

class FileSystem implements \Pilsniak\ProophGen\FileSystem
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    private $filesystem;

    public function __construct(\League\Flysystem\Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function save(string $filename, string $filecontent)
    {
        $this->filesystem->put($filename, $filecontent);
    }
}
