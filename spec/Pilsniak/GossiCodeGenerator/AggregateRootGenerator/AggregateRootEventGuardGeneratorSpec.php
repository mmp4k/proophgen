<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootEventGuardGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class AggregateRootEventGuardGeneratorSpec extends ObjectBehavior
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

        $this->execute($aggregateRoot, $event)->fileContent()->shouldBe($this->expectedCode());
    }

    protected function expectedCode()
    {
        return "<?php
declare(strict_types=1);

namespace Model\User\Guard;

use Model\User;

class UserRegisteredGuard {

\tpublic function throwExceptionIfNotPossible(User \$user) {
\t}
}
";

    }
}
