<?php
	/* $Id$ */

	class MediaFilesJoiner
	{
		private $mimeTypes = array();
		
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
		public function setMimeTypes($mimeTypes)
		{
			$this->mimeTypes = $mimeTypes;
			return $this;
		}
		
		public function getMimeTypes()
		{
			return $this->mimeTypes;
		}
		
		public function joinFileNames($files)
		{
			$bufferJoinFiles = array();
			
			$joinFiles = array();
			
			foreach($files['includeFiles'] as $file)
			{
				if(
					in_array($file['content-type']->getId(), array_keys($this->getMimeTypes()))
					&& !in_array($file['id'], $files['dontJoinFiles'])
				) {
					if(!isset($bufferJoinFiles[$file['content-type']->getId()]))
						$bufferJoinFiles[$file['content-type']->getId()] = array();
					
					$bufferJoinFiles[$file['content-type']->getId()][] = $file['path'];
				}
				else
					$joinFiles[] = $file;
			}
			
			foreach($bufferJoinFiles as $contentType => $files)
			{
				$mime = MimeContentType::create($contentType);
				
				$fileName = $this->compileFileName($files);
				
				$extension = $mime->getFileExtension();
				
				$fileName = $fileName . '.' . $extension;

				$joinFiles[] = array(
					'path' => $fileName,
					'content-type' => $mime,
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