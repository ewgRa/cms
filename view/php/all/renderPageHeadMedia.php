<?php
	if($model['includeFiles'])
	{
		foreach($model['includeFiles'] as $file)
		{
			switch($file['content-type'])
			{
				case MimeContentTypes::TEXT_JAVASCRIPT:
?>
	<script type="<?php echo $file['content-type']?>" src="<?php echo $file['path']?>"></script>
<?php
				break;
				default:
?>
	<link rel="stylesheet" type="<?php echo $file['content-type']?>" href="<?php echo $file['path']?>" />
<?php
				break;
			}
		}
	}
?>