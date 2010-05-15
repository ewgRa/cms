<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class Localizer implements LocalizerInterface
	{
		/**
		 * @var Language
		 */
		private $requestLanguage = null;
		
		private $languages 		 = null;
		
		/**
		 * @var Language
		 */
		private $cookieLanguage  = null;
		
		/**
		 * @return LocalizerLanguageSource
		 */
		private $source = null;
		
		abstract protected function getLanguageAbbr(HttpUrl $url);
				
		/**
		 * @return Language
		 */
		public function getRequestLanguage()
		{
			return $this->requestLanguage;
		}
		
		/**
		 * @return Localizer
		 */
		public function setRequestLanguage(Language $language)
		{
			$this->requestLanguage = $language;
			return $this;
		}

		/**
		 * @return LocalizerLanguageSource
		 */
		public function getSource()
		{
			return $this->source;
		}
		
		/**
		 * @return Localizer
		 */
		public function setSource(LocalizerLanguageSource $source)
		{
			$this->source = $source;
			return $this;
		}
		
		public function getLanguages()
		{
			return $this->languages;
		}
		
		/**
		 * @return Localizer
		 */
		public function setLanguages(array $languages)
		{
			$this->languages = $languages;
			return $this;
		}
		
		/**
		 * @return Localizer
		 */
		public function setCookieLanguage(Language $language)
		{
			$this->cookieLanguage = $language;
			return $this;
		}
		
		/**
		 * @return Localizer
		 */
		public function defineLanguage(HttpUrl $url)
		{
			if ($this->cookieLanguage) {
				$this->setRequestLanguage($this->cookieLanguage);
				$this->setSource(LocalizerLanguageSource::cookie());
			}

			if ($this->getLanguages()) {
				$probableLanguageAbbr = $this->getLanguageAbbr($url);

				foreach ($this->getLanguages() as $language) {
					if ($probableLanguageAbbr == $language->getAbbr()) {
						$this->setRequestLanguage($language);

						$this->setSource(
							$this->getSource()->getId()
								== LocalizerLanguageSource::COOKIE
								? LocalizerLanguageSource::urlAndCookie()
								: LocalizerLanguageSource::url()
						);
					}
				}
			}

			return $this;
		}

		/**
		 * @return Localizer
		 */
		public function selectDefaultLanguage($languageAbbr)
		{
			$language = null;
			
			if ($this->getLanguages()) {
				foreach ($this->getLanguages() as $oneLanguage) {
					if ($oneLanguage->getAbbr() == $languageAbbr) {
						$language = $oneLanguage;
						break;
					}
				}
			}

			if ($language) {
				$this->setRequestLanguage($language);
				$this->setSource(LocalizerLanguageSource::defaultSource());
			} else {
				throw MissingArgumentException::create(
					'Known nothing about default language '
					.'"'.$languageAbbr.'"'
				);
			}
			
			return $this;
		}
	}
?>