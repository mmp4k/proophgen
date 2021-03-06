<?php

namespace spec\Pilsniak\GossiCodeGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\ValueObjectGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\ValueObject;
use Pilsniak\ProophGen\ValueObjectExecuter;
use Prophecy\Argument;

class ValueObjectGeneratorSpec extends ObjectBehavior
{
    function let()
    {
        $generator = new CodeFileGenerator([
            'generateDocblock' => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'declareStrictTypes' => true
        ]);

        $this->beConstructedWith(new ValueObjectGenerator\ValueObjectGenerator($generator));
    }

    function it_implements_correct_interface()
    {
        $this->shouldImplement(ValueObjectExecuter::class);
    }

    function it_generate_file_to_value_object()
    {
        $valueObject = new ValueObject('Model\ValueObject\Name');
        $response = $this->execute($valueObject);
        $response->shouldBeArray();
        $response->shouldHaveCount(1);
    }
}
