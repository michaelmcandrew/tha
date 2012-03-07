<?php

class views_build_menu_plugin_display extends views_periodic_execution_plugin_display {
  function get_style_type() { return 'views_build_menu'; }

  function execute() {
    return $this->view->render($this->display->id);
  }

  function defaultable_sections($section = NULL) {
    if (in_array($section, array('style_options', 'style_plugin', 'row_options', 'row_plugin', ))) {
      return FALSE;
    }

    $sections = parent::defaultable_sections($section);

    return $sections;
  }

  function option_definition() {
    $options = parent::option_definition();

    $options['displays'] = array('default' => array());

    // Overrides for standard stuff:
    $options['style_plugin']['default'] = 'views_build_menu_style';
    //$options['style_options']['default']  = array('interval' => 60 * 60);
    $options['row_plugin']['default'] = 'views_build_menu_row';
    $options['defaults']['default']['style_plugin'] = FALSE;
    $options['defaults']['default']['style_options'] = FALSE;
    $options['defaults']['default']['row_plugin'] = FALSE;
    $options['defaults']['default']['row_options'] = FALSE;

    // Actual options:
    $options['plid'] = array('default' => 0);
    $options['depth'] = array('default' => 1);

    // Hidden option
    $options['depth_trail'] = array('default' => array());

    return $options;
  }


  /**
   * Provide the summary for page options in the views UI.
   *
   * This output is returned as an array.
   */
  function options_summary(&$categories, &$options) {
    // It is very important to call the parent function here:
    parent::options_summary($categories, $options);

    $categories['built_menu'] = array(
      'title' => t('Built menu'),
    );

    $possibles = $this->_possible_parents();

    $options['plid'] = array(
      'category' => 'built_menu',
      'title' => t('Parent menu'),
      'value' => isset($possibles[$this->get_option('plid')]) ? check_plain($possibles[$this->get_option('plid')]) : t('None selected'),
    );

    $options['depth'] = array(
      'category' => 'built_menu',
      'title' => t('Maximum depth'),
      'value' => check_plain($this->get_option('depth')),
    );
  }

  /**
   * Provide the default form for setting options.
   */
  function options_form(&$form, &$form_state) {
    // It is very important to call the parent function here:
    parent::options_form($form, $form_state);

    switch ($form_state['section']) {
      case 'plid':
        $form['plid'] = array(
          '#title' => t('Parent menu item'),
          '#type' => 'select',
          '#description' => t('Choose the menu item that should be used as a parent for newly created menu items.'),
          '#default_value' => $this->get_option('plid'),
          '#options' => $this->_possible_parents(),
          '#required' => TRUE,
        );
        break;

      case 'depth':
        $form['depth'] = array(
          '#title' => t('Maximum depth'),
          '#type' => 'textfield',
          '#description' => t('Choose the possible depth of created menus.'),
          '#default_value' => $this->get_option('depth'),
          '#required' => TRUE,
        );
        break;
    }
  }

  function options_submit(&$form, &$form_state) {
    // It is very important to call the parent function here:
    parent::options_submit($form, $form_state);
    switch ($form_state['section']) {
      case 'plid':
      case 'depth':
        $this->set_option($form_state['section'], $form_state['values'][$form_state['section']]);
        break;
    }
  }

  function _possible_parents() {
    $item['mlid'] = 0;
    $options = menu_parent_options(menu_get_menus(), $item);
    return $options;

  }
}
