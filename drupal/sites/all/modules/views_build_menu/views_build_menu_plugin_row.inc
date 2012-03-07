<?php

class views_build_menu_plugin_row extends views_plugin_row {

  /**
   * Render a row object. This usually passes through to a theme template
   * of some form, but not always.
   */
  function render($row) {
    // We need to render the row:
    $output = parent::render($row);

    // If we're not live previewing, do some cool stuff!
    if (empty($this->view->live_preview)) {
      $previewing = FALSE;
    }
    else {
      $previewing = TRUE;
    }

    $parent_item = $this->view;

    $plid = $this->display->handler->get_option('plid');
    if ($plid) {
      list($menu_name, $plid) = explode(':', $plid, 2);

      $style_options = $this->display->display_options['style_options'];

      // Let's just try creating menu items:
      $menu_item = array(
        'menu_name' => $menu_name,
        'plid' => $plid,
      );

      // Now add the optional item if they are set
      $options = array(
        'link_weight' => 'weight',
        'link_source' => 'link_path',
        'link_title' => 'link_title',
        //'link_enabled' => 'hidden',
      );
      foreach ($options as $view_option => $menu_option) {
        if (!empty($style_options[$view_option]) && !empty($output[$style_options[$view_option]]->content)) {
          // We need the nasty call to decode_entities because views is cool and sanitises its output
          $menu_item[$menu_option] = decode_entities($output[$style_options[$view_option]]->content);
        }
      }

      // If the menu path has a query or fragment in it, need to move it to the options:
      if (!menu_path_is_external($menu_item['link_path'])) {
        $parsed_link = parse_url($menu_item['link_path']);
        if (isset($parsed_link['query'])) {
          $menu_item['options']['query'] = $parsed_link['query'];
        }
        if (isset($parsed_link['fragment'])) {
          $menu_item['options']['fragment'] = $parsed_link['fragment'];
        }
        if ($menu_item['link_path'] != $parsed_link['path']) {
          $menu_item['link_path'] = $parsed_link['path'];
        }
      }

      if (!empty($style_options['link_description']) && !empty($output[$style_options['link_description']]->content)) {
        $menu_item['options']['attributes']['title'] = $output[$style_options['link_description']]->content;
      }

      if (!empty($menu_item['link_path']) && !empty($menu_item['link_title'])) {
        $unique_id = $output[$style_options['views_menu_item_id']]->content;
        if (!$previewing) {
          // Get the depth of these menu items:
          $depth = $this->display->handler->get_option('depth');
          $depth_trail = $this->display->handler->get_option('depth_trail');

          if ($mlid = $this->drupal_menu_link_save($unique_id, $menu_item, $depth_trail)) {
            $content .= $unique_id;

            // Recurse, passing in this unique ID as an argument:

            if ($depth > 1) {
              // Get a fresh view because our current one has a lot of stuff on it because it's
              // already been executed.
              $view = $this->view->clone_view();
              $view->original_args = $view->args;

              $args = array($unique_id);
              $view->set_arguments($args);
              $view->set_display($this->display->id);

              // Set the depth:
              $view->display_handler->set_option('depth', $depth - 1);
              $this_depth_trail = $depth_trail;
              $this_depth_trail[] = $unique_id;
              $view->display_handler->set_option('depth_trail', $this_depth_trail);
              $view->display_handler->set_option('plid', $menu_name .':' . $mlid);

              $view->execute_display($this->display->id, $args);


              //dsm($view);
            }
          }


        }
        else {
          return $menu_item;
        }
      }

    }

    return $content;

  }

  function drupal_menu_link_save($unique_id, $menu_item, $depth_trail = array()) {
    // Find out if we've created this menu item before:
    $item = views_build_menu_item_load($this->view->name . ':' . $this->display->id, $unique_id, $depth_trail);
    if ($item) {
      // We've already added this menu item, so we can just update it?
      foreach ($menu_item as $key => $value) {
        $item[$key] = $menu_item[$key];
      }
      menu_link_save($item);
      return $item['mlid'];
    }
    else {
      // We need to actually create this menu item:
      if ($mlid = menu_link_save($menu_item)) {
        // Save this into our tables:
        $record = array(
          'mlid' => $mlid,
          'view' => $this->view->name . ':' . $this->display->id,
          'unique_id' => $unique_id,
          'depth' => implode('.', $depth_trail),
        );
        drupal_write_record('views_build_menu_item', $record);
        return $mlid;
      }
    }

    return FALSE;
  }
}
