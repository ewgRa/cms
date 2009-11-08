<?php
	if($model->has('title'))
    {
?>
	<title><?php echo $model->get('title')?></title>
<?php
    }

    if($model->has('description'))
    {
?>
	<meta name="description" content="<?php echo $model->get('description')?>"></meta>
<?php
    }

    if($model->has('keywords'))
    {
?>
	<meta name="description" content="<?php echo $model->get('keywords')?>"></meta>
<?php
    }
?>