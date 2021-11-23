<?php defined('_JEXEC') or die('Restricted access'); ?>
<div class="row-fluid">
    <div class="span6">
        <h3>E-mail</h3>
        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('email_subject'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('email_subject'); ?>
            </div>
        </div>
        <?php echo $this->form->getInput('email'); ?>
    </div>
    <div class="span6">
        <h3>Notificatie</h3>
        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('notification_subject'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('notification_subject'); ?>
            </div>
        </div>
        <?php echo $this->form->getInput('notification'); ?>
    </div>
</div>



