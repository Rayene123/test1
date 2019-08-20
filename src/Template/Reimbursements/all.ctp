<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Reimbursement[]|\Cake\Collection\CollectionInterface $reimbursements
 */
?>
<?= $this->Html->css(['reimbursements/all']); ?>
<div class="reimbursements content">
    <h2 class='title-float'><?= __('Club Reimbursements') ?></h2>
    <div class='stats'>
        <h4 id='stat1'><?= __('Club Total Requested: $') . $financeStats['sum_approved']?></h4>
        <h4><?= __('Club Total Approved: $') . $financeStats['sum']?></h4>
    </div>
    <div class='filter'>
    <?php
        echo $this->Form->create(null, ['type' => 'post']);
        $control = $this->Form->control('user_id', [
            'options' => $allUsers, 
            'empty' => true,
            'label' => false,
            'class' => 'inline-block search-field',
            'templates' => [
                'inputContainer' => '<div class="search-field-div inline-block">{{content}}</div>'
            ]
        ]);
        echo $control;
        $submit = '<div class="inline-block">' . $this->Form->button('Filter') . "</div>";
        echo $submit;
        echo $this->Form->end();
    ?>
    </div>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user.last_name', 'Member') ?></th> <!-- FIXME sort by full name --> 
                <th scope="col"><?= $this->Paginator->sort('volunteer_site.name', 'Site') ?></th>
                <th scope="col"><?= $this->Paginator->sort('date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= __('Total') ?></th>
                <th scope="col"><?= $this->Paginator->sort('submitted') ?></th>
                <th scope="col"><?= $this->Paginator->sort('deleted') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reimbursements as $reimbursement): ?>
            <tr ondblclick=<?="location.href='" . $this->Url->build(["controller" => "Reimbursements","action" => "view", $reimbursement->id]) ."'"?>>
                <td><?= $this->Number->format($reimbursement->id) ?></td>
                <td><?= $reimbursement->has('user') ? $this->Html->link($reimbursement->user->full_name, ['controller' => 'Users', 'action' => 'view', $reimbursement->user->id]) : '' ?></td>
                <td><?= $reimbursement->has('volunteer_site') ? $this->Html->link($reimbursement->volunteer_site->name, ['controller' => 'VolunteerSites', 'action' => 'view', $reimbursement->volunteer_site->id]) : '' ?></td>
                <td><?= h($reimbursement->date) ?></td>
                <td><?= h($reimbursement->created) ?></td>
                <td><?= h($reimbursement->total) ?></td>
                <td><?= h($reimbursement->submitted) ?></td>
                <td><?= h($reimbursement->deleted) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $reimbursement->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $reimbursement->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $reimbursement->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reimbursement->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
