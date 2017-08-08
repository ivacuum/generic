<?php namespace Ivacuum\Generic\Services;

use Foolz\SphinxQL\Connection;
use Foolz\SphinxQL\SphinxQL;

class Sphinx
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function create()
    {
        return new SphinxQL($this->connection);
    }
}
