<?php

namespace spec\Pilsniak\FlySystem;

use Pilsniak\FlySystem\FileSystem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileSystemSpec extends ObjectBehavior
{
    function let(\League\Flysystem\Filesystem $filesystem)
    {
        $this->beConstructedWith($filesystem);
        $this->shouldImplement(\Pilsniak\ProophGen\FileSystem::class);
    }

    function it_saves_file(\League\Flysystem\Filesystem $filesystem)
    {
        $filesystem->put('filename.txt', 'content')->shouldBeCalled();
        $this->save('filename.txt', 'content');
    }
}
