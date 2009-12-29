	<ul>
<?php
	$navigationDataList = $model->get('navigationDataList');
	
	foreach ($model->get('navigationList') as $navigation) {
?>
		<li><a href="<?php echo $model->get('baseUrl').$navigation->getUri()->getPath()?>"><? echo $navigationDataList[$navigation->getId()]->getText()?></a></li>
<?php
	}
?>
	</ul>