<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html id="myhtml">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?> 
    <?= 
        $this->Html->css([
            'base.css', 
            'style.css', //completed commmented out right now
            'mystyle.css', 
            'nav.css',
            'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css',
            "https://fonts.googleapis.com/css?family=Encode+Sans:400,700&display=swap"
        ]) 
    ?> 
    <?= 
        $this->Html->script([
            'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js',
            'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js',
            'nav'
        ])
    ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="nav-height white">
		<div id="nav-logo-wrapper" class="transparent">
			<a href=<?= $this->Url->build(['controller' => 'Home', 'action' => 'index']) ?>><img id="nav-logo" src=<?= $this->Url->image('dmt-logo.jpg') ?> alt="Logo"></a>
        </div>
        <div class='nav-links'>
            <!-- <div class="nav-link-wrapper transparent col-md-3">
                <= $this->Html->link('Team',
                        ['controller' => '', 'action' => ''],  /* FIXME right location */
                        ['class' => 'nav-link']);
                ?>
            </div> -->
            <!-- <div class="nav-link-wrapper transparent col-md-3">
                <= $this->Html->link('Photos',
                        ['controller' => '', 'action' => ''],  /* FIXME right location */
                        ['class' => 'nav-link']);
                ?>
            </div> -->
            <!-- <div class="nav-link-wrapper transparent col-md-3">
                <= $this->Html->link('Blogs',
                        ['controller' => '', 'action' => ''],  /* FIXME right location */
                        ['class' => 'nav-link']);
                ?>
            </div> -->
            <div class="nav-link-wrapper transparent col-md-4">
                <?= $this->Html->link('Home',
                        ['controller' => 'Home', 'action' => 'index'],
                        ['class' => 'nav-link']);
                ?>
            </div>
            <div class="nav-link-wrapper transparent col-md-4">
                <?= $this->Html->link('Reimbursements',
                        ['controller' => 'Reimbursements', 'action' => 'index'],
                        ['class' => 'nav-link']);
                ?>
            </div>
            <div class="nav-link-wrapper transparent col-md-4">
                <?= $this->Html->link('Account',
                        ['controller' => 'Users', 'action' => 'index'],
                        ['class' => 'nav-link']);
                ?>
            </div>
         </div>
	</nav>
    <div id="nav-space-placeholder" class="nav-height"></div>
    <?= $this->Flash->render() ?>
    <div class="container clearfix main-content"> <!-- not sure if main-content will clash with container and clearfix--> 
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
</body>
</html>
