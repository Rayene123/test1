<?php
    echo $this->Html->css(['reimbursements/style', 'form']);
    echo $this->Html->script(['reimbursements/add']);
?>
<div class="reimbursements form content">
    <?= $this->Html->link($this->Html->div('arrow-head') . '</div>', 
            '/reimbursements', 
            ['class' => 'back-arrow', 'escape' => false])
    ?> <!-- FIXME styling -->
    <h3>New Reimbursement</h3>
    <?php
        echo $this->Form->create($reimbursement, ['type' => 'file']);
        echo $this->Form->control('date_string', ['label' => 'Date']);
        echo $this->Form->control('volunteer_site_id', ['type' => 'radio', 'options' => $volunteerSites]);
        echo $this->Form->control('other_riders.user_ids', [
            'type' => 'select', 
            'multiple' => true, 
            'options' => $extraRiders,
            'label' => 'Other Riders',
        ]);
    ?>
    <?= $this->Form->create(null) ?>
    <div>
        <h4>Number of Receipts</h4>
        <?php 
            $values = [];
            for ($k = 1; $k <= count($documents); $k++)
                $values[$k] = $k;
            echo $this->Form->radio('numreceipts', $values, [
                'value' => 1,
            ]);
        ?>
    </div>
    <?php
        for ($k = 0; $k < count($documents); $k++) {
            $num = $k + 1;
            echo $this->Html->div('document-receipt', null, ['id' => 'document-receipt-' . $num]);
                echo $this->Form->create($documents[$k]);
                echo $this->Form->control('documents.' . $k . '.filestuff', [
                    'type' => 'file', 
                    'label' => 'Receipt ' . $num,
                    'class' => 'document-input',
                    'id' => 'document-input-' . $num, //FIXME remove??
                    'required' => false,
                ]);
                echo $this->Form->create($receipts[$k]);
                echo $this->Form->control('receipts.' . $k . '.amount', [
                    'class' => 'receipt-input', 
                    'id' => 'receipt-input-' . $num, //FIXME remove??
                    'required' => false,
                ]);
            echo "</div>";
        }
    ?>
    <?=  $this->Form->button(__('Submit'), ['type' => 'submit', 'class' => 'submit', 'formnovalidate' => true]); ?>
    <?= $this->Form->end() ?>
</div>