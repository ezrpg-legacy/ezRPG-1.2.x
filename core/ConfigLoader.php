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
            $config = $this->mergeArrays($config, $config_data);
        }

        return $config;
    }

    /*
     * source: http://php.net/manual/en/function.array-merge-recursive.php
     * author: andyidol@gmail.com
     */
    public function mergeArrays($Arr1, $Arr2)
    {
        foreach($Arr2 as $key => $Value)
        {
            if(array_key_exists($key, $Arr1) && is_array($Value))
                $Arr1[$key] = $this->MergeArrays($Arr1[$key], $Arr2[$key]);

            else
                $Arr1[$key] = $Value;

        }

        return $Arr1;

    }
}