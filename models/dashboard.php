<?php defined('_JEXEC') or die;

class EngineModelDashboard extends JModelList
{
	public function addDashboard($name)
	{
		$mcvGroup = EngineHelper::getActiveMvcGroup($name);
		
		if(is_array($mcvGroup)) 
		{
			foreach($mcvGroup['objects'] as $object): ?>
			 <div class="row-fluid">
				<div class="span12">
					<a href="<?php echo $object['url']; ?>">
						<i class="icon-<?php echo $object['icon']; ?>"></i> <span><?php echo $object['title']; ?></span></a>
				</div>
			</div>
		<?php endforeach;
		}
	}
}