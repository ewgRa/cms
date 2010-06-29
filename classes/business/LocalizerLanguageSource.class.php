<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class LocalizerLanguageSource extends Enumeration
	{
		const DEFAULT_SOURCE = 1;
		const COOKIE  		 = 2;
		const URL 	  		 = 3;
		const URL_AND_COOKIE = 4;
				
		protected $names = array(
			self::DEFAULT_SOURCE	=> 'default',
			self::COOKIE		 	=> 'cookie',
			self::URL				=> 'url',
			self::URL_AND_COOKIE	=> 'urlAndCookie'
		);
		
		/**
		 * @return LocalizerLanguageSource
		 */
		public static function create($id)
		{
			return new self($id);
		}
		
		/**
		 * @return LocalizerLanguageSource
		 */
		public static function any()
		{
			return self::defaultSource();
		}
		
		/**
		 * @return LocalizerLanguageSource
		 */
		public static function defaultSource()
		{
			return self::create(self::DEFAULT_SOURCE);
		}

		/**
		 * @return LocalizerLanguageSource
		 */
		public static function cookie()
		{
			return self::create(self::COOKIE);
		}

		/**
		 * @return LocalizerLanguageSource
		 */
		public static function url()
		{
			return self::create(self::URL);
		}

		/**
		 * @return LocalizerLanguageSource
		 */
		public static function urlAndCookie()
		{
			return self::create(self::URL_AND_COOKIE);
		}

		public function isLanguageInUrl()
		{
			return
				in_array(
					$this->getId(),
					array(
						self::URL,
						self::URL_AND_COOKIE
					)
				);
		}
	}
?>