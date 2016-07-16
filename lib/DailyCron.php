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


class DailyCron
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
    public function start()
    {
        try {
            $this->container['hooks']->run_hooks('cron_daily');
        }catch(\Exception $ex){
            $ex->getMessage();
        }
    }
}