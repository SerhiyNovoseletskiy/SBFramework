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
        '^/hello/(?P&lt;name>\w+)$' => function ($name) {
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

         '^/rubric/(?P&lt;id>\d)/(?P&lt;action>\w)$' => [
            'controller' => 'app\controllers\RubricController',
        ],
        ...
    ];

</pre>
<pre class="brush: php;">
    // components/controllers/RubricController.php

    class RubricController extends Controller {

            public function editAction = function($id) {
                $rubric = Rubric::getByPK($id);
                return $this->render('rubric', [
                    'rubric' => $rubric
                 ]);
            };

            public function indexAction = function() {
                $rubrics = Rubric::getAll();
            };

            public function deleteAction = function ($id) {
                Rubric::getByPK($id)->delete();
            };

            public function insertAction = function () {
                /* Деякий код */
            };

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