<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecInMemoryRepository;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class PhpSpecInMemoryRepositorySpec extends ObjectBehavior
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
        $idStrategy->modifyPhpClass(Argument::any())->shouldBeCalled();
        $idStrategy->phpSpecIdGenerator(Argument::any())->shouldBeCalled();
        $idStrategy->value()->willReturn('id');
        $this->execute($aggregateRoot)->filename()->shouldBe('./spec/Infrastructure/User/InMemorySpec.php');
        $this->execute($aggregateRoot)->fileContent()->shouldBe($this->expectedContent());
    }

    protected function expectedContent(): string {
        return "<?php
declare(strict_types=1);

namespace spec\Infrastructure\User;

use Infrastructure\User\InMemory;
use Model\User\Exception\UserNotFound;
use Model\UserRepository;
use Model\User;
use PhpSpec\ObjectBehavior;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prophecy\Argument;

class InMemorySpec extends ObjectBehavior {

\tpublic function it_can_get_user(User \$user) {
\t\t\$this->getWrappedObject()->data['id'] = \$user->getWrappedObject();
\t\t\$this->get(\$this->_id());
\t}

\tpublic function it_can_save_user(User \$user) {
\t\t\$user->id()->willReturn(\$this->_id());
\t\t\$this->save(\$user);
\t}

\tpublic function it_throw_exception_if_can_not_get_user() {
\t\t\$this->shouldThrow(UserNotFound::class)->during('get', [\$this->_id()]);
\t}

\tpublic function let() {
\t\t\$this->shouldImplement(UserRepository::class);
\t}
}
";
    }
}
