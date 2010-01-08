<?php
	/* $Id$ */

	require_once(dirname(__FILE__).'/init.php');

	$curDir = $argv[1];
	
	define('CLASSES_DIR', $curDir.'/classes');
	define('META_FILE', $curDir.'/config/meta.xml');
	
	$meta = ExtendedDomDocument::create();
	
	$meta->load(META_FILE);
	
	$xslBuilders = array(
		'AutoBusinessClass' => META_BUILDER_DIR.'/xsl/AutoBusinessClass.xsl',
		'BusinessClass' => META_BUILDER_DIR.'/xsl/BusinessClass.xsl'
	);
	
	$targetFiles = array(
		'AutoBusinessClass' =>
			array(CLASSES_DIR.'/business/auto/Auto', '.class.php'),
		'BusinessClass' =>
			array(CLASSES_DIR.'/business/', '.class.php')
	);
	
	$protectedFiles = array(
		'BusinessClass' => true
	);
	
	foreach ($xslBuilders as $builderName => $builderFile) {
		${$builderName} =
			XsltView::create()->
			loadLayout(File::create()->setPath($builderFile));
	}
	
	foreach ($meta->getDocumentElement()->childNodes as $node) {
		if ($node->nodeType !== XML_ELEMENT_NODE)
			continue;

		if ($license = $meta->getDocumentElement()->getAttribute('license'))
			$node->setAttribute('license', $license);
			
		if ($author = $meta->getDocumentElement()->getAttribute('author'))
			$node->setAttribute('author', $author);
			
		$dom = ExtendedDomDocument::create();
		
		foreach ($node->childNodes as $childNode) {
			if ($childNode->nodeType !== XML_ELEMENT_NODE)
				continue;

			$childNode->setAttribute(
				'upperName',
				StringUtils::upperKeyFirstAlpha($childNode->nodeName)
			);
			
			if ($childNode->getAttribute('relation')) {
				$relationNode = $meta->createElement($childNode->nodeName.'Id');

				$relationNode->setAttribute(
					'upperName',
					StringUtils::upperKeyFirstAlpha($relationNode->nodeName)
				);
				
				$node->insertBefore($relationNode, $childNode);
			}
		}
		
		$dom->loadXML($meta->saveXML($node));

		foreach ($xslBuilders as $builderName => $builderFile) {
			$file =
				File::create()->setPath(
					join($node->nodeName, $targetFiles[$builderName])
				);
			
			if (!isset($protectedFiles[$builderName]) || !$file->isExists())
				$file->setContent(${$builderName}->transformXML($dom));
		}
	}
?>