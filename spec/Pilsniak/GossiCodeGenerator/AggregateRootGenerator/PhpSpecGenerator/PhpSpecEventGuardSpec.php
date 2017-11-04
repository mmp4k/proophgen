<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecEventGuard;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class PhpSpecEventGuardSpec extends ObjectBehavior
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
        $this->execute($aggregateRoot, $event)->filename()->shouldBe('./spec/Model/User/Guard/UserRegisteredGuardSpec.php');
        $this->execute($aggregateRoot, $event)->fileContent()->shouldBe($this->expectedContent());
    }

    protected function expectedContent(): string
    {
        return "<?php
declare(strict_types=1);

namespace spec\Model\User\Guard;

use Model\User\Guard\UserRegisteredGuard;
use Model\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserRegisteredGuardSpec extends ObjectBehavior {

\tpublic function it_does_not_throws_exception(User \$user) {
\t\t\$this->throwExceptionIfNotPossible(\$user);
\t}

\tpublic function it_throws_exception(User \$user) {
\t\t\$this->throwExceptionIfNotPossible(\$user);
\t}
}
";
    }
}
