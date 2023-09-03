<?php

namespace Ivacuum\Generic\Services;

use Foolz\SphinxQL\Drivers\ConnectionInterface;
use Foolz\SphinxQL\Helper;
use Foolz\SphinxQL\SphinxQL;

class Sphinx
{
    public function __construct(private ConnectionInterface $connection)
    {
    }

    public function create(): SphinxQL
    {
        return new SphinxQL($this->connection);
    }

    public function helper(): Helper
    {
        return new Helper($this->connection);
    }
}
