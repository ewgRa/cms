<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageHeader
	{
		private $headers = array();

		/**
		 * @return PageHeader
		 */
		public static function create()
		{
			return new self;
		}

		public function getHeaders()
		{
			return $this->headers;
		}

		/**
		 * @return PageHeader
		 */
		public function add($alias, $value = null)
		{
			$this->headers[$alias] = $value;
			return $this;
		}

		/**
		 * @return PageHeader
		 */
		public function output()
		{
			foreach ($this->getHeaders() as $alias => $value)
				header($alias.($value !== null ? ': '.$value : null));

			return $this;
		}

		public function addRedirect(\ewgraFramework\HttpUrl $url) {
			$this->add('Location', (string)$url);
			return $this;
		}
	}
?>