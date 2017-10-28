<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecEventSourced;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class PhpSpecEventSourcedSpec extends ObjectBehavior
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
        $content = "<?php
declare(strict_types=1);

namespace spec\Infrastructure\User;

use Infrastructure\User\EventSourced;
use Model\User\Exception\UserNotFound;
use Model\UserRepository;
use Model\User;
use PhpSpec\ObjectBehavior;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prophecy\Argument;

class EventSourcedSpec extends ObjectBehavior {

\tpublic function it_can_get_user(AggregateRepository \$repository, User \$user) {
\t\t\$repository->getAggregateRoot('id')->shouldBeCalled();
\t\t\$repository->getAggregateRoot('id')->willReturn(\$user);
\t\t\$this->get('id')->shouldBe(\$user);
\t}

\tpublic function it_can_save_user(AggregateRepository \$repository, User \$user) {
\t\t\$repository->saveAggregateRoot(\$user)->shouldBeCalled();
\t\t\$this->save(\$user);
\t}

\tpublic function it_throw_exception_if_can_not_get_user(AggregateRepository \$repository) {
\t\t\$repository->getAggregateRoot('id')->shouldBeCalled();
\t\t\$repository->getAggregateRoot('id')->willReturn(null);
\t\t\$this->shouldThrow(UserNotFound::class)->during('get', ['id']);
\t}

\tpublic function let(AggregateRepository \$repository) {
\t\t\$this->beConstructedWith(\$repository);
\t\t\$this->shouldImplement(UserRepository::class);
\t}
}
";

        $aggregateRoot = new AggregateRoot('Model\User', [new Event('UserRegistered')]);
        $this->execute($aggregateRoot)->filename()->shouldBe('./spec/Infrastructure/User/EventSourcedSpec.php');
        $this->execute($aggregateRoot)->fileContent()->shouldBe($content);
    }
}
