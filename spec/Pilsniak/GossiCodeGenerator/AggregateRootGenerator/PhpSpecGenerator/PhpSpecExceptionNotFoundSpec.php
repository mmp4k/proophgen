<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecExceptionNotFound;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class PhpSpecExceptionNotFoundSpec extends ObjectBehavior
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
    function it_is_initializable(IdStrategy $idStrategy)
    {
        $aggregateRoot = new AggregateRoot('Model\User', [new Event('UserRegistered')]);
        $idStrategy->modifyPhpClass(Argument::any())->shouldBeCalled();
        $idStrategy->phpSpecIdGenerator(Argument::any())->shouldBeCalled();
        $this->execute($aggregateRoot)->filename()->shouldBe('./spec/Model/User/Exception/UserNotFoundSpec.php');
        $this->execute($aggregateRoot)->fileContent()->shouldBe($this->expectedContent());
    }

    protected function expectedContent(): string {
        return "<?php
declare(strict_types=1);

namespace spec\Model\User\Exception;

use Model\User\Exception\UserNotFound;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserNotFoundSpec extends ObjectBehavior {

\tpublic function it_is_created_with_id() {
\t\t\$this->beConstructedThrough('withId', [\$this->_id()]);
\t\t\$this->getMessage()->shouldBe('User with ID id does not exists.');
\t}

\tpublic function it_is_domain_exception() {
\t\t\$this->shouldImplement(\DomainException::class);
\t}
}
";
    }
}
