<div class='pad'>
    <div class='col-md-4 col-md-offset-1'>
        <h3 class='field-name'>
            <?= $prettyName ?>
        </h3>
    </div>
    <div class='col-md-5'>
    <?=
        $this->Form->control($name, [
            'type' => $type,
            'label' => false,
        ]);
    ?>
    </div>
</div>