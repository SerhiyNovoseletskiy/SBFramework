<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 27.08.15
 * Time: 22:23
 */

namespace app\cache\FileCache;


use app\sitebuilder\Component;

class Cache implements Component {
    public $cachepath = '/cache';
    public $extension = '.cache';


    function init() {
        if (!is_dir($this->cachepath)) {
            mkdir($this->cachepath);
        }
    }

    /**
     * @param string $key
     * @param $data
     * @param null|int $time
     * @return $this
     */
    function set($key, $data, $time = null) {
        $storeData = array(
            'data' => $data,
            'time' => $time + time()
        );

        $fileName = md5($key).$this->extension;
        file_put_contents($this->cachepath . $fileName ,serialize($storeData));
        return $this;
    }

    /**
     * @param $key
     * @return mixed
     */
    function get($key) {
        $fileName = md5($key).$this->extension;

        if (file_exists($this->cachepath . $fileName)) {
            $cacheData = file_get_contents($this->cachepath . $fileName);
            $cacheData = unserialize($cacheData);

            if (!$cacheData['time'])
                return $cacheData['data'];

            if ($cacheData['time'] >= time())
                return $cacheData['data'];
        }

        return null;
    }

    /**
     * @param string $key
     */
    function remove($key) {
        $fileName = md5($key).$this->extension;

        if (file_exists($this->cachepath . $fileName)) {
            unlink($this->cachepath . $fileName);
        }
    }
}
