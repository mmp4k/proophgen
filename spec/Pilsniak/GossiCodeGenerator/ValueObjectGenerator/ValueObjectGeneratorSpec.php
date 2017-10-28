<?php

namespace spec\Pilsniak\GossiCodeGenerator\ValueObjectGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\ValueObjectGenerator\ValueObjectGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\ValueObject;
use Prophecy\Argument;

class ValueObjectGeneratorSpec extends ObjectBehavior
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

    function it_generates_value_object_code()
    {
        $content = "<?php
declare(strict_types=1);

namespace Model\ValueObject;

class Name {

\tpublic \$name;

\tpublic static function create(string \$name): self {
\t\treturn new self(\$name);
\t}

\tpublic function get(): string {
\t\treturn \$this->name;
\t}

\tpublic function isEqual(self \$name): bool {
\t\treturn \$name->get() === \$this->get();
\t}

\tprivate function __construct(string \$name) {
\t\t\$this->name = \$name;
\t}
}
";

        $valueObject = new ValueObject('Model\ValueObject\Name');
        $this->execute($valueObject)->fileContent()->shouldBe($content);
    }
}
