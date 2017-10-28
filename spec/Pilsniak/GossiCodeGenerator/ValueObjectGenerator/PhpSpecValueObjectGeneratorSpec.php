<?php

namespace spec\Pilsniak\GossiCodeGenerator\ValueObjectGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\ValueObjectGenerator\PhpSpecValueObjectGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\ValueObject;
use Prophecy\Argument;

class PhpSpecValueObjectGeneratorSpec extends ObjectBehavior
{
    function let()
    {
        $generator = new CodeFileGenerator([
            'generateDocblock' => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'declareStrictTypes' => true
        ]);

        $this->beConstructedWith(new PhpSpecValueObjectGenerator\PhpSpecValueObjectGenerator($generator));
    }

    function it_generates_phpspec_for_value_object()
    {
        $valueObject = new ValueObject('Model\ValueObject\Mail');;
        $response = $this->execute($valueObject);
        $response->shouldBeArray();
        $response->shouldHaveCount(1);
    }
}
