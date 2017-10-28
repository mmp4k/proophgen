<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecAggregateCode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PhpSpecAggregateCodeSpec extends ObjectBehavior
{
    function let()
    {
        $generator = new CodeFileGenerator([
            'generateDocblock' => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'declareStrictTypes' => true
        ]);

        $this->beConstructedWith($generator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PhpSpecAggregateCode::class);
    }
}
