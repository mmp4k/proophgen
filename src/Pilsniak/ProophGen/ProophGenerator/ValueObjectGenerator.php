<?php

namespace Pilsniak\ProophGen\ProophGenerator;

use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\Model\ValueObject;
use Pilsniak\ProophGen\ProophGenerator\ValueObjectGenerator\PhpSpecValueObjectExecuter;
use Pilsniak\ProophGen\ValueObjectExecuter;

class ValueObjectGenerator
{
    /**
     * @var ValueObjectExecuter
     */
    private $valueObjectExecuter;
    /**
     * @var PhpSpecValueObjectExecuter
     */
    private $phpSpecValueObjectExecuter;

    public function __construct(ValueObjectExecuter $valueObjectExecuter, PhpSpecValueObjectExecuter $phpSpecValueObjectExecuter)
    {
        $this->valueObjectExecuter = $valueObjectExecuter;
        $this->phpSpecValueObjectExecuter = $phpSpecValueObjectExecuter;
    }

    /**
     * @param ValueObject $valueObject
     *
     * @return array|FileToSave[]
     */
    public function generate(ValueObject $valueObject)
    {
        return array_merge(
            $this->valueObjectExecuter->execute($valueObject),
            $this->phpSpecValueObjectExecuter->execute($valueObject)
        );
    }
}
