<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageNotFoundController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$request->
				getAttachedVar(AttachedAliases::PAGE_HEADER)->
				add($request->getServerVar('SERVER_PROTOCOL').' 404 Not Found');
			
			return parent::handleRequest($request, $mav);
		}
	}
?>