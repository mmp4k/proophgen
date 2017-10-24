<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootEventGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class AggregateRootEventGeneratorSpec extends ObjectBehavior
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

    function it_generates_code()
    {
        $event = new Event('UserRegistered');
        $aggregateRoot = new AggregateRoot('Model\User', [$event]);

        $this->execute($aggregateRoot, $event)->fileContent()->shouldBe($this->expectedAggregateRootEventContent());
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
}
