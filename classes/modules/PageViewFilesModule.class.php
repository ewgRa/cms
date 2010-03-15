<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * FIXME: cache needed for avoid generate file list?
	*/
	final class PageViewFilesModule extends CmsModule
	{
		private $joinContentTypes = array();
		
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

			$dir = Dir::create()->setPath(JOIN_FILES_DIR);
			
			if (!$dir->isExists())
				$dir->make();
			
			foreach ($files as $file) {
				if($file instanceof JoinedViewFile) {
					$this->createJoinedListsCacheTicket()->
						setData($file->getFiles())->
						setKey($file->getPath())->
						storeData();
					
					if (defined('MEDIA_HOST_JOIN_URL'))
						$file->setPath(MEDIA_HOST_JOIN_URL.'/'.$file->getPath());
				}
			}

			return $files;
		}
	}
?>