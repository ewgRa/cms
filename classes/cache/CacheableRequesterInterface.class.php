<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface CacheableRequesterInterface
	{
		public function getPoolAlias();

		public function getByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery);

		public function getListByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery);
	}
?>