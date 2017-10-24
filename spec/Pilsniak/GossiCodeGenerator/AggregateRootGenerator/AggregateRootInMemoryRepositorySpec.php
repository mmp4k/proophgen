<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootInMemoryRepository;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class AggregateRootInMemoryRepositorySpec extends ObjectBehavior
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

class InMemory implements UserRepository {

\tpublic \$data = [];

\tpublic function get(string \$id): User {
\t\tif (!isset(\$this->data[\$id])) {
\t\t\tthrow UserNotFound::withId(\$id);
\t\t}
\t\treturn \$this->data[\$id];
\t}

\tpublic function save(User \$user): void {
\t\t\$this->data[\$user->id()] = \$user;
\t}
}
"
            ;
    }
}
