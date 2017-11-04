<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecAggregateCode;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class PhpSpecAggregateCodeSpec extends ObjectBehavior
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

    function it_generates_code_with_event_non_creator(IdStrategy $idStrategy)
    {
        $content = "<?php
declare(strict_types=1);

namespace spec\Model;

use Model\User;
use Model\User\Guard\UserRegisteredGuard;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserSpec extends ObjectBehavior {

\tpublic function it_can_registerUser(UserRegisteredGuard \$userRegisteredGuard) {
\t\t\$this->registerUser(\$userRegisteredGuard);
\t}

\tpublic function it_returns_id() {
\t\t\$this->id()->shouldBe(\$this->_id());
\t}
}
";
        $aggregateRoot = new AggregateRoot('Model\User', [new Event('UserRegistered')]);
        $idStrategy->modifyPhpClass(Argument::any())->shouldBeCalled();
        $idStrategy->phpSpecIdGenerator(Argument::any())->shouldBeCalled();
        $this->execute($aggregateRoot)->filename()->shouldBe('./spec/Model/UserSpec.php');
        $this->execute($aggregateRoot)->fileContent()->shouldBe($content);
    }

    function it_generates_code_with_event_creator(IdStrategy $idStrategy)
    {
        $content = "<?php
declare(strict_types=1);

namespace spec\Model;

use Model\User;
use Model\User\Guard\UserRegisteredGuard;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserSpec extends ObjectBehavior {

\tpublic function it_returns_id() {
\t\t\$this->id()->shouldBe(\$this->_id());
\t}

\tpublic function let(UserRegisteredGuard \$userRegisteredGuard) {
\t\t\$this->beConstructedThrough('registerUser', [\$userRegisteredGuard, \$this->_id()]);
\t}
}
";
        $aggregateRoot = new AggregateRoot('Model\User', [new Event('UserRegistered', true)]);
        $idStrategy->modifyPhpClass(Argument::any())->shouldBeCalled();
        $idStrategy->phpSpecIdGenerator(Argument::any())->shouldBeCalled();
        $this->execute($aggregateRoot)->filename()->shouldBe('./spec/Model/UserSpec.php');
        $this->execute($aggregateRoot)->fileContent()->shouldBe($content);
    }

}
