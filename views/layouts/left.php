<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'My Menu', 'options' => ['class' => 'header'], 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'GPS Data', 'icon' => 'map', 'url' => ['user/index'], 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'Profile', 'icon' => 'user', 'url' => ['user/profile'], 'visible' => !Yii::$app->user->isGuest],

                    ['label' => 'Site Menu', 'options' => ['class' => 'header']],
                    ['label' => 'Login', 'icon' => 'user', 'url' => ['user/login'], 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'About', 'icon' => 'info', 'url' => ['site/about']],
                    ['label' => 'F.A.Q.', 'icon' => 'question', 'url' => ['site/faq']],
                    ['label' => 'Contact us', 'icon' => 'envelope', 'url' => ['site/contact']],
                ],
            ]
        ) ?>

    </section>

</aside>
