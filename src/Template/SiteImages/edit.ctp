<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SiteImage $siteImage
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $siteImage->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $siteImage->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Site Images'), ['action' => 'index']) ?></li>
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
<div class="siteImages form large-9 medium-8 columns content">
    <?= $this->Form->create($siteImage) ?>
    <fieldset>
        <legend><?= __('Edit Site Image') ?></legend>
        <?php
            echo $this->Form->control('document_id', ['options' => $documents]);
            echo $this->Form->control('volunteer_site_id', ['options' => $volunteerSites]);
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('public_post_id', ['options' => $publicPosts]);
            echo $this->Form->control('description');
            echo $this->Form->control('deleted');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
