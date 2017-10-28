<?php

namespace Pilsniak\GossiCodeGenerator\ValueObjectGenerator;

use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\Model\ValueObject;
use Pilsniak\ProophGen\ProophGenerator\ValueObjectGenerator\PhpSpecValueObjectExecuter;

class PhpSpecValueObjectGenerator implements PhpSpecValueObjectExecuter
{
    /**
     * @var PhpSpecValueObjectGenerator\PhpSpecValueObjectGenerator
     */
    private $phpSpecValueObjectGenerator;

    public function __construct(PhpSpecValueObjectGenerator\PhpSpecValueObjectGenerator $phpSpecValueObjectGenerator)
    {
        $this->phpSpecValueObjectGenerator = $phpSpecValueObjectGenerator;
    }


    /**
     * @param ValueObject $valueObject
     *
     * @return array|FileToSave[]
     */
    public function execute(ValueObject $valueObject): array
    {
        return [
            $this->phpSpecValueObjectGenerator->execute($valueObject)
        ];
    }
}
