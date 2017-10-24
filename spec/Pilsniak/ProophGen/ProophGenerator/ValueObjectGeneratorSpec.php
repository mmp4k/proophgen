<?php

namespace spec\Pilsniak\ProophGen\ProophGenerator;

use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\Model\ValueObject;
use Pilsniak\ProophGen\ValueObjectExecuter;
use Pilsniak\ProophGen\ProophGenerator\ValueObjectGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ValueObjectGeneratorSpec extends ObjectBehavior
{
    function let(ValueObjectExecuter $valueObjectExecuter)
    {
        $this->beConstructedWith($valueObjectExecuter);
    }

    function it_generates_value_objects(ValueObjectExecuter $valueObjectExecuter)
    {
        $file = <<<FILE
        <?php
        
        ?>
FILE;

        $valueObject = new ValueObject('Model\ValueObject\Mail');
        $valueObjectExecuter->execute($valueObject)->shouldBeCalled();
        $valueObjectExecuter->execute($valueObject)->willReturn($file);

        $fileToSave = $this->generate($valueObject);
        $fileToSave[0]->fileContent()->shouldBe($file);
    }
}
