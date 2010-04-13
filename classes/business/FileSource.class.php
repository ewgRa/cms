<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class FileSource extends AutoFileSource
	{
		/**
		 * @return FileSource
		 */
		public static function create()
		{
			return new self;
		}
		
		public function getPath()
		{
			Assert::isTrue(defined($this->getAlias()));
			return constant($this->getAlias());
		}
	}
?>