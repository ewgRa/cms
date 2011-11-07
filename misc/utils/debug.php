<?php
	namespace ewgraCms\debugScript;

	function run(\ewgraFramework\HttpRequest $request)
	{
		$request->
			setPost($_POST)->
			setGet($_GET)->
			setCookie($_COOKIE)->
			setServer($_SERVER);

		$request->setAttachedVar(
			\ewgraCms\AttachedAliases::PAGE_HEADER,
			\ewgraCms\PageHeader::create()
		);

		$databasePool = \ewgraFramework\Database::me()->getPool(PROJECT);

		\ewgraFramework\Singleton::dropInstance('ewgraCms\Debug');

		\ewgraFramework\Session::me()->relativeStart();

		if (!\ewgraFramework\Session::me()->isStarted() && isset($_GET['startSession'])) {
			\ewgraFramework\Session::me()->start();

			if (!\ewgraFramework\Session::me()->has('enableDebug'))
				\ewgraFramework\Session::me()->set('enableDebug', true);

			if (!isset($_COOKIE['enableDebug']))
				\ewgraFramework\CookieManager::me()->set('enableDebug', true);
		}

		if(!\ewgraFramework\Session::me()->isStarted())
			die('no session started');

		$controller =
			new \ewgraCmsModules\UserController(
				\ewgraCmsModules\Auth401Controller::create(
					\ewgraCmsModules\UserRight401Controller::create()->
					setRequiredRightAliases(array('root'))
				)->
				setRequestAction('login')
			);

		$modelAndView = $controller->handleRequest(
			$request,
			\ewgraFramework\ModelAndView::create()
		);

		return $modelAndView->render();

		// FIXME: what next?
		die;
		$pointer = isset($_GET['pointer']) ? $_GET['pointer'] : 0;

		$dbQuery = "SELECT SQL_CALC_FOUND_ROWS * FROM "
			.$databasePool->getDialect()->escapeTable('DebugData')
			." WHERE session = ? ORDER BY id DESC LIMIT "
			.(int)$pointer.", 1";

		$dbResult = $databasePool->query(
			DatabaseQuery::create()->
			setQuery($dbQuery)->
			setValues(array(Session::me()->getId()))
		);

		$dbRow = $dbResult->fetchRow();

		$dbResult =
			$databasePool->query(
				DatabaseQuery::create()->setQuery('SELECT FOUND_ROWS() as cnt')
			);

		$dbRowCount = $dbResult->fetchRow();

		$debugItems = unserialize($dbRow['data']);
		$model = Model::create();

		if ($debugItems) {
			var_dump($debugItems);die;
			foreach ($debugItems as $item) {
				$data = $item->getData();

				switch ($item->getType()) {
					case CmsDebugItem::PAGE:
						$data = array(
							'path' => $data->getPath(),
							'id' => $data->getId(),
							'layoutId' => $data->getLayoutId()
						);

					break;

					case DebugItem::CACHE:
						$data = array(
							'prefix' => $data->getPrefix(),
							'key' => $data->getCacheInstance()->compileKey($data),
							'cacheInstance' => get_class($data->getCacheInstance()),
							'expiredTime' =>
								$data->getExpiredTime()
									? date('Y-m-d h:i:s', $data->getExpiredTime())
									: null,
							'lifeTime' =>
								$data->getLifeTime()
									? date('Y-m-d h:i:s', $data->getLifeTime())
									: null,
							'status' => $data->isExpired() ? 'expired' : 'actual'
						);
					break;
				}

				$model->append(
					array(
						'type' 		=> $item->getType(),
						'data' 		=> $data,
						'startTime' => $item->getStartTime(),
						'endTime' 	=> $item->getEndTime()
					)
				);
			}
		}

		$model->set('countItems', $dbRowCount['cnt']);

		$model->set('pointer', $pointer);

		$layout =
			File::create()->setPath(CMS_DIR . '/view/xsl/backend/debug.xsl');

		$view = XsltView::create()->loadLayout($layout);

		if ($charset = Config::me()->getOption('charset'))
			$view->setCharset($charset);

		$modelAndView = ModelAndView::create()->
			setModel($model)->
			setView($view);

		return $modelAndView->render();
	}
?>