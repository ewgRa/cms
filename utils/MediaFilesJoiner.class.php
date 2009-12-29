<?php
	/* $Id$ */

	class MediaFilesJoiner
	{
		private $contentTypes = array();
		
		/**
		 * @return MediaFilesJoiner
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return MediaFilesJoiner
		 */
		public function setContentTypes($contentTypes)
		{
			$this->contentTypes = $contentTypes;
			return $this;
		}
		
		public function getContentTypes()
		{
			return $this->contentTypes;
		}
		
		public function joinFileNames($files)
		{
			$bufferJoinFiles = array();
			
			$joinFiles = array();
			
			foreach($files as $file)
			{
				if(
					in_array($file->getContentType()->getId(), array_keys($this->getContentTypes()))
					&& $file->isJoinable()
				) {
					if(!isset($bufferJoinFiles[$file->getContentType()->getId()]))
						$bufferJoinFiles[$file->getContentType()->getId()] = array();
					
					$bufferJoinFiles[$file->getContentType()->getId()][] = $file;
				}
				else
					$joinFiles[] = $file;
			}
			
			foreach($bufferJoinFiles as $contentTypeName => $files)
			{
				$contentType = ContentType::create($contentTypeName);
				
				$joinFiles[] =
					JoinedViewFile::create()->
					setFiles($files)->
					setContentType($contentType);
			}
			
			return $joinFiles;
		}
	}
?>