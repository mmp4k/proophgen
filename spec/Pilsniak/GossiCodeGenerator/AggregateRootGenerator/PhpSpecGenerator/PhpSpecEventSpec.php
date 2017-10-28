<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecEvent;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class PhpSpecEventSpec extends ObjectBehavior
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

namespace spec\Model\User\Event;

use Model\User\Event\UserRegistered;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserRegisteredSpec extends ObjectBehavior {

\tpublic function it_is_created_with_id() {
\t\t\$this->beConstructedThrough('create', ['id']);
\t\t\$this->aggregateId()->shouldBe('id');
\t}
}
";

        $event = new Event('UserRegistered');
        $aggregateRoot = new AggregateRoot('Model\User', [$event]);
        $this->execute($aggregateRoot, $event)->filename()->shouldBe('./spec/Model/User/Event/UserRegisteredSpec.php');
        $this->execute($aggregateRoot, $event)->fileContent()->shouldBe($content);
    }
}
