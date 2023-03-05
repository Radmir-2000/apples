<?php

/** @var yii\web\View $this */
/** @var backend\models\Apples $apples */

$this->title = 'Яблочки';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Яблочки</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-12">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>
            </div>
        </div>

        <table style="width: 100%">
            <tr>
                <?php foreach ($apples as $index => $apple) { ?>
                    <td style="width: 20%; height: 240px; vertical-align: top; background-color: #<?=$apple->color?>; position: relative; padding: 0;">
                        <div style="position: relative; z-index: 100; height: 20%; background-color: #fff;">
                            Появление: <?=$apple->birthdate?><br>
                            Состояние: <?=$apple->status?> Остаток: <?=$apple->rest?>
                        </div>

                        <div style="position: relative; z-index: 100; height: 60%; background: url(/images/apple.png) no-repeat center center; background-size: cover; background-clip: border-box;">
                        </div>

                        <div style="position: relative; z-index: 100; height: 20%; background-color: #fff;">
                        </div>

                        <?php if (($index + 1) % 5 == 0) { ?>
                        </tr><tr>
                        <?php } ?>
                    </td>
                <?php } ?>
            </tr>
        </table>

    </div>
</div>
