<?php
	/* $Id: PageViewFilesController.class.php 58 2008-08-20 03:24:57Z ewgraf $ */

	class PageViewFilesController extends Controller
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
		 * @return PageViewFilesController
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
			if(Cache::me()->hasTicketParams('pageViewFiles'))
			{
				$this->setCacheTicket(
					Cache::me()->createTicket('pageViewFiles')->
						setKey(Page::me()->getId(), $settings)
				);
			}
			
			if(isset($settings['splitMimes']) && is_array($settings['splitMimes']))
			{
				foreach($settings['splitMimes'] as $mime)
				{
					if(!MimeContentTypes::isMediaFile($mime))
						throw
							ExceptionsMapper::me()->createException('Default')->
								setMessage('Don\'t know anything about mime ' . $mime);
				
					$this->addSplitMime($mime);
				}
			}
			
			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel(HttpRequest $request)
		{
			$viewFilesId = array(Page::me()->getLayoutFileId());
						
			foreach($this->da()->getPageViewFiles(Page::me()->getId()) as $file)
				$viewFilesId[] = $file['view_file_id'];
			
			$viewFiles = $this->getPageViewFiles($viewFilesId);
			
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

		private function getPageViewFiles($fileIds)
		{
			$viewFiles = array(
				'includeFiles' => array(),
				'dontSplitFiles' => array()
			);
			
			$directFiles = array();
			
			foreach($this->da()->getFiles(Page::me()->getId(), $fileIds) as $file)
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
				$includeViewFiles = $this->{__FUNCTION__}($directFiles);
				
				foreach($viewFiles as $k => $v)
					$viewFiles[$k] = array_merge($v, $includeViewFiles[$k]);
			}
		
			return $viewFiles;
		}
	}
?>