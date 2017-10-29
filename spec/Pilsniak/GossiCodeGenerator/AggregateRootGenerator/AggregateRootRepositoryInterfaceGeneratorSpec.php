<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootRepositoryInterfaceGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class AggregateRootRepositoryInterfaceGeneratorSpec extends ObjectBehavior
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
        $idStrategy->modifyPhpClass(Argument::any())->shouldBeCalled();
        $this->execute($aggregateRoot)->fileContent()->shouldBe($this->expectedAggregateRootRepositoryInterfaceContent());
    }

    private function expectedAggregateRootRepositoryInterfaceContent()
    {
        return "<?php
declare(strict_types=1);

namespace Model;

interface UserRepository {

\tpublic function get(string \$id): User;

\tpublic function save(User \$user);
}
";
    }
}
