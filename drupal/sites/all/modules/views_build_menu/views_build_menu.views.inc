<?php

/**
 * Implementation of hook_views_plugins().
 */
function views_build_menu_views_plugins() {
  return array(
    'display' => array(
      'views_build_menu' => array(
        'title' => t('Menu items'),
        'help' => t("The view results will be used to build menu items."),
        'handler' => 'views_build_menu_plugin_display',
        'theme' => 'views_view',
        'use ajax' => FALSE,
        'use pager' => FALSE,
        'use more' => FALSE,
        'accept attachments' => FALSE,
        'admin' => t('Menu settings'),
        'parent' => 'views_periodic_execution',
      ),
    ),
    'style' => array(
      'views_build_menu_style' => array(
        'title' => t('Menu items'),
        'help' => t('.'),
        'handler' => 'views_build_menu_plugin_style',
        'theme' => 'views_view_unformatted',
        'uses row plugin' => TRUE,
        'uses options' => TRUE,
        'type' => 'views_build_menu',
        'help topic' => 'style-views-build-menu',
      ),
    ),
    'row' => array(
      'views_build_menu_row' => array(
        'title' => t('Menu item'),
        'help' => t('.'),
        'handler' => 'views_build_menu_plugin_row',
        'theme' => 'views_build_menu_row',
        'theme path' => drupal_get_path('module', 'views_build_menu'),
        //'theme' => 'rules_executor_view_ruleset',
        //'theme path' => drupal_get_path('module', 'rules_executor'),
        'uses fields' => TRUE,
        'uses options' => TRUE,
        'type' => 'views_build_menu',
      ),
    ),
  );
}
