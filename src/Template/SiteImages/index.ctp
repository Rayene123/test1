<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SiteImage[]|\Cake\Collection\CollectionInterface $siteImages
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Site Image'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Documents'), ['controller' => 'Documents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Document'), ['controller' => 'Documents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Volunteer Sites'), ['controller' => 'VolunteerSites', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Volunteer Site'), ['controller' => 'VolunteerSites', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Public Posts'), ['controller' => 'PublicPosts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Public Post'), ['controller' => 'PublicPosts', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="siteImages index large-9 medium-8 columns content">
    <h3><?= __('Site Images') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('document_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('volunteer_site_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('public_post_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('description') ?></th>
                <th scope="col"><?= $this->Paginator->sort('deleted') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($siteImages as $siteImage): ?>
            <tr>
                <td><?= $this->Number->format($siteImage->id) ?></td>
                <td><?= $siteImage->has('document') ? $this->Html->link($siteImage->document->document_id, ['controller' => 'Documents', 'action' => 'view', $siteImage->document->document_id]) : '' ?></td>
                <td><?= $siteImage->has('volunteer_site') ? $this->Html->link($siteImage->volunteer_site->name, ['controller' => 'VolunteerSites', 'action' => 'view', $siteImage->volunteer_site->volunteer_site_id]) : '' ?></td>
                <td><?= $siteImage->has('user') ? $this->Html->link($siteImage->user->user_id, ['controller' => 'Users', 'action' => 'view', $siteImage->user->user_id]) : '' ?></td>
                <td><?= $siteImage->has('public_post') ? $this->Html->link($siteImage->public_post->public_post_id, ['controller' => 'PublicPosts', 'action' => 'view', $siteImage->public_post->public_post_id]) : '' ?></td>
                <td><?= h($siteImage->description) ?></td>
                <td><?= h($siteImage->deleted) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $siteImage->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $siteImage->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $siteImage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $siteImage->id)]) ?>
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
