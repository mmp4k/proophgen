<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecEvent;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class PhpSpecEventSpec extends ObjectBehavior
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
        $idStrategy->modifyPhpClass(Argument::any())->shouldBeCalled();
        $idStrategy->phpSpecIdGenerator(Argument::any())->shouldBeCalled();
        $idStrategy->value()->willReturn('id');
        $this->execute($aggregateRoot, $event)->filename()->shouldBe('./spec/Model/User/Event/UserRegisteredSpec.php');
        $this->execute($aggregateRoot, $event)->fileContent()->shouldBe($this->expectedContent());
    }

    protected function expectedContent(): string
    {
        return "<?php
declare(strict_types=1);

namespace spec\Model\User\Event;

use Model\User\Event\UserRegistered;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserRegisteredSpec extends ObjectBehavior {

\tpublic function it_is_created_with_id() {
\t\t\$this->beConstructedThrough('create', [\$this->_id()]);
\t\t\$this->aggregateId()->shouldBe('id');
\t}
}
";
    }
}
