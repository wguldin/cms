<?php
/**
 * @link http://buildwithcraft.com/
 * @copyright Copyright (c) 2015 Pixel & Tonic, Inc.
 * @license http://buildwithcraft.com/license
 */

namespace craft\app\variables;

use craft\app\elements\Asset;
use craft\app\elements\Category;
use craft\app\elements\db\AssetQuery;
use craft\app\elements\db\CategoryQuery;
use craft\app\elements\db\EntryQuery;
use craft\app\elements\db\TagQuery;
use craft\app\elements\db\UserQuery;
use craft\app\elements\Entry;
use craft\app\elements\Tag;
use craft\app\elements\User;
use yii\di\ServiceLocator;

/**
 * Craft defines the `craft` global template variable.
 *
 * @property App $app
 * @property Config $config
 * @property Elements $elements
 * @property Cp $cp
 * @property Dashboard $dashboard
 * @property Deprecator $deprecator
 * @property Fields $fields
 * @property Feeds $feeds
 * @property Globals $globals
 * @property Plugins $plugins
 * @property HttpRequest $request
 * @property Routes $routes
 * @property Sections $sections
 * @property SystemSettings $systemSettings
 * @property Tasks $tasks
 * @property Updates $updates
 * @property UserSession $session
 * @property I18n $i18n
 * @property UserGroups $userGroups
 * @property UserPermissions $userPermissions
 * @property EmailMessages $emailMessages
 * @property EntryRevisions $entryRevisions
 * @property Rebrand $rebrand
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.0
 */
class Craft extends ServiceLocator
{
	// Public Methods
	// =========================================================================

	/**
	 * @inheritdoc
	 */
	public function __construct($config = [])
	{
		// Set the core components
		$config['components'] = [
			'app' => 'craft\app\variables\App',
			'config' => 'craft\app\variables\Config',
			'elements' => 'craft\app\variables\Elements',
			'cp' => 'craft\app\variables\Cp',
			'dashboard' => 'craft\app\variables\Dashboard',
			'deprecator' => 'craft\app\variables\Deprecator',
			'fields' => 'craft\app\variables\Fields',
			'feeds' => 'craft\app\variables\Feeds',
			'globals' => 'craft\app\variables\Globals',
			'plugins' => 'craft\app\variables\Plugins',
			'request' => 'craft\app\variables\HttpRequest',
			'routes' => 'craft\app\variables\Routes',
			'sections' => 'craft\app\variables\Sections',
			'systemSettings' => 'craft\app\variables\SystemSettings',
			'tasks' => 'craft\app\variables\Tasks',
			'updates' => 'craft\app\variables\Updates',
			'session' => 'craft\app\variables\UserSession',
			'i18n' => 'craft\app\variables\I18n',
		];

		switch (\Craft::$app->getEdition())
		{
			case \Craft::Pro:
			{
				$config['components'] = array_merge($config['components'], [
					'userGroups' => 'craft\app\variables\UserGroups',
					'userPermissions' => 'craft\app\variables\UserPermissions',
				]);
				// Keep going...
			}
			case \Craft::Client:
			{
				$config['components'] = array_merge($config['components'], [
					'emailMessages' => 'craft\app\variables\EmailMessages',
					'entryRevisions' => 'craft\app\variables\EntryRevisions',
					'rebrand' => 'craft\app\variables\Rebrand',
				]);
			}
		}

		// Add plugin components
		foreach (\Craft::$app->plugins->getAllPlugins() as $handle => $plugin)
		{
			if (!isset($config['components'][$handle]))
			{
				$component = $plugin->getVariableDefinition();

				if ($component !== null)
				{
					$config['components'][$handle] = $component;
				}
			}
		}

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function __call($name, $params)
	{
		// Are they calling one of the components as if it's still a function?
		if ($params === [] && $this->has($name))
		{
			\Craft::$app->deprecator->log('CraftVariable::__call()', "craft.{$name}() is no longer a function. Use “craft.{$name}” instead (without the parentheses).");
			return $this->get($name);
		}
		else
		{
			return parent::__call($name, $params);
		}
	}

	// General info
	// -------------------------------------------------------------------------

	/**
	 * Gets the current language in use.
	 *
	 * @return string
	 */
	public function locale()
	{
		return \Craft::$app->language;
	}

	/**
	 * Returns whether this site has multiple locales.
	 *
	 * @return bool
	 */
	public function isLocalized()
	{
		return \Craft::$app->isLocalized();
	}

	// Element queries
	// -------------------------------------------------------------------------

	/**
	 * Returns a new AssetQuery instance.
	 *
	 * @param mixed $criteria
	 * @return AssetQuery
	 */
	public function assets($criteria = null)
	{
		return Asset::find()->configure($criteria);
	}

	/**
	 * Returns a new CategoryQuery instance.
	 *
	 * @param mixed $criteria
	 * @return CategoryQuery
	 */
	public function categories($criteria = null)
	{
		return Category::find()->configure($criteria);
	}

	/**
	 * Returns a new EntryQuery instance.
	 *
	 * @param mixed $criteria
	 * @return EntryQuery
	 */
	public function entries($criteria = null)
	{
		return Entry::find()->configure($criteria);
	}

	/**
	 * Returns a new TagQuery instance.
	 *
	 * @param mixed $criteria
	 * @return TagQuery
	 */
	public function tags($criteria = null)
	{
		return Tag::find()->configure($criteria);
	}

	/**
	 * Returns a new UserQuery instance
	 *
	 * @param mixed $criteria
	 * @return UserQuery
	 */
	public function users($criteria = null)
	{
		return User::find()->configure($criteria);
	}
}
