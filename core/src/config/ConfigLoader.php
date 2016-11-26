<?php
namespace ezrpg\core\config;

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

    /**
     * Merge arrays recursively.
     *
     * @author Andy <andyidol@gmail.com>
     */
    private function mergeArrays($arr1, $arr2)
    {
        foreach($arr2 as $key => $value) {
            if(array_key_exists($key, $arr1) && is_array($value)) {
                $arr1[$key] = $this->mergeArrays($arr1[$key], $arr2[$key]);
            } else {
                $arr1[$key] = $value;
            }
        }

        return $arr1;
    }
}
