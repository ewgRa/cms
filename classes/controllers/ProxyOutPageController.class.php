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
		
		private $modelKey = null;

		/**
		 * @var PageController
		 */
		private $pageController = null;
		
		/**
		 * @return ProxyOutPageController
		 */
		public function setModelKey($modelKey)
		{
			$this->modelKey = $modelKey;
			return $this;
		}
		
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
			$modelData =
				array(
					'data' => $mav->render(),
					'section' => $this->pageController->getSection(),
					'position' => $this->pageController->getPosition()
				);
			
			if ($this->modelKey) {
				$data = $this->mav->getModel()->getData();
				
				if (!isset($data[$this->modelKey]))
					$data[$this->modelKey] = array();
				
				$data[$this->modelKey][] = $modelData;
				
				$this->mav->getModel()->setData($data);
			} else
				$this->mav->getModel()->append($modelData);
			
			
			return parent::handleRequest($request, $this->mav);
		}
	}
?>