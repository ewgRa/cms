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
			
			if($file = ViewFile::da()->getById($fileId))
			{
				$layout = File::create()->setPath($file->getPath());
				
				switch($file->getContentType()->getId())
				{
					case ContentType::TEXT_XSLT:
						$result = XsltView::create();
						
						$projectConfig = Config::me()->getOption('project');
						
						if(isset($projectConfig['charset']))
							$result->setCharset($projectConfig['charset']);
						
						$result->loadLayout($layout);
					break;
					case ContentType::APPLICATION_PHP:
						$result = PhpView::create()->loadLayout($layout);
					break;
				}
			}
			else
				throw NotFoundException::create('No layout file');
			
			Assert::isNotNull($result);
			
			return $result;
		}
	}
?>