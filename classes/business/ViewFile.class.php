<?php
	namespace ewgraCms;

	/**
	 * Generated by meta builder, you can edit this class
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	final class ViewFile extends AutoViewFile
	{
		/**
		 * @return ViewFile
		 */
		public static function create()
		{
			return new self;
		}

		public function createView()
		{
			$result = null;

			$path = $this->getPath();

			if ($this->getSource())
				$path = $this->getSource()->getPath().DIRECTORY_SEPARATOR.$path;

			$layout = \ewgraFramework\File::create()->setPath($path);

			switch ($this->getContentType()->getId()) {
				case \ewgraFramework\ContentType::TEXT_XSLT:
					$result = \ewgraFramework\XsltView::create();

					if ($charset = Config::me()->getOption('charset'))
						$result->setCharset($charset);

					$result->loadLayout($layout);

					break;
				case \ewgraFramework\ContentType::APPLICATION_PHP:
					$result = \ewgraFramework\PhpView::create()->loadLayout($layout);

					break;
				default:
					\ewgraFramework\Assert::isUnreachable();
			}

			return $result;
		}
	}
?>