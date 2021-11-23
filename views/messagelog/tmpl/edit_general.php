<?php defined('_JEXEC') or die('Restricted access'); ?>

<div class="row-fluid">
    <?php if($this->item->is_email): ?>
    <div class="span6">
        <div class="clearfix">
            <h3 class="pull-left">E-mail</h3>
        </div>

        <table class="table table-striped">
            <tbody>
                <tr>
                    <td><strong><?php echo JText::_('Verzonden op'); ?></strong></td>
                    <td><?php echo JFactory::getDate($this->item->created)->format('l j F H:i'); ?></td>
                </tr>
                <tr>
                    <td><strong><?php echo JText::_('Verzonden naar'); ?></strong></td>
                    <td><?php echo $this->item->email_to; ?></td>
                </tr>
                <tr>
                    <td><strong><?php echo JText::_('Onderwerp'); ?></strong></td>
                    <td><?php echo $this->item->email_subject; ?></td>
                </tr>
                <tr>
                    <td><strong><?php echo JText::_('Geopend?'); ?></strong></td>
                    <td><?php echo $this->item->opened == 1 ? JText::_('JYES') : JText::_('JNO'); ?></td>
                </tr>
                <?php if($this->item->opened == 1 && !is_null($this->item->opened_on)): ?>
                    <tr>
                        <td><strong><?php echo JText::_('Geopend op'); ?></strong></td>
                        <td><?php echo JFactory::getDate($this->item->opened_on)->format('l j F H:i'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    <?php if($this->item->is_notification): ?>
    <div class="span6">
        <div class="clearfix">
            <h3 class="pull-left">Notificatie</h3>
        </div>

        <table class="table table-striped">
            <tbody>
            <tr>
                <td><strong><?php echo JText::_('Verzonden op'); ?></strong></td>
                <td><?php echo JFactory::getDate($this->item->created)->format('l j F H:i'); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo JText::_('Verzonden naar'); ?></strong></td>
                <td><?php echo $this->item->user_name; ?></td>
            </tr>
            <tr>
                <td><strong><?php echo JText::_('Onderwerp'); ?></strong></td>
                <td><?php echo $this->item->notification_subject; ?></td>
            </tr>
            <tr>
                <td><strong><?php echo JText::_('Gelezen?'); ?></strong></td>
                <td><?php echo $this->item->notification_read == 1 ? JText::_('JYES') : JText::_('JNO'); ?></td>
            </tr>
            <?php if($this->item->notification_read == 1 && !is_null($this->item->notification_read_on)): ?>
                <tr>
                    <td><strong><?php echo JText::_('Gelezen op'); ?></strong></td>
                    <td><?php echo JFactory::getDate($this->item->notification_read_on)->format('l j F H:i'); ?></td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<div class="row-fluid">

    <?php if($this->item->is_email): ?>
    <div class="span6">
        <div class="well">
            <?php echo $this->item->email_body; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php if($this->item->is_notification): ?>
    <div class="span6">
        <div class="well">
            <?php echo $this->item->notification_body; ?>
        </div>
    </div>
    <?php endif; ?>
</div>






