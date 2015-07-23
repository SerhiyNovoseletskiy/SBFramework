<?
    $this->title = 'Маршрутизація';
?>

<?= \app\widgets\bootstrap\BreadCrumb::widget([
    ['label' => 'Головна', 'url' => '/'],
    ['label' => 'Документація', 'url' => '/docs'],
    ['label' => 'Маршрутизація', 'url' => '/docs/routing'],
])?>

<p>
    Маршрутизація в <b>SB Framework</b> здійснюється за допомогою регулярних виразів
</p>

<h3>Маршрутизація зі зворотнім зв'язком</h3>

<pre class="brush: php;">
    // config/route.php

    return [
        ...
        '^/$' => [
            'callback' => function () {
                return 'Hello world';
            }
        ],

        // /hello/&lt;name&gt;
        '^/hello/(.*\w)' => function ($name) {
            return 'Hello '.$name;
        }
        ...
    ];
</pre>

<h3>Маршрутизація з Controller</h3>

<pre class="brush: php;">
    // config/route.php

    return [
        ...
        // aI = actionIndex
        //          aI=0 aI=1
        // /rubric/<id>/<action>
         '^/rubric/(.*\d)/(.*\w)$' => [
            'controller' => 'app\controllers\RubricController',
            /*
             * Визначаємо який з параметрів буде відповідати за екшн
             * По замовчуванню 0, тобто перший параметр
            */
            'actionIndex' => 1
        ],
        ...
    ];

    // components/controllers/RubricController.php

    class RubricController extends Controller {
        function initActions() {

            $this->editAction = function($id) {
                $rubric = Rubric::getByPK($id);
                return $this->render('rubric', [
                    'rubric' => $rubric
                 ]);
            };

            $this->indexAction = function() {
                $rubrics = Rubric::getAll();
            };

            $this->deleteAction = function ($id) {
                Rubric::getByPK($id)->delete();
            };

            $this->insertAction = function () {
                return $this->render('rubric/new');
            };
        }
    }
</pre>

<h3>Завантаження шаблону</h3>

<pre class="brush: php">
    // config/route.php

    return [
        ...
        '^/about$' => [
            'template' => 'about',
            /*
            * Можна не вказувати. По замовчуванню []
            */
            'data' => [
                'author' => 'Novoseletskiy Serhiy',
                'email' => 'novoseletskiyserhiy@gmail.com'
            ],
            /*
            * Можна не вказувати. По замовчуванню 'index'
            */
            'layout' => 'index'
        ]
        ...
    ];
</pre>

<pre class="brush: html">
    // views/about.php

    &lt;h3&gt;About page&lt;/h3&gt;

    &lt;a href = 'mailto:&lt;?= $email ?&gt;'&gt; &lt;?= $author ?&gt; &lt;/a&gt;
</pre>