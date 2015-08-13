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

    private $callback = null;
    private $params;

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

    // Get callback from routing list
    private function getCallback(array $options)
    {

        if (!empty($options['middleware'])) {
            foreach ($options['middleware'] as $class => $opt) {
                $middleware = new $class();

                if (is_array($opt)) {
                    foreach ($opt as $variable => $value) {
                        $middleware->$variable = $value;
                    }
                }

                $middleware->init();
            }
        }

        if (!empty($options['template'])) {

            !empty($options['data']) ? $data = $options['data'] : $data = [];
            !empty($options['layout']) ? $layout = $options['layout'] : $layout = 'index';

            $this->callback = new LayoutRender($options['template'], $data, $layout);
            return;
        }

        if (!empty($options['callback'])) {
            $this->callback = call_user_func_array($options['callback'], $this->params);
            return;
        };


        if (!empty($options['controller'])) {

            if (empty($this->params)) {
                !empty($options['action']) ? $action = $options['action'] : $action = 'index';
            } else {
                if (empty ($this->params['action'])) {
                    !empty($options['action']) ? $action = $options['action'] : $action = 'index';
                } else {
                    $action = !empty($options['action']) ? $options['action'] : $this->params['action'];
                    unset ($this->params['action']);
                    unset ($this->params[array_search($action, $this->params)]);
                }
            }


            $action = $action . 'Action';

            $controller = new $options['controller'];

            if (method_exists($controller, $action)) {
                $this->callback = call_user_func_array(array($controller, $action), $this->params);
                return;
            }


        }

        if (!empty($options[SiteBuilder::$app->request->method])) {
            $this->getCallback($options[SiteBuilder::$app->request->method]);
            return;
        }

        if (!empty($options['module'])) {
            $url = explode('/', $_SERVER['REQUEST_URI']);

            // Видаляю перший елемент бо він пустий
            array_shift($url);

            // Видаляю настуний елемент адже він дорівнює назві модуля
            array_shift($url);

            // Будую новий URL адрес
            $_url = $url;
            $url = '/';

            foreach ($_url as $u) {
                $url .= $u . '/';
            }

            $url = substr($url, 0, strlen($url) - 1);


            $module = new $options['module'];

            $route = $module->route();

            if (!empty($route)) {
                foreach ($route as $pattern => $options) {
                    if (preg_match_all('#' . $pattern . '#is', $url, $this->params, PREG_SET_ORDER)) {
                        $this->params = $this->params[0];

                        array_shift($this->params);
                        !empty($options['methodAccess']) ? $methodAccess = $options['methodAccess'] : $methodAccess = '*';

                        if ($methodAccess !== '*' and !in_array($this::$app->request->method, $methodAccess)) {
                            break;
                        }

                        $this->getCallback($options);
                        return $this->callback;
                    }
                }
            }
        }
    }


    private function setErrorHandler()
    {
        $eHandler = new ErrorHandler();
        $eHandler->register();
    }

    function run()
    {
        $route = $this->route;
        $this->setErrorHandler();
        if (!empty($route)) {
            foreach ($route as $pattern => $options) {

                if (preg_match_all('#' . $pattern . '#is', $_SERVER['REQUEST_URI'], $this->params, PREG_SET_ORDER)) {


                    $this->params = $this->params[0];

                    /*
                     * Видаляю перший елемент бо він не містить параметрів
                     * a дорівнює $_SERVER['REQUEST_URI']
                     */
                    array_shift($this->params);

                    !empty($options['methodAccess']) ? $methodAccess = $options['methodAccess'] : $methodAccess = '*';

                    if ($methodAccess !== '*' and !in_array($this::$app->request->method, $methodAccess)) {
                        break;
                    }

                    $this->getCallback($options);

                    echo $this->callback;
                    return;
                }
            }
        }

        // If route not found return page 404
        throw new NotFoundHttpException('Page not found');
    }
}