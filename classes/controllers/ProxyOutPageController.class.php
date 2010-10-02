<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ProxyOutPageController extends \ewgraFramework\ChainController
	{
		/**
		 * @var \ewgraFramework\ModelAndView
		 */
		private $mav = null;

		/**
		 * @var PageController
		 */
		private $pageController = null;
		
		public function setMav(\ewgraFramework\ModelAndView $mav)
		{
			$this->mav = $mav;
			return $this;	
		}
		
		public function setPageController(PageController $pageController)
		{
			$this->pageController = $pageController;
			return $this;	
		}
		
		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$this->mav->getModel()->append(
				array(
					'data' => $mav->render(),
					'section' => $this->pageController->getSection(),
					'position' => $this->pageController->getPosition() 
				)
			);
			
			return parent::handleRequest($request, $this->mav);
		}
	}
?>