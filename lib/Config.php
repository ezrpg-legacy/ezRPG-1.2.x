<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 7/16/2016
 * Time: 9:37 AM
 */

namespace ezRPG\lib;

use \Exception;


class Config
{
    protected $filelocation;

    public function __construct($filelocation)
    {
        $this->filelocation = $filelocation;
    }

    public function readConfig()
    {
        require $this->filelocation;

        return array(
            'dbdriver' => $config_driver,
            'dbserver' => $config_server,
            'dbuser' => $config_username,
            'dbname' => $config_dbname,
            'dbpass' => $config_password,
            'dbport' => $config_port
        );
    }
}