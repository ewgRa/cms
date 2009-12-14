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
			
			foreach($files['includeFiles'] as $file)
			{
				if(
					in_array($file['content-type']->getId(), array_keys($this->getContentTypes()))
					&& !in_array($file['id'], $files['dontJoinFiles'])
				) {
					if(!isset($bufferJoinFiles[$file['content-type']->getId()]))
						$bufferJoinFiles[$file['content-type']->getId()] = array();
					
					$bufferJoinFiles[$file['content-type']->getId()][] = $file['path'];
				}
				else
					$joinFiles[] = $file;
			}
			
			foreach($bufferJoinFiles as $contentTypeName => $files)
			{
				$contentType = ContentType::create($contentTypeName);
				
				$fileName = $this->compileFileName($files);
				
				$extension = $contentType->getFileExtension();
				
				$fileName = $fileName . '.' . $extension;

				$joinFiles[] = array(
					'path' => $fileName,
					'content-type' => $contentType,
					'files' => $files
				);
			}
			
			return array('includeFiles' => $joinFiles);
		}
		
		private function compileFileName($filePathes)
		{
			return md5(serialize($filePathes));
		}
	}
?>