<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SiteImage $siteImage
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Site Image'), ['action' => 'edit', $siteImage->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Site Image'), ['action' => 'delete', $siteImage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $siteImage->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Site Images'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Site Image'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Documents'), ['controller' => 'Documents', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Document'), ['controller' => 'Documents', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Volunteer Sites'), ['controller' => 'VolunteerSites', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Volunteer Site'), ['controller' => 'VolunteerSites', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Public Posts'), ['controller' => 'PublicPosts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Public Post'), ['controller' => 'PublicPosts', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="siteImages view large-9 medium-8 columns content">
    <h3><?= h($siteImage->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Document') ?></th>
            <td><?= $siteImage->has('document') ? $this->Html->link($siteImage->document->document_id, ['controller' => 'Documents', 'action' => 'view', $siteImage->document->document_id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Volunteer Site') ?></th>
            <td><?= $siteImage->has('volunteer_site') ? $this->Html->link($siteImage->volunteer_site->name, ['controller' => 'VolunteerSites', 'action' => 'view', $siteImage->volunteer_site->volunteer_site_id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $siteImage->has('user') ? $this->Html->link($siteImage->user->user_id, ['controller' => 'Users', 'action' => 'view', $siteImage->user->user_id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Public Post') ?></th>
            <td><?= $siteImage->has('public_post') ? $this->Html->link($siteImage->public_post->public_post_id, ['controller' => 'PublicPosts', 'action' => 'view', $siteImage->public_post->public_post_id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= h($siteImage->description) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Site Image Id') ?></th>
            <td><?= $this->Number->format($siteImage->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Deleted') ?></th>
            <td><?= $siteImage->deleted ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
