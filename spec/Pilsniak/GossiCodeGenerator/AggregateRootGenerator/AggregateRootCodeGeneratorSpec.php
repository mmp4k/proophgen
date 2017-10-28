<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootCodeGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class AggregateRootCodeGeneratorSpec extends ObjectBehavior
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

        $this->execute($aggregateRoot)->fileContent()->shouldBe($this->expectedAggregateRootContent());
    }

    function it_generates_code_when_event_is_creator()
    {
        $aggregateRoot = new AggregateRoot('Model\User', [new Event('UserRegistered', true)]);

        $this->execute($aggregateRoot)->fileContent()->shouldBe($this->expectedAggregateRootWithEventCreatorContent());
    }
    private function expectedAggregateRootWithEventCreatorContent()
    {
        return "<?php
declare(strict_types=1);

namespace Model;

use Model\User\Event\UserRegistered;
use Model\User\Guard\UserRegisteredGuard;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

class User extends AggregateRoot {

\tprivate \$id;

\tpublic static function registerUser(UserRegisteredGuard \$userRegisteredGuard, string \$id): self {
\t\t\$userRegisteredGuard->throwExceptionIfNotPossible();

\t\t\$self = new self;
\t\t\$self->recordThat(UserRegistered::create(\$id));
\t\treturn \$self;
\t}

\tpublic function id(): string {
\t\treturn \$this->id;
\t}

\tprotected function aggregateId(): string {
\t\treturn \$this->id;
\t}

\tprotected function apply(AggregateChanged \$event): void {
\t\tswitch (get_class(\$event)) {
\t\t\tcase UserRegistered::class:
\t\t\t\t\$this->whenUserRegistered(\$event);
\t\t\t\tbreak;
\t\t}
\t}

\tprivate function whenUserRegistered(UserRegistered \$event): void {
\t\t\$this->id = \$event->aggregateId();
\t}
}
";
    }

    private function expectedAggregateRootContent()
    {
        return "<?php
declare(strict_types=1);

namespace Model;

use Model\User\Event\UserRegistered;
use Model\User\Guard\UserRegisteredGuard;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

class User extends AggregateRoot {

\tprivate \$id;

\tpublic function id(): string {
\t\treturn \$this->id;
\t}

\tpublic function registerUser(UserRegisteredGuard \$userRegisteredGuard): void {
\t\t\$userRegisteredGuard->throwExceptionIfNotPossible(\$this);

\t\t\$this->recordThat(UserRegistered::create(\$this->id));
\t}

\tprotected function aggregateId(): string {
\t\treturn \$this->id;
\t}

\tprotected function apply(AggregateChanged \$event): void {
\t\tswitch (get_class(\$event)) {
\t\t\tcase UserRegistered::class:
\t\t\t\t\$this->whenUserRegistered(\$event);
\t\t\t\tbreak;
\t\t}
\t}

\tprivate function whenUserRegistered(UserRegistered \$event): void {
\t}
}
";
    }
}
