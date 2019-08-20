<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PublicPost $publicPost
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Public Post'), ['action' => 'edit', $publicPost->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Public Post'), ['action' => 'delete', $publicPost->id], ['confirm' => __('Are you sure you want to delete # {0}?', $publicPost->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Public Posts'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Public Post'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="publicPosts view large-9 medium-8 columns content">
    <h3><?= h($publicPost->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Public Post Id') ?></th>
            <td><?= $this->Number->format($publicPost->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Moderator Approved') ?></th>
            <td><?= $publicPost->moderator_approved ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
