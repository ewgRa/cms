<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface CacherInterface
	{
		public function dropCache();

		public function getLinkedCachers();

		public function addLinkedCacher(CacherInterface $cacher);

		public function hasLinkedCacher(CacherInterface $cacher);
	}
?>