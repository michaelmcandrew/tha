<?php

/**
 * Theme function for contextual popups.
 */
function contextual_preprocess_contextual(&$vars) {
  if (!empty($vars['links']) && is_array($vars['links'])) {
    $links = '';
    foreach ($vars['links'] as $link) {
      $links .= l($link['title'], $link['href'], $link);
    }
    $vars['links'] = $links;
  }
}
