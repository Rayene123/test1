<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Reimbursement[]|\Cake\Collection\CollectionInterface $reimbursements
 */
?>
<?php 
    echo $this->Html->css(['reimbursements/style', 'table']);
    echo $this->Html->script(['table']);
?>
<?php 
    function conditionalImage($htmlHelper, string $filename, $condition) {
        if ($condition) {
            return $htmlHelper->div('table-img-wrapper') . $htmlHelper->image('tutoring-img1.jpg') . "</div>"; //FIXME approval image, and center
        }
        return "";
    }

    function getSubmittedDate($reimbursement) {
        $submitted = $reimbursement->submitted;
        return is_null($submitted) ? '' : $submitted->i18nFormat("MMMM d");
    }
?>
<div class="reimbursements index content">
    <h2 class='inline-block'><?= __('Reimbursements') ?></h2>
    <?= $this->Html->link('', '/reimbursements/add', ['class' => ['float-right', 'plus-button']]) ?>
    <h4><?= __('Total Requested: $') . array_reduce($reimbursements->toArray(), function($result, $reimb) { return $result + $reimb->total; }, 0) ?></h4>
    <h4><?= __('Total Approved: $') . array_reduce($reimbursements->toArray(), function($result, $reimb) {return $result + $reimb->approved_total;}, 0)?></h4>
    <div class='table-area'>
        <table class='sleek' cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class='capital' scope="col"><?= $this->Paginator->sort('date') ?></th>
                    <th class='capital' scope="col"><?= $this->Paginator->sort('volunteer_site.name', 'Site') ?></th>
                    <th class='capital' scope="col"><?= __('Total') ?></th>
                    <th class='capital disappearing' scope="col"><?= $this->Paginator->sort('created') ?></th>
                    <th class='capital disappearing' scope="col"><?= $this->Paginator->sort('submitted') ?></th>
                    <th class='capital' scope="col"><?= __('Approved') ?></th>
                    <th class='capital' scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody class='body-half-screen'>
                <?php foreach ($reimbursements as $reimbursement): ?>
                <tr onclick='handleTableRowSelect(this)' ondblclick=<?="location.href='" . $this->Url->build(["controller" => "Reimbursements","action" => "view", $reimbursement->id]) ."'"?>>
                    <td><?= h($reimbursement->date) ?></td>
                    <td><?= h($reimbursement->volunteer_site->name) ?></td>
                    <td><?= h($reimbursement->total) ?></td>
                    <td class='disappearing'><?= h($reimbursement->created) ?></td>
                    <td class='disappearing'><?= getSubmittedDate($reimbursement); ?></td>
                    <td><?= conditionalImage($this->Html, 'tutoring-img1.jpg', $reimbursement->approved);//FIXME real image, and center image ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $reimbursement->id]) ?>
                        <!--  FIXME uncomment when edit works < $this->Html->link(__('Edit'), ['action' => 'edit', $reimbursement->id]) ?> -->
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $reimbursement->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reimbursement->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <h4><?= $this->Paginator->counter(['format' => __('Reimbursement Count: {{count}}')]) ?></h4>
</div>
