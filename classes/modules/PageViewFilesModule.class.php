<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageViewFilesModule extends CmsModule
	{
		private $joinContentTypes = array();
		
		private $additionalJoinUrl = '/join';
		
		/**
		 * @return PageViewFilesModule
		 */
		public function addJoinContentType(ContentType $contentType)
		{
			$this->joinContentTypes[$contentType->getId()] = $contentType;
			return $this;
		}
		
		public function getJoinContentTypes()
		{
			return $this->joinContentTypes;
		}
		
		/**
		 * @return PageViewFilesModule
		 */
		public function importSettings(array $settings = null)
		{
			if(isset($settings['additionalJoinUrl']))
				$this->additionalJoinUrl = $settings['additionalJoinUrl'];
			
			if(isset($settings['joinContentTypes'])) {
				Assert::isArray($settings['joinContentTypes']);
				
				foreach ($settings['joinContentTypes'] as $contentTypeName) {
					$contentType = ContentType::createByName($contentTypeName);
					
					if (!$contentType->canBeJoined()) {
						throw DefaultException::create(
							'Don\'t know how join content-type '.$contentType
						);
					}
				
					$this->addJoinContentType($contentType);
				}
			}
			
			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$cacheTicket = ViewFile::da()->createCacheTicket();
			
			if ($cacheTicket) {
				$cacheTicket->
					setPrefix(__CLASS__.'/'.__FUNCTION__)->
					setKey($this->getPage())->
					restoreData();
			}
				
			if ($cacheTicket && !$cacheTicket->isExpired())
				return $cacheTicket->getData();

			$viewFiles = ViewFile::da()->getByPage($this->getPage());
			
			$inheritanceFiles =
				array_diff_assoc(
					ViewFile::da()->getInheritanceByIds(array_keys($viewFiles)),
					$viewFiles
				);
				
			$viewFiles = $inheritanceFiles;
			
			while ($inheritanceFiles) {
				$viewFiles = $viewFiles+$inheritanceFiles;
				
				$inheritanceFiles =
					array_diff_assoc(
						ViewFile::da()->getInheritanceByIds(
							array_keys($inheritanceFiles)
						),
						$viewFiles
					);
			}
			
			if ($this->getJoinContentTypes())
				$viewFiles = $this->joinFiles($viewFiles);
			
			$model = Model::create()->set('files', $viewFiles);
			
			if ($cacheTicket) {
				$cacheTicket->setData($model)->storeData();
				ViewFile::da()->addTicketToTag($cacheTicket);
			}
			
			return $model;
		}

		public static function createJoinedListsCacheTicket()
		{
			return
				Cache::me()->getPool('cms')->
				createTicket()->
				setPrefix(__CLASS__.'/'.__FUNCTION__);
		}
		
		private function joinFiles(array $viewFiles)
		{
			$files =
				MediaFilesJoiner::create()->
				setContentTypes($this->getJoinContentTypes())->
				joinFiles($viewFiles);

			foreach ($files as $file) {
				if($file instanceof JoinedViewFile) {
					$this->createJoinedListsCacheTicket()->
						setData($file)->
						setKey($file->getPath())->
						storeData();
					
					if ($this->additionalJoinUrl)
						$file->setPath($this->additionalJoinUrl.'/'.$file->getPath());
				}
			}

			return $files;
		}
	}
?>