<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootEventSourcedRepository;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class AggregateRootEventSourcedRepositorySpec extends ObjectBehavior
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
        $aggregateRoot = new AggregateRoot('Model\User', [new Event('UserRegistered')]);

        $this->execute($aggregateRoot)->fileContent()->shouldBe($this->expectedContent());
    }

    private function expectedContent()
    {
        return "<?php
declare(strict_types=1);

namespace Infrastructure\User;

use Model\User;
use Model\User\Exception\UserNotFound;
use Model\UserRepository;
use Prooph\EventSourcing\Aggregate\AggregateRepository;

class EventSourced implements UserRepository {

\tpublic \$aggregateRepository;

\tpublic function __construct(AggregateRepository \$aggregateRepository) {
\t\t\$this->aggregateRepository = \$aggregateRepository;
\t}

\tpublic function get(string \$id): User {
\t\t\$row = \$this->aggregateRepository->getAggregateRoot(\$id);
\t\tif (!\$row) {
\t\t\tthrow UserNotFound::withId(\$id);
\t\t}
\t\treturn \$row;
\t}

\tpublic function save(User \$user): void {
\t\t\$this->aggregateRepository->saveAggregateRoot(\$user);
\t}
}
";
    }
}
