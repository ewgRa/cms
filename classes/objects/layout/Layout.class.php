<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Layout
	{
		private $id 		= null;

		private $viewFileId	= null;

		/**
		 * @var ViewFile
		 */
		private $viewFile	= null;
		
		/**
		 * @return Layout
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return LayoutDA
		 */
		public static function da()
		{
			return LayoutDA::me();
		}
		
		/**
		 * @return Layout
		 */
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		/**
		 * @return ViewFile
		 */
		public function setViewFile(ViewFile $viewFile)
		{
			$this->viewFile		= $viewFile;
			$this->viewFileId	= $viewFile->getId();
			return $this;
		}
		
		/**
		 * @return ViewFile
		 */
		public function getViewFile()
		{
			if (!$this->viewFile && $this->getViewFileId()) {
				$this->setViewFile(
					ViewFile::da()->getById($this->getViewFileId())
				);
			}
			
			return $this->viewFile;
		}
		
		/**
		 * @return Layout
		 */
		public function setViewFileId($viewFileId)
		{
			$this->viewFile = null;
			$this->viewFileId = $viewFileId;
			
			return $this;
		}
		
		public function getViewFileId()
		{
			return $this->viewFileId;
		}
	}
?>