<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<section class="content">

    <div class="error-page">
        <h2 class="headline text-info"><i class="fa fa-warning text-yellow"></i></h2>

        <div class="error-content">
            <h3><?= $name ?></h3>

            <p>
                <?= nl2br(Html::encode($message)) ?>
            </p>

            <p>
                The above error occurred while the Web server was processing your request.
                Please <a href="/site/contact">contact us</a> if you think this is a server error. Thank you.
                Meanwhile, you may <a href='<?= Yii::$app->homeUrl ?>'>return to map</a> or try using our <a href="/site/faq">F.A.Q.</a>
            </p>

        </div>
    </div>

</section>
