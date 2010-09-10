<?php
	classesAutoloaderInit();

	$request	= initRequest();
	$output		= run($request);

	$echo	= ob_get_clean();

	if ($echo)
		ProjectIndex::me()->catchEcho($echo);
		
	if ($request->hasAttachedVar(AttachedAliases::PAGE_HEADER))
		$request->getAttachedVar(AttachedAliases::PAGE_HEADER)->output();
		
	echo ProjectIndex::me()->getOutput($output, $echo);
	
	function run(HttpRequest $request)
	{
		$request->
			setPost($_POST)->
			setGet($_GET)->
			setCookie($_COOKIE)->
			setServer($_SERVER);
				
		$request->setAttachedVar(
			AttachedAliases::PAGE_HEADER,
			PageHeader::create()
		);

		$databasePool = Database::me()->getPool(PROJECT);
		
		Singleton::dropInstance('Debug');
		
		Session::me()->relativeStart();
		
		if (!Session::me()->isStarted() && isset($_GET['startSession'])) {
			Session::me()->start();
			
			if (!Session::me()->has('enableDebug')) {
				Session::me()->
					set('enableDebug', true)->
					save();
			}
			
			if (!isset($_COOKIE['enableDebug']))
				CookieManager::me()->setCookie('enableDebug', true);
		}
		
		if(!Session::me()->isStarted())
			die('no session started');
		
		$controller =
			new UserController( 
				Auth401Controller::create(
					UserRight401Controller::create()->
					setRequiredRightAliases(array('root'))
				)->
				setRequestAction('login')
			);
		
		$modelAndView = $controller->handleRequest(
			$request,
			ModelAndView::create()
		);
		
		return $modelAndView->render();

		die;
		$pointer = isset($_GET['pointer']) ? $_GET['pointer'] : 0;

		$dbQuery = "SELECT SQL_CALC_FOUND_ROWS * FROM "
			.$databasePool->getDialect()->quoteTable('DebugData')
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
			// FIXME: Render
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
		
		$projectConfig = Config::me()->getOption('project');
		
		if (isset($projectConfig['charset']))
			$view->setCharset($projectConfig['charset']);
		
		$modelAndView = ModelAndView::create()->
			setModel($model)->
			setView($view);
			
		return $modelAndView->render();
	}
?>