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
			$cacheTicket =
				ViewFile::da()->getCacheTicket()->
				setKey(get_class($this), __FUNCTION__, $this->getPage())->
				restoreData();
				
			$viewFiles = $cacheTicket->getData();
				
			if ($viewFiles)
				return Model::create()->set('files', $viewFiles);

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
			
			$cacheTicket->setData($viewFiles)->storeData();
			ViewFile::da()->addTicketToTag($cacheTicket);
			
			return Model::create()->set('files', $viewFiles);
		}

		public static function createJoinedListsCacheTicket()
		{
			$pool = Cache::me()->getPool('cms');
			Assert::isTrue($pool->hasTicketParams('JoinedViewFilesLists'));
			return $pool->createTicket('JoinedViewFilesLists');
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