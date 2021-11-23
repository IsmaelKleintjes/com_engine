<?php defined('_JEXEC') or die; 
$colsPerRow = 6; // SETTING - Alleen dit getal wijzigen, indien meer of minder per rij.
$colSpan = 12 / $colsPerRow;
$i=0;
?>
<style>
	.dashboard i.fa { margin-top:20px;height:50px; }
	.dashboard a.thumbnail { text-decoration:none;height:120px;text-align:center;margin-bottom:15px;background-color:#FFF; }
	h1.well { color:#0D6DAB;text-align:center; }
</style>
<link rel="stylesheet" type="text/css" href="<?php echo JURI::base() ?>components/com_<?php echo COMPONENT; ?>/assets/css/font-awesome.min.css" />
<div class="row-fluid">
    <h1 class="well span8"><?php echo $this->mvcGroups['clientTitle']; ?></h1>
    <div class="span4 pull-right">
        <table class="table table-striped table-bordered">
            <tbody>
            <?php foreach($this->mvcGroups['componentInfo'] as $label => $value): ?>
                <tr>
                    <td class="muted" style="text-align:right;"><?php echo $label; ?></td>
                    <td class="text-info"><?php echo $value; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<ul class="nav nav-tabs">
<?php foreach($this->mvcGroups as $key => $mvcGroup): $col=1; ?>
	<?php if(is_numeric($key)): ?>
    	<li class="<?php echo ($key==0) ? "active" : ""; ?>">
        	<a href="#dashboardTab<?php echo $key; ?>" data-toggle="tab"><?php echo JText::_($mvcGroup["title"]); ?></a>
        </li>
	<?php endif; ?>
<?php endforeach; ?>
</ul>
<div class="row-fluid dashboard">
	<div class="span8">
        <div class="tab-content">
        <?php foreach($this->mvcGroups as $key => $mvcGroup): $col=1; ?>
			<?php if(is_numeric($key)): ?>
				<div class="tab-pane <?php echo ($key==0) ? "active" : ""; ?>" id="dashboardTab<?php echo $key; ?>">
					<?php foreach($mvcGroup["objects"] as $vKey => $view): ?>
                        <?php if($col==1): ?><div class="row-fluid"><?php endif; ?>
                        <a href="<?php echo $view["url"]; ?>" class="thumbnail span<?php echo $colSpan; ?>">
                        	<i class="fa fa-<?php echo $view["icon"]; ?> fa-4x"></i>
                            <br />
                            <?php echo $view["title"]; ?>
                        </a>
                        <?php if($col==$colsPerRow||!is_array($mvcGroup["objects"][$vKey+1])): $col=1; ?></div><?php else: $col++; endif; ?>
                    <?php endforeach; ?>
                </div>
			<?php endif; ?>
        <?php endforeach; ?>
        </div>
	</div>
    <?php if(App4U::isMe()): ?>
        <div class="span4">
            <div class="row-fluid">
                <a href="index.php?option=com_engine&view=sqlupdates" class="thumbnail span4">
                    <i class="fa fa-database fa-4x"></i>
                    <br>SQL Updates
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<a href='?option=com_engine&view=companies'>Companies
<br><a href='?option=com_engine&view=contacts'>Contacts