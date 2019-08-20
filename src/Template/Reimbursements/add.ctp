<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Reimbursement $reimbursement
 */
?>
<?php
    echo $this->Html->css(['reimbursements/style', 'form']);
?>
<div class="reimbursements form content">
    <?= $this->Html->link($this->Html->div('arrow-head') . '</div>', 
            '/reimbursements', 
            ['class' => 'back-arrow', 'escape' => false])
    ?>
    <h3>New Reimbursement</h3>
    <?= $this->Form->create($reimbursement, ['type' => 'file']) ?>
        <?php
            echo $this->Form->control('date_string', ['label' => 'Date']);
            echo $this->Form->control('volunteer_site_id', ['type' => 'radio', 'options' => $volunteerSites]);
            echo $this->Form->control('other_riders.user_ids', [
                'type' => 'select', 
                'multiple' => true, 
                'options' => $extraRiders,
                'label' => 'Other Riders',
            ]);
        ?>
        <div class="form-group">
            <?php
                echo $this->Form->control('receipts.0.amount');
                echo $this->Form->control('receipts.0.filestuff', ['type' => 'file']);
            ?>
        </div>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>