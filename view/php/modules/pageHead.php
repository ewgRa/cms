<?php
	namespace ewgraCms;
	
	if ($pageData->getTitle()) {
?>
	<title><?=htmlspecialchars($pageData->getTitle())?></title>
<?php
    }

	if ($pageData->getDescription()) {
?>
	<meta name="description" content="<?=htmlspecialchars($pageData->getDescription())?>"></meta>
<?php
	}

	if ($pageData->getKeywords()) {
?>
	<meta name="keywords" content="<?=htmlspecialchars($pageData->getKeywords())?>"></meta>
<?php
	}
?>