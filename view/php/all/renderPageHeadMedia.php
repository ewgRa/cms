<?php
	if($model->has('includeFiles'))
	{
		foreach($model->get('includeFiles') as $file)
		{
			switch($file['content-type']->getId())
			{
				case MimeContentType::TEXT_JAVASCRIPT:
?>
	<script type="<?php echo $file['content-type']?>" src="<?php echo $file['path']?>"></script>
<?php
				break;
				default:
?>
	<link rel="stylesheet" type="<?php echo $file['content-type']->getName()?>" href="<?php echo $file['path']?>" />
<?php
				break;
			}
		}
	}
?>