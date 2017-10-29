<?php

namespace Pilsniak\ProophGen\Model;

class IdPolicy
{
    /**
     * @var string
     */
    private $namePolicy;

    public function __construct(string $namePolicy)
    {
        if (!in_array($namePolicy, ['string', 'Ramsey\Uuid\UuidInterface'])) {
            throw new \Exception(sprintf("Id policy %s is not supported.", $namePolicy));
        }
        $this->namePolicy = $namePolicy;
    }

    public function name(): string
    {
        return $this->namePolicy;
    }
}
