<?php

namespace Pilsniak\YamlFileParser;

use Pilsniak\ProophGen\FileParser;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Command;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\IdPolicy;
use Pilsniak\ProophGen\Model\ValueObject;
use Symfony\Component\Yaml\Yaml;

class YamlLoader implements FileParser
{
    private $yaml;

    public function __construct(string $fileContent)
    {
        $this->yaml = Yaml::parse($fileContent);
    }

    public function valueObjects() : array
    {
        $return = [];
        foreach ($this->yaml['valueObjects'] as $valueObject) {
            $return[] = new ValueObject($valueObject);
        }

        return $return;
    }

    public function commands(): array
    {
        $return = [];
        foreach ($this->yaml['commands'] as $command) {
            $return[] = new Command($command);
        }

        return $return;
    }

    public function aggregateRoots(): array
    {
        $return = [];

        foreach ($this->yaml['aggregateRoots'] as $aggregateRoot => $events) {
            $eventsObjects = [];
            foreach ($events as $event) {
                $isCreator = substr($event, 0, 1) === '!';
                $eventsObjects[] = new Event($isCreator ? substr($event, 1) : $event, $isCreator);
            }
            $return[] = new AggregateRoot($aggregateRoot, $eventsObjects);
        }
        return $return;
    }

    public function idPolicy(): IdPolicy
    {
        return new IdPolicy(isset($this->yaml['idPolicy']) ? $this->yaml['idPolicy'] : 'string');
    }
}
