<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseBasedDebug extends BaseDebug
	{
		/**
		 * @return DatabaseBasedDebug
		 */
		public static function create()
		{
			return new self;
		}
		
		public function store()
		{
			foreach($this->getItems() as $item) {
				$item->dropTrace();
			}
			
			$itemId =
				DebugData::da()->insert(
					DebugData::create()->
						setSession(Session::me()->getId())->
						setData($this->getItems())
				);
			
			return $itemId;
		}
	}
?>