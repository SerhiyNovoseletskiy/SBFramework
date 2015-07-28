<?php

namespace app\sitebuilder;

use app\sitebuilder\ErrorHandler;

class Application
{
    // Посилання саме на себе
    public static $app = null;

    private $callback = null;
    private $params;

    function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            Container::add($key, $value);
        }

        foreach ($config['components'] as $component) {
            $c = new $component['class'];

            if (!empty($component['options']))
                foreach ($component['options'] as $key => $option) {
                    $c->$key = $option;
                }

            $c->init();

            Container::add($component['alias'], $c);
        }
    }

    function __get($key)
    {
        return Container::get($key);
    }

    private function getCallback(array $options)
    {

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

            /*if (empty($this->params)) {
                $action = 'index';
            } else {
                !empty($options['actionIndex']) ? $actionIndex = $options['actionIndex'] : $actionIndex = 0;
                $action = $this->params[$actionIndex];
                array_splice($this->params, $actionIndex,1);
            }*/

            if (empty($this->params)) {
                !empty($options['action']) ? $action = $options['action'] : 'index';
            } else {
                if (empty ($this->params['action'])) {
                    !empty($options['action']) ? $action = $options['action'] : 'index';
                } else {
                    $action = $this->params['action'];

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

        if (!empty($options[$this::$app->request->method])) {
            $this->getCallback($options[$this::$app->request->method]);
            return;
        }
    }

    private function setErrorHandler() {
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
        echo new LayoutRender($this->errors['404']);
    }
}