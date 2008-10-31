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
		
		public function importSettings($settings)
		{
			$this->setUnits($settings['units']);

			if(Cache::me()->hasTicketParams('content'))
			{
				$this->setCacheTicket(
					Cache::me()->createTicket('content')->
						setKey(
							$this->getUnits(),
							Localizer::me()->getRequestLanguage(),
							Localizer::me()->getSource()
						)
				);
			}
			
			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$result = $this->da()->getUnitsContent(
				$this->getUnits(),
				Localizer::me()->getRequestLanguage()->getId()
			);

			$replace = array(
				'pattern' => array('%localizerPath%'),
				'replace' => array(UrlHelper::me()->getLocalizerPath())
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