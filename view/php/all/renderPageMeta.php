<?php
    if(isset($model['title']))
    {
?>
	<title><?php echo $model['title']?></title>
<?php
    }

    if(isset($model['description']))
    {
?>
	<meta name="description" content="<?php echo $model['description']?>"></meta>
<?php
    }

    if(isset($model['keywords']))
    {
?>
	<meta name="description" content="<?php echo $model['keywords']?>"></meta>
<?php
    }
?>