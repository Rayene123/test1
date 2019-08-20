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
    <?= $this->Form->create() ?>
    <div>
        <h4>Number of Receipts</h4>
        <?php 
            $values = [];
            for ($k=1; $k<=$maxNumReceipts; $k++)
                $values[$k] = $k;
            echo $this->Form->radio('numreceipts', $values, [
                'value' => 1,
            ]);
        ?>
    </div>
    <?php
        for ($k = 0; $k < $maxNumReceipts; $k++) {
            $num = $k + 1; 
            echo $this->Html->div('document-receipt', null, ['id' => 'document-receipt-' . $num]);
                echo $this->Form->create($documents[$k], ['type' => 'file']);
                echo $this->Form->control('documents.' . $k . '.filestuff', [
                    'type' => 'file', 
                    'label' => 'Receipt ' . $num,
                    'class' => 'document-input',
                    'id' => 'document-input-' . $num, //FIXME remove??
                ]);
                echo $this->Form->create($receipts[$k]);
                echo $this->Form->control('receipts.' . $k . '.amount', [
                    'class' => 'receipt-input', 
                    'id' => 'receipt-input-' . $num, //FIXME remove??
                ]);
            echo "</div>";
        }
    ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>