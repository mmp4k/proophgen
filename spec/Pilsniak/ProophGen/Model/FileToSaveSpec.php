<?php

namespace spec\Pilsniak\ProophGen\Model;

use Pilsniak\ProophGen\Model\FileToSave;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileToSaveSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('filename', 'filecontent');
    }

    function it_returns_filename()
    {
        $this->filename()->shouldBe('filename');
    }

    function it_return_file_content()
    {
        $this->fileContent()->shouldBe('filecontent');
    }
}
