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
    ?> <!-- FIXME styling -->
    <h3>New Reimbursement</h3>
    <?php
        echo $this->Form->create($reimbursement);
        echo $this->Form->control('date_string', ['label' => 'Date']);
        echo $this->Form->control('volunteer_site_id', ['type' => 'radio', 'options' => $volunteerSites]);
        echo $this->Form->control('other_riders.user_ids', [
            'type' => 'select', 
            'multiple' => true, 
            'options' => $extraRiders,
            'label' => 'Other Riders',
        ]);
    ?>
    <?php 
        echo $this->Form->create($documents[0], ['type' => 'file']);
        echo $this->Form->control('document.filestuff', ['type' => 'file', 'label' => 'Receipt ' . (0 + 1)]); //FIXME make $k??
    ?>
    <?php
        echo $this->Form->create($receipts[0]);
        echo $this->Form->control('receipt.amount');
    ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>