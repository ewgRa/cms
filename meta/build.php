<?php
	/* $Id$ */

	require_once(dirname(__FILE__).'/init.php');

	$curDir = $argv[1];
	
	define('CLASSES_DIR', $curDir.'/classes');
	define('META_FILE', $curDir.'/config/meta.xml');
	
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
	
	$meta = ExtendedDomDocument::create();
	$meta->load(META_FILE);
	
	preConfigure($meta);
	
	$classNode = $meta->createElement('className', null);
	
	$meta->getDocumentElement()->insertBefore(
		$classNode,
		$meta->getDocumentElement()->childNodes->item(0)
	);
	
	foreach ($meta->getDocumentElement()->childNodes as $node) {
		if (
			$node->nodeType !== XML_ELEMENT_NODE
			|| $node->nodeName == 'className'
		)
			continue;

		$classNode->nodeValue = $node->nodeName;
		
		foreach ($xslBuilders as $builderName => $builderFile) {
			$file =
				File::create()->setPath(
					join($node->nodeName, $targetFiles[$builderName])
				);
			
			if (!isset($protectedFiles[$builderName]) || !$file->isExists())
				$file->setContent(${$builderName}->transformXML($meta));
		}
	}
	
	function preConfigure(ExtendedDomDocument $meta)
	{
		foreach ($meta->getDocumentElement()->childNodes as $node) {
			if ($node->nodeType !== XML_ELEMENT_NODE)
				continue;
	
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
		}
	}
?>