<?php
	/* $Id: PageViewFilesController.class.php 58 2008-08-20 03:24:57Z ewgraf $ */

	class PageViewFilesController extends Controller
	{
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
		
		public function importSettings($settings)
		{
			if(Cache::me()->hasTicketParams('pageViewFiles'))
			{
				$this->setCacheTicket(
					Cache::me()->createTicket('pageViewFiles')->
						setKey(Page::me()->getId())
				);
			}
			
			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$viewFilesId = array(Page::me()->getLayoutFileId());
						
			foreach($this->da()->getPageViewFiles(Page::me()->getId()) as $file)
				$viewFilesId[] = $file['view_file_id'];
			
			return Model::create()->setData($this->getPageViewFiles($viewFilesId));
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