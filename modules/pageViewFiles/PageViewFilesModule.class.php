<?php
	/* $Id: PageViewFilesController.class.php 58 2008-08-20 03:24:57Z ewgraf $ */

	class PageViewFilesModule extends CmsModule
	{
		private $joinContentTypes = array();
		
		/**
		 * @var PageViewFilesCacheWorker
		 */
		private $cacheWorker = null;
		
		/**
		 * @return PageViewFilesCacheWorker
		 */
		public function cacheWorker()
		{
			if(!$this->cacheWorker)
				$this->cacheWorker = PageViewFilesCacheWorker::create()->
					setModule($this);

			return $this->cacheWorker;
		}
		
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
		
		public function importSettings($settings)
		{
			if($cacheTicket = $this->cacheWorker()->createTicket())
			{
				$cacheTicket->addKey($settings);
				$this->setCacheTicket($cacheTicket);
			}

			if(isset($settings['joinContentTypes']) && is_array($settings['joinContentTypes']))
			{
				foreach($settings['joinContentTypes'] as $contentTypeName)
				{
					$contentType = ContentType::createByName($contentTypeName);
					
					if(!$contentType->canBeJoined()) {
						throw DefaultException::create(
							'Don\'t know how join content-type ' . $contentType
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
			$page = $this->getRequest()->getAttachedVar(AttachedAliases::PAGE);
			
			$viewFiles = ViewFile::da()->getByPage($page);
			
			$inheritanceFiles =
				array_diff_assoc(
					ViewFile::da()->getInheritanceByIds(array_keys($viewFiles)),
					$viewFiles
				);
				
			$viewFiles = $inheritanceFiles;
			
			while($inheritanceFiles) {
				$viewFiles = $viewFiles+$inheritanceFiles;
				
				$inheritanceFiles =
					array_diff_assoc(
						ViewFile::da()->getInheritanceByIds(array_keys($inheritanceFiles)),
						$viewFiles
					);
			}
			
			if($this->getJoinContentTypes())
				$viewFiles = $this->joinFiles($viewFiles);
			
			foreach($viewFiles as $file)
			{
				if(
					defined('MEDIA_HOST_JOIN_URL')
					&& $file->getContentType()->canBeJoined()
					&& $file instanceof JoinedViewFile
				) {
					$file->setPath(MEDIA_HOST_JOIN_URL . '/' . $file->getPath());
				}
			}
			
			return Model::create()->setData($viewFiles);
		}

		private function joinFiles($viewFiles)
		{
			$files =
				MediaFilesJoiner::create()->
				setContentTypes($this->getJoinContentTypes())->
				joinFileNames($viewFiles);
				
			if(!file_exists(JOIN_FILES_DIR)) {
				$umask = umask(0);
				mkdir(JOIN_FILES_DIR, FileBasedCache::DIR_PERMISSIONS, true);
				umask($umask);
			}
			
			foreach($files as $file) {
				if($file instanceof JoinedViewFile)
					$file->saveFileList();
			}

			return $files;
		}
	}
?>