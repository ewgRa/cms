<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ViewFile extends AutoViewFile
	{
		/**
		 * @return ViewFile
		 */
		public static function create()
		{
			return new self;
		}

		public function createView()
		{
			$result = null;
			
			$path = $this->getPath();
			
			if ($this->getSource())
				$path = $this->getSource()->getPath().$path;
			
			$layout = File::create()->setPath($path);
				
			switch ($this->getContentType()->getId()) {
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
				default:
					Assert::isUnreachable();
			}
			
			return $result;
		}
	}
?>