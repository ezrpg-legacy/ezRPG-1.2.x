<?php
namespace ezrpg\core;

class Config
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }
}
