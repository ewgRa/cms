<?php
	if ($pageData->getTitle()) {
?>
	<title><?=$pageData->getTitle()?></title>
<?php
    }

	if ($pageData->getDescription()) {
?>
	<meta name="description" content="<?=$pageData->getDescription()?>"></meta>
<?php
	}

	if ($pageData->getKeywords()) {
?>
	<meta name="description" content="<?=$pageData->getKeywords()?>"></meta>
<?php
	}
?>