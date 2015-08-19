<?php

namespace app\sitebuilder;

use app\sitebuilder\exceptions\NotFoundHttpException;

/**
 * Application is the base class for our web application
 * @author Novoseletskiy Serhiy <novoseletskiyserhiy@gmail.com>
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



    /**
     * @throws NotFoundHttpException
     */
    function run()
    {
        // Set ErrorHandler
        $eHandler = new ErrorHandler();
        $eHandler->register();

        echo RouteManager::route(SiteBuilder::$app->route, $_SERVER['REQUEST_URI']);
    }
}