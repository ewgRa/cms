<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ViewFactory
	{
		/**
		 * @return ViewInterface
		 */
		public static function createByViewFile(ViewFile $viewFile)
		{
			$result = null;
			
			$layout = File::create()->setPath($viewFile->getPath());
				
			switch ($viewFile->getContentType()->getId()) {
				case ContentType::TEXT_XSLT:
					$result = XsltView::create();
					
					$projectConfig = Config::me()->getOption('project');
					
					if (isset($projectConfig['charset']))
						$result->setCharset($projectConfig['charset']);
					
					$result->loadLayout($layout);
					
					break;
				case ContentType::APPLICATION_PHP:
					$result = PhpView::create()->loadLayout($layout);
					
					break;
			}
			
			Assert::isNotNull($result);
			
			return $result;
		}
	}
?>