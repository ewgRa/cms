<?php
	$contentDataList 	= $model->get('contentDataList');
	$replaceFilter 		= $model->get('replaceFilter');
	
	foreach ($model->get('contentList') as $content)
		echo $contentDataList[$content->getId()]->getText();
?>