<?php

namespace app\cache;


use app\sitebuilder\Component;

class CacheFile implements Component
{
    public $cachePath;
    public $cacheTime = 300; // 5 minutes

    public $fileMode = 0777;
    public $dirMode = 0777;

    function init()
    {
        $this->createDirectory($this->cachePath, $this->dirMode);
    }

    private function createDirectory($path, $mode = 0775, $recursive = true)
    {
        if (is_dir($path)) {
            return true;
        }

        try {
            $result = mkdir($path, $mode);
            chmod($path, $mode);
        } catch(\Exception $e) {

        }

        return $result;
    }

    public function set($key, $value)
    {
        $key = md5($key);

        if (@file_put_contents($this->cachePath . $key . '.cache', $value, LOCK_EX) !== false) {

            if ($this->fileMode !== null) {
                @chmod($this->cachePath . $key . '.cache', $this->fileMode);
            }
        }

        return $value;
    }

    public function get($key)
    {
        if (file_exists($this->cachePath . $key . '.cache')) {
            return file_get_contents($this->cachePath . $key . '.cache');
        }

        return false;
    }

    public function remove($key)
    {

    }
}