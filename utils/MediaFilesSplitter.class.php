<?php
	/* $Id$ */

	class MediaFilesSplitter
	{
		private $maxFileNameLength = null;
		
		private $beginPartUrl = null;
		
		private $mimeTypes = array();
		
		/**
		 * @return MediaFilesSplitter
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return MediaFilesSplitter
		 */
		public function setMaxFileNameLength($length)
		{
			$this->maxFileNameLength = $length;
			return $this;
		}
		
		public function getMaxFileNameLength()
		{
			return $this->maxFileNameLength;
		}
		
		/**
		 * @return MediaFilesSplitter
		 */
		public function setBeginPartUrl($url)
		{
			$this->beginPartUrl = $url;
			return $this;
		}
		
		public function getBeginPartUrl()
		{
			return $this->beginPartUrl;
		}
		
		/**
		 * @return MediaFilesSplitter
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
		
		public function splitFileNames($files)
		{
			$bufferSplitFiles = array();
			
			$splitFiles = array();
			
			foreach($files['includeFiles'] as $file)
			{
				if(
					in_array($file['content-type'], array_keys($this->getMimeTypes()))
					&& !in_array($file['id'], $files['dontSplitFiles'])
				) {
					if(!isset($bufferSplitFiles[$file['content-type']]))
						$bufferSplitFiles[$file['content-type']] = array();
					
					$bufferSplitFiles[$file['content-type']][] = $file['path'];
					
					$fileName = $this->compileFileName(
						$bufferSplitFiles[$file['content-type']]
					);
					
					$fileName = $this->beginPartUrl . '/' . $fileName . '.'
						. MimeContentTypes::getFileExtension($file['content-type']);
					
					if(strlen($fileName) > $this->getMaxFileNameLength())
					{
						$splitFiles[] = array(
							'path' => $fileName,
							'content-type' => $file['content-type']
						);
						
						$bufferSplitFiles[$file['content-type']] = array();
					}
				}
				else
				{
					$splitFiles[] = $file;
				}
			}
			
			foreach($bufferSplitFiles as $contentType => $files)
			{
				$fileName = $this->compileFileName($files);
				
				$fileName = $this->beginPartUrl . '/' . $fileName
					. '.' . MimeContentTypes::getFileExtension($contentType);
			
				$splitFiles[] = array(
					'path' => $fileName,
					'content-type' => $contentType
				);
			}
			
			return array('includeFiles' => $splitFiles);
		}
		
		private function compileFileName($filePathes)
		{
			return base64_encode(serialize($filePathes));
		}
	}
?>