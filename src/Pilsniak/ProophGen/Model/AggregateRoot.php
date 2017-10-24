<?php

namespace Pilsniak\ProophGen\Model;

class AggregateRoot
{
    /**
     * @var string
     */
    private $qualifiedName;
    /**
     * @var array
     */
    private $events;

    public function __construct(string $qualifiedName, array $events = [])
    {
        $this->qualifiedName = $qualifiedName;
        $this->events = $events;
    }

    /**
     * @return array|Event[]
     */
    public function events(): array
    {
        return $this->events;
    }

    public function eventNamespace(Event $event)
    {
        return $this->qualifiedName.'\\Event\\'.$event->name();
    }

    public function eventPath(Event $event)
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, $this->eventNamespace($event)).'.php';
    }

    public function qualifiedName()
    {
        return $this->qualifiedName;
    }

    public function variableName()
    {
        return strtolower($this->className()[0]).substr($this->className(), 1);
    }


    public function path()
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, $this->qualifiedName).'.php';
    }

    public function className()
    {
        return substr($this->qualifiedName, strrpos($this->qualifiedName, '\\')+1);
    }

    public function exceptionPath()
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, $this->qualifiedName).'/Exception/'.$this->className().'NotFound.php';
    }

    public function exceptionQualifiedName()
    {
        return $this->qualifiedName.'\\Exception\\'.$this->className().'NotFound';
    }


    public function repositoryInterfaceName()
    {
        return $this->className().'Repository';
    }

    public function repositoryInterfaceQualifiedName()
    {
        return $this->qualifiedName.'Repository';
    }

    public function repositoryInterfacePath()
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, $this->repositoryInterfaceQualifiedName()).'.php';
    }
}
