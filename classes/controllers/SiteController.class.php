<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SiteController extends \ewgraFramework\ChainController
	{
		private $siteAlias = null;

		/**
		 * @return SiteController
		 */
		public static function create(\ewgraFramework\ChainController $controller = null)
		{
			return new self($controller);
		}

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
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			\ewgraFramework\Assert::isNotNull($this->getSiteAlias());

			$request->setAttachedVar(
				AttachedAliases::SITE,
				Site::da()->getByAlias($this->getSiteAlias())
			);

			return parent::handleRequest($request, $mav);
		}
	}
?>