<?php

namespace Pilsniak\ProophGen;

use gossi\codegen\model\AbstractPhpStruct;

interface IdStrategy
{
    public function value(): string;
    public function type(): string;
    public function convertToType(string $expression): string;
    public function convertToString(string $expression): string;
    public function modifyPhpClass(AbstractPhpStruct $class);
    public function phpSpecIdGenerator(AbstractPhpStruct $class);
}
