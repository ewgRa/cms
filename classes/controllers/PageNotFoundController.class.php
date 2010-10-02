<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageNotFoundController extends \ewgraFramework\ChainController
	{
		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$request->
				getAttachedVar(AttachedAliases::PAGE_HEADER)->
				add($request->getServerVar('SERVER_PROTOCOL').' 404 Not Found');
			
			return parent::handleRequest($request, $mav);
		}
	}
?>