<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Language
	{
		private $abbr = null;
		private $id   = null;

		/**
		 * @return LanguageDA
		 */
		public static function da()
		{
			return LanguageDA::me();
		}
		
		/**
		 * @return Language
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return Language
		 */
		public function setId($id)
		{
			$this->id = (int)$id;
			return $this;
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		/**
		 * @return Language
		 */
		public function setAbbr($abbr)
		{
			$this->abbr = $abbr;
			return $this;
		}
		
		public function getAbbr()
		{
			return $this->abbr;
		}
	}
?>