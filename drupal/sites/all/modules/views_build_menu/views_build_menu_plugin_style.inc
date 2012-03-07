<?php

class views_build_menu_plugin_style extends views_plugin_style {

  function option_definition() {
    $options = parent::option_definition();

    $options['link_source'] = array('default' => '');
    $options['link_title'] = array('default' => '');
    $options['link_description'] = array('default' => '');
    $options['link_weight'] = array('default' => '');
    $options['link_enabled'] = array('default' => '');
    $options['views_menu_item_id'] = array('default' => '');

    return $options;
  }

  function options_form(&$form, &$form_state) {
    $fields = array();
    foreach ($this->display->handler->get_handlers('field') as $field => $handler) {
      $fields[$field] = $handler->ui_name();
    }

    $fields_optional = array_merge(array('' => t('Default/none')), $fields);

    $form['views_menu_item_id'] = array(
      '#type' => 'select',
      '#default_value' => $this->options['views_menu_item_id'],
      '#title' => t('Row ID'),
      '#description' => t('This should be a unique ID for identifying this row.'),
      '#options' => $fields,
      '#required' => TRUE,
    );

    $form['link_source'] = array(
      '#type' => 'select',
      '#default_value' => $this->options['link_source'],
      '#title' => t('Link URL'),
      '#description' => t('Select the field that will provide the link URL.'),
      '#options' => $fields,
      '#required' => TRUE,
    );

    $form['link_title'] = array(
      '#type' => 'select',
      '#default_value' => $this->options['link_title'],
      '#title' => t('Link title'),
      '#description' => t('Select the field that will provide the link title.'),
      '#options' => $fields,
      '#required' => TRUE,
    );

    $form['link_description'] = array(
      '#type' => 'select',
      '#default_value' => $this->options['link_description'],
      '#title' => t('Link description'),
      '#description' => t('Select the field that will provide the link description.'),
      '#options' => $fields_optional,
    );

    $form['link_weight'] = array(
      '#type' => 'select',
      '#default_value' => $this->options['link_weight'],
      '#title' => t('Link weight'),
      '#description' => t('Select the field that will provide the link weight.'),
      '#options' => $fields_optional,
    );

    $form['link_enabled'] = array(
      '#type' => 'select',
      '#default_value' => $this->options['link_enabled'],
      '#title' => t('Link enabled'),
      '#description' => t('Select the field that will provide the link enabled status.'),
      '#options' => $fields_optional,
    );
  }

  /**
   * Render the display in this style.
   */
  function render() {
    if ($this->uses_row_plugin() && empty($this->row_plugin)) {
      vpr('views_plugin_style_default: Missing row plugin');
      return;
    }

    // If we're not live previewing, do some cool stuff!
    if (empty($this->view->live_preview)) {
      $previewing = FALSE;
    }
    else {
      $previewing = TRUE;
    }

    // Group the rows according to the grouping field, if specified.
    $sets = $this->render_grouping($this->view->result, $this->options['grouping']);

    // Render each group separately and concatenate.  Plugins may override this
    // method if they wish some other way of handling grouping.
    $output = '';
    foreach ($sets as $title => $records) {
      if ($this->uses_row_plugin()) {
        $rows = array();
        foreach ($records as $row_index => $row) {
          $this->view->row_index = $row_index;
          $rows[] = $this->row_plugin->render($row);
        }
      }
      else {
        $rows = $records;
      }

      if (!$previewing) {

        // We some some special handling here:
        // Delete menu items that we had created on this level, but should no longer exist:
        $depth_trail = $this->display->handler->get_option('depth_trail');
        $all_items = views_build_menu_item_load_all_items($this->view->name . ':' . $this->display->id, $depth_trail);
        $items_to_remove = array_diff(array_keys($all_items), $rows);
        foreach ($items_to_remove as $unique_id) {
          views_build_menu_menu_link_delete($all_items[$unique_id]['mlid']);
        }

        // Delete menu trees that are below this one, if we are the last level:
        if ($this->display->handler->get_option('depth') == 1) {
          foreach ($rows as $unique_id) {
            $this_depth_trail = $depth_trail;
            $this_depth_trail[] = $unique_id;
            $result = db_query("SELECT mlid, unique_id FROM {views_build_menu_item} WHERE view = '%s' AND depth LIKE '%s%%'", $this->view->name . ':' . $this->display->id, implode('.', $this_depth_trail));
            while ($row = db_fetch_object($result)) {
              views_build_menu_menu_link_delete($row->mlid);
            }
          }
        }
        // Output as normal:
        $output .= theme($this->theme_functions(), $this->view, $this->options, $rows, $title);
      }
      else {
        // We should re-write this when we have time:
        $headers = array(
          t('Depth'),
          t('Link title'),
          t('Link path'),
        );
        $table_rows = array();
        foreach ($rows as $menu_item) {
          $path = $menu_item['link_path'];
          if (!empty($menu_item['options']['query'])) {
            $path .= '?' . $menu_item['options']['query'];
          }
          if (!empty($menu_item['options']['fragment'])) {
            $path .= '#' . $menu_item['options']['fragment'];
          }
          $table_rows[] = array(
            1,
            filter_xss($menu_item['link_title']),
            filter_xss($path),
          );
        }
        $output .= theme('table', $headers, $table_rows);
      }


    }
    unset($this->view->row_index);
    return $output;
  }

}
