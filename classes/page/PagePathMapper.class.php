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
		 * @return PagePathMapperDA
		 */
		public function da()
		{
			return PagePathMapperDA::me();
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
			
			foreach($this->da()->getMap() as $map)
			{
				$preg = $map['preg'] == self::NON_PREG ? self::NON_PREG : self::PREG;
				$this->map[$preg][$map['id']] = $map['path'];
			}
			
			$this->map[self::NON_PREG] = array_flip($this->map[self::NON_PREG]);
			
			return $this;
		}
		
		public function getPageId($path)
		{
			$result = null;

			if(isset($this->map[self::NON_PREG][$path]))
				$result = $this->map[self::NON_PREG][$path];
			else
			{
				foreach($this->map[self::PREG] as $pageId => $pagePattern)
				{
					if(preg_match('@' . $pagePattern . '@', $path))
					{
						$result = $pageId;
						break;
					}
				}
			}
			
			return $result;
		}
	}
?>