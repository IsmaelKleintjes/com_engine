<?php defined('_JEXEC') or die('Restricted access'); ?>
<div class="row-fluid">
    <div class="span6 form-horizontal">
        <div class="well">
            <h3>Gegevens</h3>

            <?php echo $this->detail->fieldset("essential"); ?>
        </div>
    </div>
    <div class="span5 form-horizontal">
        <div class="well">
            <h3>Bericht</h3>

            <?php echo $this->form->getInput('message'); ?>
        </div>
    </div>
</div>