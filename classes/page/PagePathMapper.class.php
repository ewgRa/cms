<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PagePathMapper
	{
		const NON_PREG	= 'no';
		const PREG		= 'yes';

		private $map = null;

		/**
		 * @return PagePathMapper
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return PagePathMapper
		 */
		public function loadMap()
		{
			$this->map = array(
				self::NON_PREG => array(),
				self::PREG => array()
			);
			
			foreach(Page::da()->getList() as $page)
			{
				$this->map[$page->getPreg()][$page->getId()] = $page;
			}
			
			return $this;
		}
		
		public function getPageByPath($path)
		{
			$result = null;

			foreach ($this->map[self::NON_PREG] as $page) {
				if ($page->getPath() == $path) {
					$result = $page;
					break;
				}
			}
			
			if (!$result) {
				foreach ($this->map[self::PREG] as $page) {
					if (preg_match('@' . $page->getPath() . '@', $path)) {
						$result = $page;
						break;
					}
				}
			}
			
			return $result;
		}
	}
?>