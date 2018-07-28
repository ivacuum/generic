<?php namespace Ivacuum\Generic\Services;

use Foolz\SphinxQL\Drivers\SimpleConnection;
use Foolz\SphinxQL\Helper;
use Foolz\SphinxQL\SphinxQL;

class Sphinx
{
    protected $connection;

    public function __construct(SimpleConnection $connection)
    {
        $this->connection = $connection;
    }

    public function create()
    {
        return new SphinxQL($this->connection);
    }

    public function helper()
    {
        return Helper::create($this->connection);
    }
}
