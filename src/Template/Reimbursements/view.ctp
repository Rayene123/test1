<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Reimbursement $reimbursement
 */
?>
<div class="reimbursements content">
    <div style='padding-top: 20px; '>
        <div class='col-md-offset-1 col-md-4'>
            <div style="width: 400px; height=800px; border: 1px solid red;">
                <object width="400px" height="800px" data='http://localhost:8765/reimbursements/viewpdf/16'>>
                </object>
            </div>
        </div>
        <div class='col-md-offset-1 col-md-4'>
            <div style="height=800px; border: 3px solid green;"></div>
        </div>
        <div class='col-md-offset-2 col-md-7'>
            <table class="vertical-table">
                <tr>
                    <th scope="row"><?= __('User') ?></th>
                    <td><?= $reimbursement->has('user') ? $this->Html->link($reimbursement->user->user_id, ['controller' => 'Users', 'action' => 'view', $reimbursement->user->user_id]) : '' ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Volunteer Site') ?></th>
                    <td><?= $reimbursement->has('volunteer_site') ? $this->Html->link($reimbursement->volunteer_site->name, ['controller' => 'VolunteerSites', 'action' => 'view', $reimbursement->volunteer_site->volunteer_site_id]) : '' ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Reimbursement Id') ?></th>
                    <td><?= $this->Number->format($reimbursement->id) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Date') ?></th>
                    <td><?= h($reimbursement->date) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Created') ?></th>
                    <td><?= h($reimbursement->created) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Modified') ?></th>
                    <td><?= h($reimbursement->modified) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Submitted') ?></th>
                    <td><?= h($reimbursement->submitted) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Deleted') ?></th>
                    <td><?= $reimbursement->deleted ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
