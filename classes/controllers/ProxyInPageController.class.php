<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ProxyInPageController extends ChainController
	{
		/**
		 * @var ModelAndView
		 */
		private $mav = null;

		public function setMav(ModelAndView $mav)
		{
			$this->mav = $mav;
			return $this;	
		}
		
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			return parent::handleRequest($request, $this->mav);
		}
	}
?>