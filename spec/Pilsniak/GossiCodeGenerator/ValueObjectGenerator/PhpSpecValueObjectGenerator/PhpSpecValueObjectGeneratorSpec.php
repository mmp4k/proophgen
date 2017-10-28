<?php

namespace spec\Pilsniak\GossiCodeGenerator\ValueObjectGenerator\PhpSpecValueObjectGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\ValueObjectGenerator\PhpSpecValueObjectGenerator\PhpSpecValueObjectGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\ValueObject;
use Prophecy\Argument;

class PhpSpecValueObjectGeneratorSpec extends ObjectBehavior
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

namespace spec\Model\ValueObject;

use Model\ValueObject\Mail;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MailSpec extends ObjectBehavior {

\tpublic function it_is_available_as_primitive() {
\t\t\$this->get()->shouldBe('something');
\t}

\tpublic function it_is_comparable() {
\t\t\$otherMail = Mail::create('something');

\t\t\$this->isEqual(\$otherMail)->shouldBe(true);
\t}

\tpublic function let() {
\t\t\$this->beConstructedThrough('create', ['something']);
\t}
}
";
        $valueObject = new ValueObject('Model\ValueObject\Mail');
        $response = $this->execute($valueObject);
        $response->filename()->shouldBe('./spec/Model/ValueObject/MailSpec.php');
        $response->fileContent()->shouldBe($content);
    }
}
