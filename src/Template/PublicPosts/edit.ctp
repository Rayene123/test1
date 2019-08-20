<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PublicPost $publicPost
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $publicPost->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $publicPost->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Public Posts'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="publicPosts form large-9 medium-8 columns content">
    <?= $this->Form->create($publicPost) ?>
    <fieldset>
        <legend><?= __('Edit Public Post') ?></legend>
        <?php
            echo $this->Form->control('moderator_approved');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
