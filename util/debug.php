<?php
	class CMSDebug
	{
		function Show()
		{
			$Session = new Session();
			$Session->Start();
			if( array_key_exists( 'Debug', $Session->Data ) )
			{
				$DebugData = array_pop( $Session->Data['Debug'] );
				if( array_key_exists( 'xml', $DebugData ) )
				{
					$XML = new DOMDocument();
					$XML->loadXML( $DebugData['xml'] );
		/*			$XSLTView = new XSLTView();
					$XSLTView->LayoutFile = CMS_DIR . '/view/xsl/backend/xml_view_like_browser.xsl';
					$XSLTView->LoadLayout();
					$XSLTView->XMLDocument = $XML;
					$XSLTView->Transform();
					$DebugData['xml'] = $XSLTView->Result;
		*/
					$file_name = CACHE_DIR . '/xml/debug/' . $Session->GetID() . '.xml';
					Cache::CreatePreDirs( $file_name );
					file_put_contents( $file_name, $XML->saveXML() );
					$DebugData['xml'] = '<a onclick="document.getElementById( \'xml_frame\' ).style.display = \'\'; this.style.display = \'none\';" target="xml_frame" href="/cache/xml/debug/' . $Session->GetID() . '.xml">here (' . filesize( $file_name ) . ' bytes)</a>';
				}
				if( array_key_exists( 'xslt', $DebugData ) )
				{
					$XML = new DOMDocument();
					$XML->loadXML( $DebugData['xslt'] );
/*					$XSLTView = new XSLTView();
					$XSLTView->LayoutFile = CMS_DIR . '/view/xsl/backend/xml_view_like_browser.xsl';
					$XSLTView->LoadLayout();
					$XSLTView->XMLDocument = $XML;
					$XSLTView->Transform();
					$DebugData['xslt'] = $XSLTView->Result;
*/				
					$file_name = CACHE_DIR . '/xsl/debug/' . $Session->GetID() . '.xsl';
					Cache::CreatePreDirs( $file_name );
					file_put_contents( $file_name, $XML->saveXML() );
					$DebugData['xslt'] = '<a onclick="document.getElementById( \'xsl_frame\' ).style.display = \'\'; this.style.display = \'none\';" target="xsl_frame" href="/cache/xsl/debug/' . $Session->GetID() . '.xsl">here (' . filesize( $file_name ) . ' bytes)</a>';
				}

				$DataCollector = new XMLDataCollector();
				$DataCollector->Set( $DebugData, array( 'DEBUG' ) );
				$XSLTView = new XSLTView();
				$XSLTView->LayoutFile = CMS_DIR . '/view/xsl/backend/debug_console.xsl';
				$XSLTView->LoadLayout();
				$XSLTView->XMLDocument = $DataCollector->GetData();
				$XSLTView->Transform();
		/*			header( 'Content-type: text/xml;' );
					echo $DebugData['xml'];
					die;
		*/
				echo $XSLTView->Result;
			}
		}
	}
?>