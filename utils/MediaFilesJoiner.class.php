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
					in_array($file['content-type'], array_keys($this->getMimeTypes()))
					&& !in_array($file['id'], $files['dontJoinFiles'])
				) {
					if(!isset($bufferJoinFiles[$file['content-type']]))
						$bufferJoinFiles[$file['content-type']] = array();
					
					$bufferJoinFiles[$file['content-type']][] = $file['path'];
				}
				else
					$joinFiles[] = $file;
			}
			
			foreach($bufferJoinFiles as $contentType => $files)
			{
				$fileName = $this->compileFileName($files);
				
				$extension = MimeContentTypes::getFileExtension($contentType);
				
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