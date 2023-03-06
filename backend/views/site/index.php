<?php
use yii\helpers\Html;
use yii\helpers\Url;

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
                <p class="alert alert-info">Яблочко можно сорвать, если оно еще на дереве. Если яблоко не на дереве, его можно откусить, но если оно не гнилое.
                Яблоки можно <?=Html::a('обновить', Url::to(['generate']))?>.</p>
            </div>
        </div>

        <table style="width: 100%">
            <tr>
                <?php foreach ($apples as $index => $apple) { ?>
                    <td style="width: 20%; height: 240px; vertical-align: top; background-color: #<?=$apple->color?>; position: relative; padding: 0; font-size: 14px;">
                        <div style="position: relative; z-index: 100; height: 20%; background-color: #fff;">
                            Появление: <?=$apple->birthdate?><br>
                            Состояние: <?=$apple->status?> Остаток: <?=$apple->rest?>
                        </div>

                        <div style="position: relative; z-index: 100; height: 60%; background: url(/images/apple.png) no-repeat center center; background-size: cover; background-clip: border-box;">
                        </div>

                        <div style="position: relative; z-index: 100; height: 20%; background-color: #fff;">
                            <?php print Html::a('Сорвать', Url::to(['fall', 'id' => $apple->id])); ?>
                            <?php print Html::a('Откусить', Url::to(['eat', 'id' => $apple->id]), ['onclick' => 'eat()']); ?>
                            <?php print Html::a('Откусить', '#', ['onclick' => 'eat()']); ?>
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

<div class="modal fade show" id="modalPercent" tabindex="-1" aria-labelledby="modalPercentLabel" aria-hidden="true">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Укажите процент</h1>
                <button type="button" class="btn-close" onclick="closeEat()"></button>
            </div>

            <div class="modal-body">
                <form id="eatPercent" method="get">
                    <?=Html::input('text', 'percent', '') ?>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="setEat()">Откусить</button>
                <button type="button" class="btn btn-default" onclick="closeEat()">Закрыть</button>
            </div>
        </div>

    </div>
</div>

<?php $this->registerJs('
    var eat = function() {
        $("#modalPercent").toggle();
    };

    var setEat = function() {
        $("#eatPercent").submit();
    };

    var closeEat = function() {
        $("#modalPercent").toggle();
    };
', 2); ?>


