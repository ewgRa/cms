<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class ViewFactory
	{
		/**
		 * @var ViewFactoryCacheWorker
		 */
		private static $cacheWorker = null;
			
		/**
		 * @var ViewDA
		 */
		private static $da = null;
		
		public static function da()
		{
			if(!self::$da)
				self::$da = ViewDA::create();
				
			return self::$da;
		}
		
		public static function cacheWorker()
		{
			if(!self::$cacheWorker)
				self::$cacheWorker = ViewFactoryCacheWorker::create();
				
			return self::$cacheWorker;
		}
		
		/**
		 * @return ViewInterface
		 */
		public static function createByFileId($fileId)
		{
			$cacheTicket = self::cacheWorker()->createTicket();
			
			if($cacheTicket)
			{
				$cacheTicket->addKey($fileId);
				$cacheTicket->restoreData();
			}
			
			$result = null;
			
			if(!$cacheTicket || $cacheTicket->isExpired())
			{
				$result = self::uncachedCreateByFileId($fileId);
				
				if($cacheTicket)
					$cacheTicket->setData($result)->storeData();
			}
			else
				$result = $cacheTicket->getData();
			
			Assert::isNotNull($result);
			
			return $result;
		}
		
		private static function uncachedCreateByFileId($fileId)
		{
			$result = null;
			
			if($file = self::da()->getFile($fileId))
			{
				$file['path'] = Config::me()->replaceVariables($file['path']);
				
				$layout = File::create()->setPath($file['path']);
				
				$mimeType = MimeContentType::createByName($file['content-type']);

				switch($mimeType->getId())
				{
					case MimeContentType::TEXT_XSLT:
						$result = XsltView::create();
						
						$projectConfig = Config::me()->getOption('project');
						
						if(isset($projectConfig['charset']))
							$result->setCharset($projectConfig['charset']);
						
						$result->loadLayout($layout);
					break;
					case MimeContentType::APPLICATION_PHP:
						$result = PhpView::create()->loadLayout($layout);
					break;
				}
			}
			else
				throw NotFoundException::create('No layout file');
			
			Assert::isNotNull($result);
			
			return $result;
		}

		private static function getLayoutIncludeFiles($fileId)
		{
			$result = array();
				
			foreach($this->da()->getLayoutIncludeFiles($fileId) as $file)
			{
				$result[] = str_replace(
					'\\',
					'/',
					realpath(Config::me()->replaceVariables($file['path']))
				);
			}
			
			return $result;
		}
	}
?>