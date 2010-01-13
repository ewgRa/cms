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
	
	$predifinedAttributes = array('license', 'author', 'DAExtends');
	
	foreach ($xslBuilders as $builderName => $builderFile) {
		${$builderName} =
			XsltView::create()->
			loadLayout(File::create()->setPath($builderFile));
	}
	
	foreach ($meta->getDocumentElement()->childNodes as $node) {
		if ($node->nodeType !== XML_ELEMENT_NODE)
			continue;

		foreach ($predifinedAttributes as $attr) {
			if ($value = $meta->getDocumentElement()->getAttribute($attr))
				$node->setAttribute($attr, $value);
		}
			
		$propertiesNode = $node->getElementsByTagName('properties')->item(0);

		foreach ($propertiesNode->childNodes as $propertyNode) {
			if ($propertyNode->nodeType !== XML_ELEMENT_NODE)
				continue;
			
			$propertyNode->setAttribute(
				'upperName',
				StringUtils::upperKeyFirstAlpha($propertyNode->nodeName)
			);
			
			$propertyNode->setAttribute(
				'downSeparatedName',
				StringUtils::separateByUpperKey($propertyNode->nodeName)
			);
			
			if ($propertyNode->getAttribute('relation')) {
				$relationNode = $meta->createElement($propertyNode->nodeName.'Id');

				foreach ($propertyNode->attributes as $attrName => $attrValue) {
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
				
				$propertiesNode->insertBefore($relationNode, $propertyNode);
			}
		}
		
		$dom = ExtendedDomDocument::create()->loadXML($meta->saveXML($node));

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