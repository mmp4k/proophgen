<?php

namespace Pilsniak\ProophGen\Model;

class ValueObject
{
    /**
     * @var string
     */
    private $qualifiedName;

    public function __construct(string $qualifiedName)
    {
        $this->qualifiedName = $qualifiedName;
    }

    public function path()
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, $this->qualifiedName).'.php';
    }

    public function className()
    {
        return substr($this->qualifiedName, strrpos($this->qualifiedName, '\\')+1);
    }

    public function namespace()
    {
        return substr($this->qualifiedName, 0, strrpos($this->qualifiedName, '\\'));
    }

    public function qualifiedName()
    {
        return $this->qualifiedName;
    }
}
