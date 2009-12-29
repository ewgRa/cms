<?php
	if ($model->getData()) {
		foreach ($model->getData() as $file) {
			switch ($file->getContentType()->getId()) {
				case ContentType::TEXT_JAVASCRIPT:
?>
	<script type="<?php echo $file->getContentType()?>" src="<?php echo $file->getPath()?>"></script>
<?php
				break;
				default:
?>
	<link rel="stylesheet" type="<?php echo $file->getContentType()?>" href="<?php echo $file->getPath()?>" />
<?php
				break;
			}
		}
	}
?>