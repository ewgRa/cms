<?php
	/* $Id: PageViewFilesController.class.php 58 2008-08-20 03:24:57Z ewgraf $ */

	class PageViewFilesModule extends CmsModule
	{
		private $joinContentTypes = array();
		
		/**
		 * @var PageViewFilesCacheWorker
		 */
		private $cacheWorker = null;
		
		protected function da()
		{
			return PageViewFilesDA::me();
		}
		
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
			
			$viewFilesId = array($page->getLayoutFileId());
			
			foreach($this->da()->getPageViewFiles($page) as $file)
				$viewFilesId[] = $file['view_file_id'];
			
			$viewFiles = $this->getPageViewFiles($page, $viewFilesId);
			
			if($this->getJoinContentTypes())
				$viewFiles = $this->joinFiles($viewFiles);
			
			foreach($viewFiles['includeFiles'] as &$file)
			{
				if(
					defined('MEDIA_HOST_JOIN_URL')
					&& $file['content-type']->canBeJoined()
				) {
					if(isset($file['files']))
						$file['path'] = MEDIA_HOST_JOIN_URL . '/' . $file['path'];
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
			
			foreach($files['includeFiles'] as $file) {
				if(isset($file['files'])) {
					$targetFile =
						JOIN_FILES_DIR . DIRECTORY_SEPARATOR . $file['path'] . '.fl';

					file_put_contents($targetFile, serialize($file['files']));
				}
			}

			return $files;
		}

		private function getPageViewFiles(Page $page, $fileIds)
		{
			$viewFiles = array(
				'includeFiles' => array(),
				'dontJoinFiles' => array()
			);
			
			$directFiles = array();
			
			foreach($this->da()->getFiles($page, $fileIds) as $file)
			{
				$file['path'] = str_replace(
					'\\',
					'/',
					Config::me()->replaceVariables($file['path'])
				);

				$viewFiles['includeFiles'][] = array(
					'path' => $file['path'],
					'content-type' =>
						ContentType::createByName($file['content_type']),
					'id' => $file['id']
				);
				
				if($file['is_can_joined'] == 'no')
					$viewFiles['dontJoinFiles'][] = $file['id'];

				if($file['recursive_include'] == 'yes')
					$directFiles[] = $file['id'];
			}

			if(count($directFiles))
			{
				$includeViewFiles = $this->{__FUNCTION__}($page, $directFiles);
				
				foreach($viewFiles as $k => $v)
					$viewFiles[$k] = array_merge($v, $includeViewFiles[$k]);
			}
		
			return $viewFiles;
		}
	}
?>