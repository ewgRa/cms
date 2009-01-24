<?php
	/* $Id: ContentController.class.php 56 2008-08-17 17:31:53Z ewgraf $ */

	class ContentController extends Controller
	{
		private $units = null;
		
		/**
		 * @var ContentDA
		 */
		private $da	= null;
		
		public function da()
		{
			if(!$this->da)
				$this->da = ContentDA::create();

			return $this->da;
		}
		
		public function importSettings(HttpRequest $request, $settings)
		{
			$this->setUnits($settings['units']);

			if(Cache::me()->hasTicketParams('content'))
			{
				$localizer = $request->getAttached(AttachedAliases::LOCALIZER);
				
				$this->setCacheTicket(
					Cache::me()->createTicket('content')->
						setKey(
							$this->getUnits(),
							$localizer->getRequestLanguage(),
							$localizer->getSource()
						)
				);
			}
			
			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel(HttpRequest $request)
		{
			$localizer = $request->getAttached(AttachedAliases::LOCALIZER);
			
			$result = $this->da()->getUnitsContent(
				$this->getUnits(),
				$localizer->getRequestLanguage()->getId()
			);

			$replace = array(
				'pattern' => array('%baseUrl%'),
				'replace' => array($localizer->getBaseUrl())
			);

			if(defined('MEDIA_HOST'))
			{
				$replace['pattern'][] = '%MEDIA_HOST%';
				$replace['replace'][] = MEDIA_HOST;
			}

			foreach($result as &$contentRow)
				$contentRow['text'] = str_replace(
					$replace['pattern'],
					$replace['replace'],
					$contentRow['text']
				);
		
			return Model::create()->setData($result);
		}

		private function setUnits($units)
		{
			$this->units = $units;
			return $this;
		}
		
		private function getUnits()
		{
			return $this->units;
		}
		
	}
?>