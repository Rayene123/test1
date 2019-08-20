<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\Location $location
 */
?>

<?php
    echo $this->Html->css(['blue-background', 'users/form']);
    echo $this->Html->script(['users/form']);
?>
<div class="users form">
    <div class='user-form'>
        <?= $this->Form->create($user) ?>
        <div class='fields'>
        <?=
            $this->element('users/fields', [
                'fields' => [
                    'username', 
                    'password', 
                    'email', 
                    'first_name', 
                    'last_name', 
                    'unique_id'
                ],
                'specialTypes' => [
                    'unique_id' => 'text'
                ]
            ])
        ?>
        </div>
        <?= $this->Form->create($location) ?>
        <div>
            <p>What's the best address to send reimbursement checks to?</p>
        </div>
        <div class='fields'>
        <?=
            $this->element('users/fields', [
                'fields' => [
                    'number', 
                    'road', 
                    'city', 
                    'state', 
                    'zip', 
                    'po_box'
                ],
                'optionalFields' => ['po_box']
            ])
        ?>
        </div>
        <?= $this->element('users/formsubmit', ['name' => 'Create Account']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
