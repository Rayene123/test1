<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Reimbursement $reimbursement
 */
?>
<?php 
    echo $this->Html->css(['reimbursements/view']);
?>
<div class="reimbursements content">
    <div style='padding-top: 20px; '>
<!--
        <div class='col-md-offset-1 col-md-4'>
            <div style="width: 400px; height=800px; border: 1px solid red;">
                <object width="400px" height="800px" data='http://localhost:8765/reimbursements/viewpdf/16'>>
                </object>
            </div>
        </div>
        <div class='col-md-offset-1 col-md-4'>
            <div style="height=800px; border: 3px solid green;"></div>
        </div>
-->
        <div>
            <p>Date: <?= h($reimbursement->date) ?></p>
            <p>Total: $<?= h($reimbursement->total) ?></p>
            <p>Site: <?= h($reimbursement->volunteer_site->name) ?></p>
            <p>Created: <?= h($reimbursement->created) ?></p>
            <p>Modified: <?= h($reimbursement->modified) ?></p>
            <p>Deleted: <?= $reimbursement->deleted ? 'yes' : 'no' ?></p>
        </div>
        <div class='card-container'>
            <?php foreach ($reimbursement->receipts as $k => $receipt): ?>
            <div class='col-xs-offset-1 col-md-offset-2 col-md-3 center-text receipt-card'>
                <h2>Receipt <?= $k + 1 ?></h4>
                <p>Amount: $<?= $receipt->amount ?></p>
                <p>Approved: <?= $receipt->approved ? 'yes' : 'no' ?></p>
                <?= $this->Html->link('View File', ['controller' => 'Documents', 'action' => 'view/' . $receipt->document_id], ['class' => 'doc-link']) ?>
                <?php 
                    if ($isTreasurer && !$reimbursement->submitted) {
                        $approved = $receipt->approved;
                        $class = $approved ? 'approved' : 'unapproved';
                        $text = $approved ?  "Mark Unapproved" : 'Mark Approved';
                        echo $this->Html->link($text, ['controller' => 'Receipts', 'action' => 'toggle-approval/' . $receipt->id], ['class' => $class]);
                    }
                ?>
            </div>
            <?php endforeach; ?>
        </div>
        <div class='center-text submit-button-wrapper'>
        <?php 
            if ($isTreasurer && $reimbursement->approved) {
                $submitted = $reimbursement->submitted;
                $class = 'submit-button ';
                $class .= $submitted ? 'submitted' : 'unsubmitted';
                $text = $submitted ? 'Remove Submission Status' : 'Mark Submitted';
                echo $this->Html->link($text, ['controller' => 'Reimbursements', 'action' => 'toggle-submission/' . $reimbursement->id], ['class' => $class]);
                if ($submitted) 
                    echo "<p>" . h("Submission Date: " . $reimbursement->submitted) ."</p>";
            }
        ?>
        </div>
        <div>
            <h2>Member Info</h2>
            <p>Name: <?= h($reimbursement->user->full_name) ?></p>
            <p>Unique ID: <?= h($reimbursement->user->unique_id) ?></p>
            <h2>Reimburse to address:</h2>
            <div>
            <?php 
                $location = $reimbursement->user->location;
                $roadString = $location->number . ' ' . $location->road;
                echo "<p>" . h($roadString) . "</p>";
                $cityString = $location->city . ', ' . $location->state . ' ' . $location->zip;
                echo "<p>" . h($cityString) . "</p>";
                $po = $location->po_box;
                if (!\is_null($po) && strlen($po) !== 0)
                    echo "<p>" . h('Box ' . $po) . "</p>";
            ?>
            </div>
        </div>
    </div>
</div>
