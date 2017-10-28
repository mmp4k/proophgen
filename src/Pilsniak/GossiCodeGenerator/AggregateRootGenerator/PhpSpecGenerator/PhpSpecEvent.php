<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\FileToSave;

class PhpSpecEvent
{
    /**
     * @var CodeFileGenerator
     */
    private $codeFileGenerator;

    public function __construct(CodeFileGenerator $codeFileGenerator)
    {
        $this->codeFileGenerator = $codeFileGenerator;
    }

    public function execute(AggregateRoot $aggregateRoot, Event $event): FileToSave
    {
        return new FileToSave($this->generateFileName($aggregateRoot), $this->generateFileContent($aggregateRoot));
    }

    private function generateFileContent(AggregateRoot $aggregateRoot): string
    {
        return '';
    }

    private function generateFileName(AggregateRoot $aggregateRoot): string
    {
        return '';
    }
}
