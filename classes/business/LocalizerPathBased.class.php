<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class LocalizerPathBased extends Localizer
	{
		/**
		 * @return LocalizerPathBased
		 */
		public static function create()
		{
			return new self;
		}

		protected function getLanguageAbbr(\ewgraFramework\HttpUrl $url)
		{
			$result = null;

			$parts = explode('/', $url->getPath());

			if (count($parts) > 2)
				$result = $parts[1];

			return $result;
		}

		/**
		 * @return \ewgraFramework\HttpUrl
		 */
		public function removeLanguageFromUrl(\ewgraFramework\HttpUrl $url)
		{
			return
				$this->getSource()->isLanguageInUrl()
					? $this->cutLanguageAbbr($url)
					: $url;
		}

		/**
		 * @return \ewgraFramework\HttpUrl
		 */
		private function cutLanguageAbbr(\ewgraFramework\HttpUrl $url)
		{
			if (
				$this->getLanguageAbbr($url)
					== $this->getRequestLanguage()->getAbbr()
			) {
				$url->setPath(
					substr(
						$url->getPath(),
						strlen($this->getRequestLanguage()->getAbbr())+1
					)
				);
			}

			return $url;
		}
	}
?>