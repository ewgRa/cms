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
		'BusinessClass' => META_BUILDER_DIR.'/xsl/BusinessClass.xsl',
		'AutoDAClass' => META_BUILDER_DIR.'/xsl/AutoDAClass.xsl',
		'DAClass' => META_BUILDER_DIR.'/xsl/DAClass.xsl'
	);
	
	$targetFiles = array(
		'AutoBusinessClass' => array(CLASSES_DIR.'/business/auto/Auto', '.class.php'),
		'BusinessClass' => array(CLASSES_DIR.'/business/', '.class.php'),
		'AutoDAClass' => array(CLASSES_DIR.'/da/auto/Auto', 'DA.class.php'),
		'DAClass' => array(CLASSES_DIR.'/da/', 'DA.class.php')
	);
	
	$protectedFiles = array(
		'BusinessClass' => true,
		'DAClass' => true
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
			
		if ($DAExtends = $meta->getDocumentElement()->getAttribute('DAExtends'))
			$node->setAttribute('DAExtends', $DAExtends);
			
		$dom = ExtendedDomDocument::create();
		
		foreach ($node->childNodes as $childNode) {
			if ($childNode->nodeType !== XML_ELEMENT_NODE)
				continue;

			$childNode->setAttribute(
				'upperName',
				StringUtils::upperKeyFirstAlpha($childNode->nodeName)
			);
			
			$childNode->setAttribute(
				'downSeparatedName',
				StringUtils::separateByUpperKey($childNode->nodeName)
			);
			
			if ($childNode->getAttribute('relation')) {
				$relationNode = $meta->createElement($childNode->nodeName.'Id');

				foreach ($childNode->attributes as $attrName => $attrValue) {
					if ($attrName != 'relation' && $attrName != 'type')
						$relationNode->setAttribute($attrName, $attrValue->value);
				}
				
				$relationNode->setAttribute(
					'upperName',
					StringUtils::upperKeyFirstAlpha($relationNode->nodeName)
				);
				
				$relationNode->setAttribute(
					'downSeparatedName',
					StringUtils::separateByUpperKey($relationNode->nodeName)
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