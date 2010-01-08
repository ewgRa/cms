<?php
	try {
		$startTime 	= microtime(true);
		
		classesAutoloaderInit();
		
		$output	= ProjectIndex::me()->run();
		$echo	= ob_get_clean();
	
		if ($echo)
			ProjectIndex::me()->catchEcho($echo);
			
		echo ProjectIndex::me()->getOutput($output, $echo);

		if(Singleton::hasInstance('Debug') && Debug::me()->isEnabled()) {
			$debugItem =
				CmsDebugItem::create()->
				setType(CmsDebugItem::ENGINE_ECHO)->
				setData($echo)->
				setStartTime($startTime)->
				setEndTime(microtime(true));
			
			Debug::me()->addItem($debugItem);
			
			if (Session::me()->isStarted())
				Debug::me()->store();
		}
		
	} catch(Exception $e) {
		error_log($e);

		ProjectIndex::me()->
			sendUnavailableHeaders()->
			catchException($e);

		echo
			!ini_get('display_errors')
				? file_get_contents(SITE_DIR . '/view/html/haserror.html')
				: (
					$e instanceof DefaultException
						? $e->toHtmlString()
						: '<pre>'.$e.'</pre>'
				);
		
		die;
	}
	
	function run(HttpRequest $request)
	{
		$request->
			setPost($_POST)->
			setGet($_GET)->
			setCookie($_COOKIE)->
			setServer($_SERVER);
				
		$databasePool = Database::me()->getPool('darinaprint');
		
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
		
		$controller = new UserController();
		$controller->handleRequest($request, ModelAndView::create());
		
		$user =
			$request->hasAttachedVar(AttachedAliases::USER)
				? $request->getAttachedVar(AttachedAliases::USER)
				: null;
		
		if ($request->hasServerVar('PHP_AUTH_USER') && !$user) {
			$loginModule = new LoginModule();
			
			$loginModule->
				setRequest($request)->
				importSettings(array('source' => 'server'))->
				getModel();
		}
		
		$controller = new Auth401Controller();
		$controller->handleRequest($request, ModelAndView::create());
		
		$pointer = isset($_GET['pointer']) ? $_GET['pointer'] : 0;

		$dbQuery = "SELECT SQL_CALC_FOUND_ROWS * FROM "
			.$databasePool->getTable('DebugData')
			." WHERE session = ? ORDER BY id DESC LIMIT "
			.(int)$pointer.", 1";

		$dbResult = $databasePool->query(
			$dbQuery,
			array(Session::me()->getId())
		);
		
		$dbRow = $dbResult->fetchArray();

		$dbResult = $databasePool->query('SELECT FOUND_ROWS() as cnt');
		$dbRowCount = $dbResult->fetchArray();
		
		$debugItems = unserialize($dbRow['data']);
		$model = Model::create();
		
		if ($debugItems) {
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
							'actualTime' =>
								$data->getActualTime()
									? date('Y-m-d h:i:s', $data->getActualTime())
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