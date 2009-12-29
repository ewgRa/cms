<?php
	$pageData = $model->get('pageData');
	
	if ($pageData->getTitle()) {
?>
	<title><?php echo $pageData->getTitle()?></title>
<?php
    }

	if ($pageData->getDescription()) {
?>
	<meta name="description" content="<?php echo $pageData->getDescription()?>"></meta>
<?php
	}

	if ($pageData->getKeywords()) {
?>
	<meta name="description" content="<?php echo $pageData->getKeywords()?>"></meta>
<?php
	}
?>