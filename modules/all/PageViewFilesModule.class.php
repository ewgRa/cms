<?php
	/* $Id: PageViewFilesController.class.php 58 2008-08-20 03:24:57Z ewgraf $ */

	class PageViewFilesModule extends Module
	{
		const MAX_SPLIT_FILENAME_LENGTH = 255;
		
		private $splitMimes = array();
		
		/**
		 * @var PageViewFilesDA
		 */
		private $da = null;

		protected function da()
		{
			if(!$this->da)
				$this->da = PageViewFilesDA::create();
			
			return $this->da;
		}
		
		/**
		 * @return PageViewFilesModule
		 */
		public function addSplitMime($contentType)
		{
			$this->splitMimes[$contentType] = 1;
			return $this;
		}
		
		public function getSplitMimes()
		{
			return $this->splitMimes;
		}
		
		public function importSettings($settings)
		{
			if(Cache::me()->getPool('cms')->hasTicketParams('pageViewFiles'))
			{
				$this->setCacheTicket(
					Cache::me()->getPool('cms')->createTicket('pageViewFiles')->
						setKey(
							$this->getRequest()->getAttached(AttachedAliases::PAGE)->getId(),
							$settings
						)
				);
			}
			
			if(isset($settings['splitMimes']) && is_array($settings['splitMimes']))
			{
				foreach($settings['splitMimes'] as $mime)
				{
					if(!MimeContentTypes::isMediaFile($mime))
						throw
							DefaultException::create()->
								setMessage('Don\'t know anything about mime ' . $mime);
				
					$this->addSplitMime($mime);
				}
			}
			
			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$page = $this->getRequest()->getAttached(AttachedAliases::PAGE);
			
			$viewFilesId = array(
				$page->getLayoutFileId()
			);
			
			foreach($this->da()->getPageViewFiles($page) as $file)
				$viewFilesId[] = $file['view_file_id'];
			
			$viewFiles = $this->getPageViewFiles($page, $viewFilesId);
			
			if($this->getSplitMimes())
				$viewFiles = $this->splitFiles($viewFiles);
			
			foreach($viewFiles['includeFiles'] as &$file)
			{
				if(
					defined('MEDIA_HOST')
					&& MimeContentTypes::isMediaFile($file['content-type'])
				) {
					$parsedUrl = parse_url($file['path']);
					
					if(!isset($parsedUrl['host']))
						$file['path'] = MEDIA_HOST . $file['path'];
				}
			}
			
			return Model::create()->setData($viewFiles);
		}
		
		private function splitFiles($viewFiles)
		{
			return MediaFilesSplitter::create()->
				setMaxFileNameLength(self::MAX_SPLIT_FILENAME_LENGTH)->
				setBeginPartUrl(MEDIA_HOST_SPLIT_URL)->
				setMimeTypes($this->getSplitMimes())->
				splitFileNames($viewFiles);
		}

		private function getPageViewFiles(Page $page, $fileIds)
		{
			$viewFiles = array(
				'includeFiles' => array(),
				'dontSplitFiles' => array()
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
					'content-type' => $file['content-type'],
					'id' => $file['id']
				);
				
				if($file['is_can_splited'] == 'no')
					$viewFiles['dontSplitFiles'][] = $file['id'];

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