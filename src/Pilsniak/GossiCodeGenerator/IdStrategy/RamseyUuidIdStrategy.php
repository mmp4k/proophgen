<?php

namespace Pilsniak\GossiCodeGenerator\IdStrategy;

use gossi\codegen\model\AbstractPhpStruct;
use gossi\codegen\model\PhpMethod;
use Pilsniak\ProophGen\IdStrategy;

class RamseyUuidIdStrategy implements IdStrategy
{
    public function type(): string
    {
        return 'UuidInterface';
    }

    public function convertToType(string $expression): string
    {
        return 'Uuid::fromString('.$expression.')';
    }

    public function convertToString(string $expression): string
    {
        return $expression.'->toString()';
    }

    public function modifyPhpClass(AbstractPhpStruct $class)
    {
        $class->addUseStatement('Ramsey\Uuid\Uuid');
        $class->addUseStatement('Ramsey\Uuid\UuidInterface');
    }

    public function phpSpecIdGenerator(AbstractPhpStruct $class)
    {
        $class->setMethod(
            PhpMethod::create('_id')
                ->setVisibility('protected')
                ->setBody($this->generateBodyForIdPhpSpecGenerator())
        );
    }

    protected function generateBodyForIdPhpSpecGenerator()
    {
        return "return Uuid::fromString('dd76226a-926d-4603-ab5a-69010722510e');";
        $body = '$prophet = new \Prophecy\Prophet;'."\n";

        $body .= '$prophecy = $prophet->prophesize(UuidInterface::class);'."\n";
        $body .= '$prophecy->toString()->willReturn(\'id\');'."\n";

        $body .= 'return $prophecy;';

        return $body;
    }

    public function value(): string
    {
        return 'dd76226a-926d-4603-ab5a-69010722510e';
    }
}
