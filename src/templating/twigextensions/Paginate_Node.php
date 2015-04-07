<?php
/**
 * @link http://buildwithcraft.com/
 * @copyright Copyright (c) 2015 Pixel & Tonic, Inc.
 * @license http://buildwithcraft.com/license
 */

namespace craft\app\templating\twigextensions;

/**
 * Represents a paginate node.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.0
 */
class Paginate_Node extends \Twig_Node
{
	// Public Methods
	// =========================================================================

	/**
	 * Compiles the node to PHP.
	 *
	 * @param \Twig_Compiler $compiler
	 *
	 * @return null
	 */
	public function compile(\Twig_Compiler $compiler)
	{
		$compiler
			->addDebugInfo($this)
			// the (array) cast bypasses a PHP 5.2.6 bug
			//->write("\$context['_parent'] = (array) \$context;\n")
			->write("list(\$context['paginate'], ")
			->subcompile($this->getNode('elementsTarget'))
			->raw(') = \craft\app\helpers\TemplateHelper::paginateCriteria(')
			->subcompile($this->getNode('criteria'))
			->raw(");\n")
			->subcompile($this->getNode('body'), false)
			->write('unset($context[\'paginate\'], ')
			->subcompile($this->getNode('elementsTarget'))
			->raw(");\n");
	}
}
