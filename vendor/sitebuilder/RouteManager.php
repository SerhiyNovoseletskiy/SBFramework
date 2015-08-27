<?php

namespace app\sitebuilder;


use app\sitebuilder\exceptions\NotFoundHttpException;

class RouteManager
{
    private static $callback;
    private static $params;

    static function route(array $route, $url)
    {
        $is_found = false;

        if ($url[strlen($url) - 1] !== '/')
            $url = $url . '/';

        if (!empty($route)) {
            foreach ($route as $pattern => $options) {

                if (preg_match_all('#' . $pattern . '#is', $url, self::$params, PREG_SET_ORDER)) {


                    self::$params = self::$params[0];

                    /*
                     * Видаляю перший елемент бо він не містить параметрів
                     * a дорівнює $_SERVER['REQUEST_URI']
                     */
                    array_shift(self::$params);

                    !empty($options['methodAccess']) ? $methodAccess = $options['methodAccess'] : $methodAccess = '*';

                    if ($methodAccess !== '*' and !in_array(SiteBuilder::$app->request->method, $methodAccess)) {
                        break;
                    }

                    self::getCallback($options);

                    if (!empty(self::$callback))
                        return self::$callback;

                }
            }
        }

        if (!$is_found)
            throw new NotFoundHttpException('Page not found');
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

            self::$callback = new LayoutRender($options['template'], $data, $layout);
            return;
        }

        if (!empty($options['callback'])) {
            self::$callback = call_user_func_array($options['callback'], self::$params);
            return;
        };


        if (!empty($options['controller'])) {

            if (empty(self::$params)) {
                !empty($options['action']) ? $action = $options['action'] : $action = 'index';
            } else {
                if (empty (self::$params['action'])) {
                    !empty($options['action']) ? $action = $options['action'] : $action = 'index';
                } else {
                    $action = !empty($options['action']) ? $options['action'] : $this->params['action'];
                    unset (self::$params['action']);
                    unset (self::$params[array_search($action, self::$params)]);
                }
            }


            $action = $action . 'Action';

            $controller = new $options['controller'];

            if (method_exists($controller, $action)) {
                self::$callback = call_user_func_array(array($controller, $action), self::$params);
                return;
            }


        }

        if (!empty($options[SiteBuilder::$app->request->method])) {
            self::getCallback($options[SiteBuilder::$app->request->method]);
            return;
        }

        if (!empty($options['module'])) {
            $module = new $options['module'];
            self::route($module->route(), self::$params[0]);
        }
    }
}