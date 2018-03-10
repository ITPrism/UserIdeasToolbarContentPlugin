<?php
/**
 * @package         UserIdeas
 * @subpackage      Plugins
 * @author          Todor Iliev
 * @copyright       Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license         http://www.gnu.org/licenses/gpl-3.0.en.html GNU/GPLv3
 */

defined('_JEXEC') or die;
/**
 * @var Joomla\Registry\Registry $componentParams
 * @var int $userId
 */
?>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" href="javascript:void(0);"><?php echo JText::_('PLG_CONTENT_USERIDEASTOOLBAR_SORT_BY'); ?>:</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <ul class="nav navbar-nav">
            <?php
            if ($this->params->get('show_sort_title', Prism\Constants::DISPLAY)) {
                echo UserideasHelper::sortByLink(JText::_('PLG_CONTENT_USERIDEASTOOLBAR_TITLE'), 'alpha', $orderOptions, 'ui-order-alphabet');
            }
            if ($this->params->get('show_sort_votes', Prism\Constants::DISPLAY)) {
                echo UserideasHelper::sortByLink(JText::_('PLG_CONTENT_USERIDEASTOOLBAR_VOTES'), 'votes', $orderOptions, 'ui-order-funding');
            }
            if ($this->params->get('show_sort_recent', Prism\Constants::DISPLAY)) {
                echo UserideasHelper::sortByLink(JText::_('PLG_CONTENT_USERIDEASTOOLBAR_RECENT'), 'date', $orderOptions, 'ui-order-latest');
            }
            if ($this->params->get('show_sort_popular', Prism\Constants::DISPLAY)) {
                echo UserideasHelper::sortByLink(JText::_('PLG_CONTENT_USERIDEASTOOLBAR_POPULAR'), 'hits', $orderOptions, 'ui-order-popular');
            }
            ?>

            <?php if ($this->params->get('show_statuses', Prism\Constants::DO_NOT_DISPLAY)) { ?>
            <li class="dropdown">
                <a href="javascript: void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="<?php echo JText::_('PLG_CONTENT_USERIDEASTOOLBAR_FILTER_BY_STATUS');?>">
                    <span class="fa fa-filter"></span>
                </a>
                <ul class="dropdown-menu">
                <?php foreach ($statuses as $status) {
                    echo JHtml::_('userideas.filterStatus', $filterStatus, $status, $url);
                } ?>
                </ul>
            </li>
            <?php } ?>
        </ul>
        <?php if ($this->params->get('show_search_form', Prism\Constants::DISPLAY)) {?>
        <form class="navbar-form navbar-left" action="<?php echo $url;?>" method="get">
            <div class="form-group">
                <input name="filter_search" value="<?php echo $filterSearch;?>" type="text" class="form-control" placeholder="<?php echo JText::_('PLG_CONTENT_USERIDEASTOOLBAR_SEARCH'); ?>">
            </div>
            <button type="submit" class="btn btn-default" title="<?php echo JText::_('PLG_CONTENT_USERIDEASTOOLBAR_SUBMIT'); ?>">
                <span class="fa fa-search" aria-hidden="true"></span>
                <span class="hidden-sm hidden-md hidden-lg"><?php echo JText::_('PLG_CONTENT_USERIDEASTOOLBAR_SUBMIT'); ?></span>
            </button>
        </form>
        <?php }?>
        <?php if ($this->params->get('show_post_button', Prism\Constants::DO_NOT_DISPLAY) and $canCreate) {?>
            <a href="<?php echo JRoute::_(UserideasHelperRoute::getFormRoute(0));?>" class="btn btn-default navbar-btn navbar-right mr-5" role="button" title="<?php echo JText::_('COM_USERIDEAS_POST_ITEM');?>">
                <span class="fa fa-plus-circle"></span>
                <span class="hidden-sm hidden-md hidden-lg"><?php echo JText::_('PLG_CONTENT_USERIDEASTOOLBAR_POST_ITEM'); ?></span>
            </a>
        <?php }?>

        <?php if (!$userId) { ?>
            <a href="<?php echo JRoute::_('index.php?option=com_users&view=login'); ?>" role="button" class="btn btn-default navbar-btn navbar-right mr-5" title="<?php echo JText::_('PLG_CONTENT_USERIDEASTOOLBAR_SIGN_IN'); ?>">
                <span class="fa fa-sign-in" aria-hidden="true"></span>
            </a>
        <?php } ?>
    </div><!-- /.container-fluid -->
</nav>
