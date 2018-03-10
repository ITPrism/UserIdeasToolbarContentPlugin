<?php
/**
 * @package         UserIdeas
 * @subpackage      Plugins
 * @author          Todor Iliev
 * @copyright       Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license         http://www.gnu.org/licenses/gpl-3.0.en.html GNU/GPLv3
 */

// no direct access
defined('_JEXEC') or die;

/**
 * UserIdeas Toolbar Plugin
 *
 * @package        UserIdeas
 * @subpackage     Plugins
 */
class plgContentUserideasToolbar extends JPlugin
{
    /**
     * @param string                   $context
     * @param array                    $items
     * @param Joomla\Registry\Registry $params
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \OutOfBoundsException
     * @throws \Exception
     *
     * @return null|string
     */
    public function onContentBeforeDisplay($context, &$items, &$params)
    {
        $app = JFactory::getApplication();
        /** @var $app JApplicationSite */

        if ($app->isAdmin()) {
            return null;
        }

        $doc = JFactory::getDocument();
        /**  @var $doc JDocumentHtml */

        // Check document type
        $docType = $doc->getType();
        if (strcmp('html', $docType) !== 0) {
            return null;
        }

        if (strcmp('com_userideas.items', $context) !== 0) {
            return null;
        }

        // Load language
        $this->loadLanguage();

        $view   = $app->input->getCmd('view');
        $option = $app->input->getCmd('option');
        if (strcmp('com_userideas', $option) === 0 and strcmp('category', $view) === 0) {
            $categoryId = $app->input->getInt('id');
            $url = JRoute::_(UserideasHelperRoute::getCategoryRoute($categoryId));
        } else {
            $url = JRoute::_(UserideasHelperRoute::getItemsRoute());
        }

        $filterSearch = $app->input->get('filter_search', '', 'string');

        $user = JFactory::getUser();
        $userId = $user->get('id');

        // Set permission state. Is it possible to be edited items?
        $canCreate = $user->authorise('core.create', 'com_userideas') || count($user->getAuthorisedCategories('com_userideas', 'core.create'));

        $componentParams = JComponentHelper::getParams('com_userideas');
        /** @var  $componentParams Joomla\Registry\Registry */

        // START Sorting filters
        $displaySortingFilters= false;
        if ($params->get('show_sort_title', Prism\Constants::DISPLAY) or $params->get('show_sort_votes', Prism\Constants::DISPLAY) or
            $params->get('show_sort_recent', Prism\Constants::DISPLAY) or $params->get('show_sort_popular', Prism\Constants::DISPLAY)) {
            $displaySortingFilters = true;

            // Get current ordering column.
            $pageParams   = $app->getParams();
            $container    = Prism\Container::getContainer();
            if ($container->exists(Userideas\Constants::CONTAINER_FILTER_ORDER_CONTEXT)) {
                $orderContext = $container->get(Userideas\Constants::CONTAINER_FILTER_ORDER_CONTEXT);
                $orderBy      = $app->getUserStateFromRequest($orderContext, 'filter_order', $pageParams->get('orderby_sec', 'rdate'), 'cmd');
            } else {
                $orderBy = $app->input->get('filter_order', $pageParams->get('orderby_sec', 'rdate'));
            }

            $orderOptions = array(
                'ordered_by'    => $orderBy,
                'url'           => $url,
                'item_class'    => $params->get('sort_item_class')
            );
        }

        if ($this->params->get('show_statuses', Prism\Constants::DO_NOT_DISPLAY)) {
            $cache = null;
            if ($app->get('caching', 0)) {
                $cache = JFactory::getCache('com_userideas', '');
                $cache->setLifeTime(Prism\Constants::TIME_SECONDS_24H);
            }
            
            if ($cache !== null) {
                $statuses = Userideas\Helper\CacheHelper::getStatusOptions($cache);
            } else {
                $container       = Prism\Container::getContainer();
                /** @var  $container Joomla\DI\Container */

                $containerHelper = new Userideas\Container\Helper();
                $statuses = $containerHelper->fetchStatuses($container);
                $statuses = $statuses->getStatusOptions();
            }

            $filterStatus   = $app->input->get('filter_status', '', 'int');
        }

        // Prepare output

        // Get the path for the layout file
        $path = JPluginHelper::getLayoutPath('content', 'userideastoolbar', 'default');

        // Render the login form.
        ob_start();
        include $path;
        $html = ob_get_clean();

        return $html;
    }
}
