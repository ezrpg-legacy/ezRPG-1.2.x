<?php
/**
 * Created by PhpStorm.
 * User: uakta
 * Date: 7/16/2016
 * Time: 12:55 AM
 */

namespace ezRPG\lib;

use ezRPG\lib\Application,
    Pimple\Container;


class HourlyCron
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function start()
    {
        try {
            $this->container['hooks']->run_hooks('cron_1hr');
        } catch (\Exception $ex) {
            $ex->getMessage();
        }
    }
}