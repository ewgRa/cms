<?php
	namespace ewgraCms;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoLayout
	{
		private $id = null;

		private $viewFileId = null;

		/**
		 * @var ViewFile
		 */
		private $viewFile = null;

		/**
		 * @return LayoutDA
		 */
		public static function da()
		{
			return LayoutDA::me();
		}

		/**
		 * @return AutoLayout
		 */
		public function setId($id)
		{
			$this->id = $id;

			return $this;
		}

		public function getId()
		{
			\ewgraFramework\Assert::isNotNull($this->id);
			return $this->id;
		}

		/**
		 * @return AutoLayout
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

		/**
		 * @return AutoLayout
		 */
		public function setViewFile(ViewFile $viewFile)
		{
			$this->viewFileId = $viewFile->getId();
			$this->viewFile = $viewFile;

			return $this;
		}

		/**
		 * @return ViewFile
		 */
		public function getViewFile()
		{
			if (!$this->viewFile && $this->getViewFileId())
				$this->viewFile = ViewFile::da()->getById($this->getViewFileId());

			return $this->viewFile;
		}
	}
?>