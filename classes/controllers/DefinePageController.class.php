<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DefinePageController extends \ewgraFramework\ChainController
	{
		private $observerManager = null;

		/**
		 * @return DefinePageControllerObserverManager
		 */
		public function getObserverManager()
		{
			return $this->observerManager;
		}

		/**
		 * @return DefinePageController
		 */
		public function __construct(\ewgraFramework\ChainController $controller = null)
		{
			$this->observerManager =
				DefinePageControllerObserverManager::create($this);

			return parent::__construct($controller);
		}

		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$localizer = $request->getAttachedVar(AttachedAliases::LOCALIZER);

			$clearPath =
				$localizer->
					removeLanguageFromUrl($request->getUrl())->
					getPath();

			$page = PagePathMapper::create()->loadMap()->getPageByPath($clearPath);

			if (!$page)
				throw PageNotFoundException::create();

			$this->getObserverManager()->notifyPageDefined($page);

			$user =
				$request->hasAttachedVar(AttachedAliases::USER)
					? $request->getAttachedVar(AttachedAliases::USER)
					: null;

			$request->setAttachedVar(AttachedAliases::PAGE, $page);

			$mav->setView($page->getLayout()->getViewFile()->createView());

			$mav->getModel()->set('layoutSettings', $page->getLayoutSettings());

			$baseUrl = \ewgraFramework\HttpUrl::create()->setPath('');

			if (
				$localizer->getSource()->isLanguageInUrl()
				&& $localizer->getSource()->getId()
					!= LocalizerLanguageSource::URL_AND_COOKIE
			) {
				$baseUrl->setPath(
					'/'.$localizer->getRequestLanguage()->getAbbr()
					.$baseUrl->getPath()
				);
			}

			$request->setAttachedVar(AttachedAliases::BASE_URL, $baseUrl);

			if (!$request->hasAttachedVar(AttachedAliases::PAGE_HEADER)) {
				$request->setAttachedVar(
					AttachedAliases::PAGE_HEADER,
					PageHeader::create()
				);
			}

			return parent::handleRequest($request, $mav);
		}
	}
?>