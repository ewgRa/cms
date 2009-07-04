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
		 * @return BaseView
		 * FIXME: cache!
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
			
			return $result;
		}
		
		private static function uncachedCreateByFileId($fileId)
		{
			$result = null;
			
			if($file = self::da()->getFile($fileId))
			{
				$file['path'] = Config::me()->replaceVariables($file['path']);
				
				switch($file['content-type'])
				{
					case MimeContentTypes::TEXT_XSLT:
						$result = XsltView::create()->loadLayout(
							$file['path'], $file['id']
						);
						
						$projectConfig = Config::me()->getOption('project');
						
						if(isset($projectConfig['charset']))
							$result->setCharset($projectConfig['charset']);
					break;
					case MimeContentTypes::APPLICATION_PHP:
						$result = PhpView::create()->loadLayout(
							$file['path'], $file['id']
						);
					break;
				}
			}
			else
				throw NotFoundException::create()->
					setMessage('No layout file');
			
			return $result;
		}
	}
?>