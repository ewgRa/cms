<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageControllersDispatcher extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$page = $request->getAttachedVar(AttachedAliases::PAGE);
			
			$this->insertControllers(
				PageController::da()->getByPage($page),
				$mav
			);
					
			return parent::handleRequest($request, $mav);
		}

		/**
		 * @return PageControllersDispatcher
		 */
		private function insertControllers(
			array $pageControllers,
			ModelAndView $mav
		) {
			foreach ($pageControllers as $pageController) {
				$controller = $pageController->getController();
				
				$controllerName = $controller->getName();
				
				$proxyOut = new ProxyOutPageController($this->getInner());
				$proxyOut->setMav($mav);
				$proxyOut->setPageController($pageController);
				
				$controllerInstance = new $controllerName($proxyOut);
				
				$proxyIn = new ProxyInPageController($controllerInstance);
				$proxyMav = ModelAndView::create();
				$proxyIn->setMav($proxyMav);

				$this->setInner($proxyIn);

				$settings = $controller->getSettings();
				
				if ($pageController->getSettings()) {
					if ($settings) {
						$settings = array_merge(
							$settings,
							$pageController->getSettings()
						);
					} else
						$settings = $pageController->getSettings();
				}

				// FIXME XXX: shit 
				if (method_exists($controllerInstance, 'importSettings'))
					$controllerInstance->importSettings($settings);

				if ($pageController->getViewFileId()) {
					$proxyMav->setView(
						$pageController->getViewFile()->createView()
					);
				} else {
					$proxyMav->setView(NullTransformView::create());
				}
			}
			
			return $this;
		}
	}
?>