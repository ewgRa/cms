<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SessionController extends \ewgraFramework\ChainController
	{
		private $checkSecurityHash = true;

		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			\ewgraFramework\Session::me()->relativeStart();

			if (
				$this->checkSecurityHash
				&& \ewgraFramework\Session::me()->isStarted()
			) {
				if (!\ewgraFramework\Session::me()->has('securityHash')) {
					\ewgraFramework\Session::me()->set(
						'securityHash',
						$this->compileSecurityHash($request)
					);
				} else if (
					\ewgraFramework\Session::me()->get('securityHash')
						!= $this->compileSecurityHash($request)
				) {
					\ewgraFramework\Session::me()->startAsNew();

					\ewgraFramework\LogManager::me()->store(
						'Security hash not equal! "'
						.\ewgraFramework\Session::me()->get('securityHash')
						.'" not equal to "'.$this->compileSecurityHash($request).'"'
					);
				}
			}

			return parent::handleRequest($request, $mav);
		}

		public function setCheckSecurityHash($checkSecurityHash = true)
		{
			$this->checkSecurityHash = ($checkSecurityHash === true);
			return $this;
		}

		private function compileSecurityHash(\ewgraFramework\HttpRequest $request)
		{
			return $request->getRemoteIp().'|'.$request->getUserAgent();
		}
	}
?>