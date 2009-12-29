<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class Localizer implements LocalizerInterface
	{
		// FIXME: languageSource extends Enumeration
		const SOURCE_LANGUAGE_DEFAULT 		 = 1;
		const SOURCE_LANGUAGE_COOKIE  		 = 2;
		const SOURCE_LANGUAGE_URL 	  		 = 3;
		const SOURCE_LANGUAGE_URL_AND_COOKIE = 4;
		
		const DETERMINANT_PATH_BASED = 5;
		const DETERMINANT_HOST_BASED = 6;
		
		/**
		 * @var Language
		 */
		private $requestLanguage = null;
		
		private $languages 		 = null;
		
		/**
		 * @var Language
		 */
		private $cookieLanguage  = null;
		
		// FIXME: languageSource extends Enumeration
		private $source 		 = null;
		
		// FIXME: LocalizerType extends Enumeration
		protected $type = null;

		abstract protected function getLanguageAbbr(HttpUrl $url);
				
		public function getType()
		{
			return $this->type;
		}
		
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

		public function getSource()
		{
			return $this->source;
		}
		
		/**
		 * @return Localizer
		 */
		public function setSource($source)
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
				$this->setSource(self::SOURCE_LANGUAGE_COOKIE);
			}

			if ($this->getLanguages()) {
				$probableLanguageAbbr = $this->getLanguageAbbr($url);

				foreach ($this->getLanguages() as $language) {
					if ($probableLanguageAbbr == $language->getAbbr()) {
						$this->setRequestLanguage($language);

						$this->setSource(
							$this->getSource() == self::SOURCE_LANGUAGE_COOKIE
								? self::SOURCE_LANGUAGE_URL_AND_COOKIE
								: self::SOURCE_LANGUAGE_URL
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
				$this->setSource(self::SOURCE_LANGUAGE_DEFAULT);
			} else {
				throw DefaultException::create(
					'Known nothing about default language '
					.'"'.$languageAbbr.'"'
				);
			}
			
			return $this;
		}

		public function isLanguageInUrl()
		{
			return
				in_array(
					$this->getSource(),
					array(
						Localizer::SOURCE_LANGUAGE_URL,
						Localizer::SOURCE_LANGUAGE_URL_AND_COOKIE
					)
				);
		}
	}
?>