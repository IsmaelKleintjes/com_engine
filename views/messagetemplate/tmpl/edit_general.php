<?php defined('_JEXEC') or die('Restricted access'); ?>
<div class="span6 form-horizontal">
    <?php echo $this->detail->fieldset("essential"); ?>
</div>

<div class="span6 form-horizontal" style="margin-left: 0;">
    <?php echo $this->detail->fieldset("test"); ?>
    <div class='control-group'>
        <div class="controls">
            <button class="btn" type="button" onclick="Joomla.submitbutton('messagetemplate.test')"><?php echo JText::_( 'Verstuur' ); ?></button>
        </div>
    </div>
    <script type="text/javascript">
        Joomla.submitbutton = function(task)
        {
            if (task == 'messagetemplate.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
                Joomla.submitform(task, document.getElementById('adminForm'));
            }
        }
    </script>
</div>
