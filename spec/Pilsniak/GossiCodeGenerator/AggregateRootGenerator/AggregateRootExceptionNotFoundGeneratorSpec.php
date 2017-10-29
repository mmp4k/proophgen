<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootExceptionNotFoundGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class AggregateRootExceptionNotFoundGeneratorSpec extends ObjectBehavior
{
    function let(IdStrategy $idStrategy)
    {
        $generator = new CodeFileGenerator([
            'generateDocblock' => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'declareStrictTypes' => true
        ]);
        $this->beConstructedWith($generator, $idStrategy);
    }

    function it_generates_code(IdStrategy $idStrategy)
    {
        $aggregateRoot = new AggregateRoot('Model\User', [new Event('UserRegistered')]);

        $idStrategy->type()->shouldBeCalled()->willReturn('string');
        $idStrategy->convertToString('$id')->shouldBeCalled()->willReturn('$id');
        $idStrategy->modifyPhpClass(Argument::any())->shouldBeCalled();
        $this->execute($aggregateRoot)->fileContent()->shouldBe($this->expectedAggregateRootExceptionNotFoundContent());
    }

    private function expectedAggregateRootExceptionNotFoundContent()
    {
        return "<?php
declare(strict_types=1);

namespace Model\User\Exception;

class UserNotFound extends \DomainException {

\tpublic static function withId(string \$id): self {
\t\treturn new self(sprintf(\"User with ID %s does not exists.\", \$id));
\t}
}
";
    }
}
