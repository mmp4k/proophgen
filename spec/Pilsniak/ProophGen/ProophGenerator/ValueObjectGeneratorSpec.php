<?php

namespace spec\Pilsniak\ProophGen\ProophGenerator;

use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\Model\ValueObject;
use Pilsniak\ProophGen\ValueObjectExecuter;
use Pilsniak\ProophGen\ProophGenerator\ValueObjectGenerator;
use Pilsniak\ProophGen\ProophGenerator\ValueObjectGenerator\PhpSpecValueObjectExecuter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ValueObjectGeneratorSpec extends ObjectBehavior
{
    function let(ValueObjectExecuter $valueObjectExecuter, PhpSpecValueObjectExecuter $phpSpecValueObjectExecuter)
    {
        $this->beConstructedWith($valueObjectExecuter, $phpSpecValueObjectExecuter);
    }

    function it_generates_value_objects(ValueObjectExecuter $valueObjectExecuter, PhpSpecValueObjectExecuter $phpSpecValueObjectExecuter)
    {
        $file = <<<FILE
        <?php
        
        ?>
FILE;

        $valueObject = new ValueObject('Model\ValueObject\Mail');
        $valueObjectExecuter->execute($valueObject)->shouldBeCalled();
        $valueObjectExecuter->execute($valueObject)->willReturn([new FileToSave('', $file)]);
        $phpSpecValueObjectExecuter->execute($valueObject)->shouldBeCalled();
        $phpSpecValueObjectExecuter->execute($valueObject)->willReturn([new FileToSave('', $file)]);

        $fileToSave = $this->generate($valueObject);
        $fileToSave[0]->fileContent()->shouldBe($file);
        $fileToSave[1]->fileContent()->shouldBe($file);
    }
}
