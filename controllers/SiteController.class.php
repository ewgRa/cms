<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SiteController extends ChainController
	{
		private $siteAlias = null;

		/**
		 * @return SiteController
		 */
		public function setSiteAlias($alias)
		{
			$this->siteAlias = $alias;
			return $this;
		}

		public function getSiteAlias()
		{
			return $this->siteAlias;
		}
		
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			Assert::isNotNull($this->getSiteAlias());
			
			$request->setAttachedVar(
				AttachedAliases::SITE,
				Site::da()->getByAlias($this->getSiteAlias())
			);
			
			return parent::handleRequest($request, $mav);
		}
	}
?>