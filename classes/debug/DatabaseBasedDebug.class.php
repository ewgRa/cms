<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseBasedDebug extends BaseDebug
	{
		/**
		 * @var DatabaseBasedDebugDA
		 */
		private $da = null;
		
		/**
		 * @return DatabaseBasedDebug
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return DatabaseBasedDebugDA
		 */
		public function da()
		{
			if(!$this->da)
				$this->da = DatabaseBasedDebugDA::create();
			
			return $this->da;
		}
		
		public function store()
		{
			foreach($this->getItems() as $item)
				$item->dropTrace();
			
			$itemId =
				$this->da()->insertItem(
					Session::me()->getId(),
					serialize($this->getItems())
				);
			
			return $itemId;
		}
	}
?>