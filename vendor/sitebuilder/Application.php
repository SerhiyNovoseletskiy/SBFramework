<?php

namespace app\sitebuilder;

use app\sitebuilder\exceptions\NotFoundHttpException;

/**
 * Application is the base class for our web application
 *
 * @property Application $app Link for self
 *
 */
class Application
{
    function __construct(array $config)
    {
        // Move application configuration to Container class
        foreach ($config as $key => $value) {
            Container::add($key, $value);
        }

        // Initialization of components
        foreach ($config['components'] as $component) {
            $c = new $component['class'];

            if (!empty($component['options']))
                foreach ($component['options'] as $key => $option) {
                    $c->$key = $option;
                }

            // Required for All components
            $c->init();

            Container::add($component['alias'], $c);
        }
    }

    function __get($key)
    {
        return Container::get($key);
    }




    private function setErrorHandler()
    {
        $eHandler = new ErrorHandler();
        $eHandler->register();
    }

    /**
     * @throws NotFoundHttpException
     */
    function run()
    {
        $this->setErrorHandler();

        RouteManager::route();
    }
}