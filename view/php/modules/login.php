<?php
	if (
		!$model->has('loginResult')
		|| $model->get('loginResult') != LoginModule::SUCCESS_LOGIN
	) {
		$backurl =
			$model->has('backurl')
				? $model->get('backurl')
				: null;
?>
	<form method="<?php echo $model->get('source');?>">
		<input type="hidden" name="backurl" value="<?php echo $backurl;?>" /><br />
		Login: <input type="text" name="login" /><br />
		Password: <input type="text" name="password" />
		<input type="submit" />
	</form>
<?php
	} else {
?>
	Success login
<?php
	}
?>