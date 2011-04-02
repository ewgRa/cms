<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface LocalizerInterface
	{
		public static function create();

		/**
		 * @return Localizer
		 */
		public function selectDefaultLanguage($languageAbbr);

		/**
		 * @return Localizer
		 */
		public function setCookieLanguage(Language $language);

		/**
		 * @return Localizer
		 */
		public function defineLanguage(\ewgraFramework\HttpUrl $url);

		/**
		 * @return Language
		 */
		public function getRequestLanguage();

		/**
		 * @return \ewgraFramework\HttpUrl
		 */
		public function removeLanguageFromUrl(\ewgraFramework\HttpUrl $url);
	}
?>