<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootEventGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\IdStrategy;
use Prophecy\Argument;

class AggregateRootEventGeneratorSpec extends ObjectBehavior
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
        $event = new Event('UserRegistered');
        $aggregateRoot = new AggregateRoot('Model\User', [$event]);

        $idStrategy->type()->shouldBeCalled()->willReturn('string');
        $idStrategy->convertToString('$id')->shouldBeCalled()->willReturn('$id');
        $idStrategy->modifyPhpClass(Argument::any())->shouldBeCalled();
        $this->execute($aggregateRoot, $event)->fileContent()->shouldBe($this->expectedAggregateRootEventContent());
    }

    function it_generates_code_for_event_creator(IdStrategy $idStrategy)
    {
        $event = new Event('UserRegistered', true);
        $aggregateRoot = new AggregateRoot('Model\User', [$event]);

        $idStrategy->type()->shouldBeCalled()->willReturn('string');
        $idStrategy->convertToType('$this->aggregateId()')->shouldBeCalled()->willReturn('$this->aggregateId()');
        $idStrategy->convertToString('$id')->shouldBeCalled()->willReturn('$id');
        $idStrategy->modifyPhpClass(Argument::any())->shouldBeCalled();
        $this->execute($aggregateRoot, $event)->fileContent()->shouldBe($this->expectedAggregateRootEventCreatorContent());
    }

    private function expectedAggregateRootEventContent()
    {
        return "<?php
declare(strict_types=1);

namespace Model\User\Event;

use Prooph\EventSourcing\AggregateChanged;

class UserRegistered extends AggregateChanged {

\tpublic static function create(string \$id): self {
\t\treturn self::occur(\$id, []);
\t}
}
";
    }

    private function expectedAggregateRootEventCreatorContent()
    {
        return "<?php
declare(strict_types=1);

namespace Model\User\Event;

use Prooph\EventSourcing\AggregateChanged;

class UserRegistered extends AggregateChanged {

\tpublic static function create(string \$id): self {
\t\treturn self::occur(\$id, []);
\t}

\tpublic function id(): string {
\t\treturn \$this->aggregateId();
\t}
}
";
    }
}
