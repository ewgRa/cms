<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Config extends Singleton
	{
		private $options = null;
		
		/**
		 * @return Config
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		/**
		 * @return Config
		 */
		public function setOption($alias, $value)
		{
			$this->options[$alias] = $value;
			return $this;
		}

		public function getOption($alias)
		{
			$result = null;

			if (isset($this->options[$alias]))
				$result = $this->options[$alias];
			
			return $result;
		}

		public function replaceVariables($variable)
		{
			if (is_array($variable)) {
				foreach ($variable as &$var)
					$var = $this->{__FUNCTION__}($var);
			} else {
				$matches = null;
				preg_match_all('/%(.*?)%/', $variable, $matches);
				
				foreach (array_unique($matches[1]) as $match) {
					$matchVarValue = VariableUtils::getValueByString($match);
					
					if ($matchVarValue) {
						$variable = str_replace(
							"%" . $match . "%",
							$matchVarValue,
							$variable
						);
					}
				}
			}
			
			return $variable;
		}
	}
?>