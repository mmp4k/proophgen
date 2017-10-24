<?php

namespace Pilsniak\ProophGen\Model;

class Command
{
    /**
     * @var string
     */
    private $qualifiedName;

    public function __construct(string $qualifiedName)
    {
        $this->qualifiedName = $qualifiedName;
    }

    public function commandVariableName()
    {
        return strtolower($this->commandName()[0]).substr($this->commandName(), 1);
    }

    public function commandName()
    {
        return substr($this->qualifiedName, strrpos($this->qualifiedName, '\\')+1);
    }

    public function commandHandlerName()
    {
        return substr($this->qualifiedName, strrpos($this->qualifiedName, '\\')+1).'Handler';
    }

    public function commandPath()
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, $this->qualifiedName).'.php';
    }

    public function commandHandlerPath()
    {
        $lastSlash = strrpos($this->qualifiedName, '\\');
        $path = str_replace('\\', DIRECTORY_SEPARATOR, substr($this->qualifiedName, 0, $lastSlash) . 'Handler');
        return $path .'/' . $this->commandHandlerName().'.php';
    }

    public function commandQualifiedName()
    {
        return $this->qualifiedName;
    }

    public function commandHandlerQualifiedName()
    {
        $lastSlash = strrpos($this->qualifiedName, '\\');
        $path = substr($this->qualifiedName, 0, $lastSlash) . 'Handler';
        return $path .'\\' . $this->commandHandlerName();
    }
}
