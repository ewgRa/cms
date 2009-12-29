<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NormalizeRequestController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$this->normalizeRequest($request);
			
			return parent::handleRequest($request, $mav);
		}
		
		/**
		 * @return NormalizeRequestController
		 */
		private function normalizeRequest(HttpRequest $request)
		{
			if(function_exists('set_magic_quotes_runtime'))
				set_magic_quotes_runtime(0);
			
			if(function_exists( 'get_magic_quotes_gpc') && get_magic_quotes_gpc())
			{
				$arrays = array('Get', 'Post', 'Cookie');
				
				foreach ($arrays as $arrayName) {
					$request->{'set'.$arrayName}(
						$this->strips($request->{'get'.$arrayName}())
					);
				}
			}
			
			return $this;
		}

		private function strips($el)
		{
			if (is_array($el)) {
				foreach($el as &$v)
					$v = $this->strips($v);
			} else
				$el = stripslashes($el);
				
			return $el;
			
		}
	}
?>