<?php

namespace Pilsniak\ProophGen;

interface FileSystem
{
    public function save(string $filename, string $filecontent);
}
