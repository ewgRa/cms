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
			Assert::isNotNull($this->id);
			return $this->id;
		}
		
		/**
		 * @return Layout
		 */
		public function setViewFileId($viewFileId)
		{
			$this->viewFileId = $viewFileId;
			
			return $this;
		}
		
		public function getViewFileId()
		{
			return $this->viewFileId;
		}

		/**
		 * @return ViewFile
		 */
		public function getViewFile()
		{
			return ViewFile::da()->getById($this->getViewFileId());
		}
	}
?>