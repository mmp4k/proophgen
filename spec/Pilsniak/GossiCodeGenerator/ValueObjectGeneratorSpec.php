<?php

namespace spec\Pilsniak\GossiCodeGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\ValueObjectGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\ValueObject;
use Pilsniak\ProophGen\ValueObjectExecuter;
use Prophecy\Argument;

class ValueObjectGeneratorSpec extends ObjectBehavior
{
    function it_implements_correct_interface(CodeFileGenerator $codeFileGenerator)
    {
        $this->beConstructedWith($codeFileGenerator);
        $this->shouldImplement(ValueObjectExecuter::class);
    }

    function it_generate_class_filename()
    {
        $generator = new CodeFileGenerator([
            'generateDocblock' => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'declareStrictTypes' => true
        ]);
        $this->beConstructedWith($generator);

        $fileContent = "<?php
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
        $this->execute($valueObject)->shouldBe($fileContent);
    }
}
