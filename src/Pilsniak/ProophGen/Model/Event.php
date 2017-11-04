<?php

namespace Pilsniak\ProophGen\Model;

class Event
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var bool
     */
    private $isCreator;

    public function __construct(string $name, bool $isCreator = false)
    {
        $this->name = $name;
        $this->isCreator = $isCreator;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function aggregateMethodName()
    {
        $splited = preg_split('/(?=[A-Z])/', $this->name, -1, PREG_SPLIT_NO_EMPTY);;
        krsort($splited);

        $splited[array_keys($splited)[0]] = strtolower($splited[array_keys($splited)[0]]);

        if (substr($splited[array_keys($splited)[0]], -2) === 'ed') {
            $splited[array_keys($splited)[0]] = substr($splited[array_keys($splited)[0]], 0, -2);
        }

        return implode('', $splited);

    }

    public function isCreator(): bool
    {
        return $this->isCreator;
    }

    public function guardQualifiedName(AggregateRoot $aggregateRoot)
    {
        return $aggregateRoot->qualifiedName().'\\Guard\\'.$this->guardName();
    }

    public function guardQualifiedPath(AggregateRoot $aggregateRoot)
    {
        return './src/'.str_replace('\\', DIRECTORY_SEPARATOR, $this->guardQualifiedName($aggregateRoot)).'.php';
    }

    public function guardName()
    {
        return $this->name.'Guard';
    }

    function guardVariableName()
    {
        return strtolower($this->guardName()[0]).substr($this->guardName(), 1);
    }
}
