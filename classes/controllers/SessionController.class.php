<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SessionController extends \ewgraFramework\ChainController
	{
		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			\ewgraFramework\Session::me()->relativeStart();

			if (\ewgraFramework\Session::me()->isStarted()) {
				if (!\ewgraFramework\Session::me()->has('securityHash')) {
					\ewgraFramework\Session::me()->set(
						'securityHash',
						$this->compileSecurityHash($request)
					);
				} else if (
					\ewgraFramework\Session::me()->get('securityHash')
						!= $this->compileSecurityHash($request)
				)
					\ewgraFramework\Session::me()->destroy();
			}

			return parent::handleRequest($request, $mav);
		}

		private function compileSecurityHash(\ewgraFramework\HttpRequest $request)
		{
			return sha1($request->getRemoteIp().'-'.$request->getUserAgent());
		}
	}
?>