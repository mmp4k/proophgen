<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecAggregateCode;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class PhpSpecAggregateCodeSpec extends ObjectBehavior
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

    function it_generates_code_with_event_non_creator()
    {
        $content = "<?php
declare(strict_types=1);

namespace spec\Model;

use Model\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserSpec extends ObjectBehavior {

\tpublic function it_can_registerUser() {
\t\t\$this->registerUser();
\t}

\tpublic function it_returns_id() {
\t\t\$this->id()->shouldBe('id');
\t}
}
";
        $aggregateRoot = new AggregateRoot('Model\User', [new Event('UserRegistered')]);
        $this->execute($aggregateRoot)->filename()->shouldBe('./spec/Model/UserSpec.php');
        $this->execute($aggregateRoot)->fileContent()->shouldBe($content);
    }

    function it_generates_code_with_event_creator()
    {
        $content = "<?php
declare(strict_types=1);

namespace spec\Model;

use Model\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserSpec extends ObjectBehavior {

\tpublic function it_returns_id() {
\t\t\$this->id()->shouldBe('id');
\t}

\tpublic function let() {
\t\t\$this->beConstructedThrough('registerUser', ['id']);
\t}
}
";
        $aggregateRoot = new AggregateRoot('Model\User', [new Event('UserRegistered', true)]);
        $this->execute($aggregateRoot)->filename()->shouldBe('./spec/Model/UserSpec.php');
        $this->execute($aggregateRoot)->fileContent()->shouldBe($content);
    }

}
