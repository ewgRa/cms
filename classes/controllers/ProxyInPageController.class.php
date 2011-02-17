<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ProxyInPageController extends \ewgraFramework\ChainController
	{
		/**
		 * @var \ewgraFramework\ModelAndView
		 */
		private $mav = null;

		public function setMav(\ewgraFramework\ModelAndView $mav)
		{
			$this->mav = $mav;
			return $this;
		}
		
		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			return parent::handleRequest($request, $this->mav);
		}
	}
?>