<?php
namespace ezrpg\core;

class ConfigLoader
{
    public function loadConfigFromPaths($config_paths)
    {
        $config_path = '{' . implode(',', $config_paths) . '}';
        $config_files_path = glob($config_path, GLOB_BRACE);
        $config = [];

        foreach ($config_files_path as $config_file_path) {
            $config_data = include $config_file_path;
            $config = array_merge($config, $config_data);
        }

        return $config;
    }
}