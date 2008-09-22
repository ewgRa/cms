<?php
	foreach($model['includeFiles'] as $file)
	{
?>
	<link rel="stylesheet" type="<?php echo $file['content-type']?>" href="<?php echo $file['path']?>" />
<?php
	}
?>