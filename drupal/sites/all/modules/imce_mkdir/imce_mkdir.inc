<?php

/**
 * Implementation of hook_form_formID_alter().
 */
function _imce_mkdir_form_imce_profile_form_alter(&$form, &$form_state) {
  foreach (element_children($form['profile']['directories']) as $key) {
    $form['profile']['directories'][$key]['mkdir'] = array(
      '#type' => 'checkbox',
      '#title' => t('Add subdirectories'),
      '#default_value' => isset($form_state['profile']['directories'][$key]['mkdir']) ? $form_state['profile']['directories'][$key]['mkdir'] : 0,
    );
    $form['profile']['directories'][$key]['rmdir'] = array(
      '#type' => 'checkbox',
      '#title' => t('Remove subdirectories'),
      '#default_value' => isset($form_state['profile']['directories'][$key]['rmdir']) ? $form_state['profile']['directories'][$key]['rmdir'] : 0,
    );
  }
  $form['profile']['mkdirnum'] = array(
    '#type' => 'textfield',
    '#title' => t('Maximum number of subdirectories'),
    '#default_value' =>  isset($form_state['profile']['mkdirnum']) ? $form_state['profile']['mkdirnum'] : 2,
    '#description' => t('This setting is applicable only if you allow subdirectory creation under any of the predefined directories. Define here the maximum number of subdirectories a directory can have. Setting this to 0 removes the limit and also allows infinite subdirectory depth.'),
  );
}

/**
 * Mkdir form.
 */
function _imce_mkdir_form(&$form_state, &$imce) {
  $mkdir['html1']['#value'] = '<div class="container-inline">';
  $mkdir['dirname'] = array(
    '#type' => 'textfield',
    '#title' => t('Subdirectory name'),
    '#size' => 12,
    '#maxlength' => 255,
  );
  if (imce_perm_exists($imce, 'mkdir')) {
    $mkdir['mkdir'] = array(
      '#type' => 'submit',
      '#value' => t('Add'),
      '#submit' => $imce['perm']['mkdir'] ? array('imce_mkdir_submit') : NULL,
    );
  }
  if (imce_perm_exists($imce, 'rmdir')) {
    $mkdir['rmdir'] = array(
      '#type' => 'submit',
      '#value' => t('Remove'),
      '#submit' => $imce['perm']['rmdir'] ? array('imce_mkdir_rmdir_submit') : NULL,
    );
  }
  $mkdir['html2']['#value'] = '</div>';
  $form['fset_mkdir'] = array(
    '#type' => 'fieldset',
    '#title' => t('Directory'),
  ) + $mkdir;
  $form['#action'] = $imce['url'];
  return $form;
}

/**
 * Submit mkdir form.
 */
function imce_mkdir_submit($form, &$form_state) {
  $form_state['redirect'] = FALSE;
  imce_mkdir_batch($form['#parameters'][2]['imce'], array(rawurldecode($form_state['values']['dirname'])));
}

/**
 * Batch add directories.
 */
function imce_mkdir_batch(&$imce, $dirnames = array()) {
  if (!isset($imce['diradded'])) {
    $imce['diradded'] = array();
  }
  $parent = file_directory_path() . ($imce['dir'] == '.' ? '' : '/'. $imce['dir']);

  foreach ($dirnames as $dirname) {
    if (!preg_match('/^[A-Za-z0-9_\-]+$/', $dirname)) {
      drupal_set_message(t('%dirname is not a valid directory name. It should contain only alphanumeric characters, hyphen and underscore.', array('%dirname' => $dirname)), 'error');
      continue;
    }

    $dirpath = $parent .'/'. $dirname;

    if (file_exists($dirpath)) {
      drupal_set_message(t('Subdirectory %dir already exists.', array('%dir' => $dirname)), 'error');
      continue;
    }

    if (!file_check_directory($dirpath, FILE_CREATE_DIRECTORY)) {
      drupal_set_message(t('Subdirectory %dir could not be created.', array('%dir' => $dirname)), 'error');
      continue;
    }

    drupal_set_message(t('Subdirectory %dir has been added.', array('%dir' => $dirname)));
    $imce['diradded'][] = $imce['subdirectories'][] = $dirname;
  }
}

/**
 * Submit rmdir form.
 */
function imce_mkdir_rmdir_submit($form, &$form_state) {
  $form_state['redirect'] = FALSE;
  imce_mkdir_rmdir_batch($form['#parameters'][2]['imce'], array(rawurldecode($form_state['values']['dirname'])));
}

/**
 * Batch remove directories.
 */
function imce_mkdir_rmdir_batch(&$imce, $dirnames = array()) {
  if (!isset($imce['dirremoved'])) {
    $imce['dirremoved'] = array();
  }
  $parent = file_directory_path() . ($imce['dir'] == '.' ? '' : '/'. $imce['dir']);
  $prefix = ($imce['dir'] == '.' ? '' : $imce['dir'] . '/');

  foreach ($dirnames as $dirname) {
    $index = array_search($dirname, $imce['subdirectories']);

    if ($index === FALSE) {
      drupal_set_message(t('Subdirectory %dir does not exist.', array('%dir' => $dirname)), 'error');
      continue;
    }

    if (isset($imce['directories'][$prefix . $dirname])) {
      drupal_set_message(t('Subdirectory %dir is a predefined directory and can not be removed.', array('%dir' => $dirname)), 'error');
      continue;
    }

    $dirpath = $parent .'/'. $dirname;
    if (!imce_mkdir_rmdir_recursive($dirpath)) {
      drupal_set_message(t('Subdirectory %dir could not be removed.', array('%dir' => $dirname)), 'error');
      continue;
    }

    drupal_set_message(t('Subdirectory %dir has been removed.', array('%dir' => $dirname)));
    $imce['dirremoved'] = array_merge($imce['dirremoved'], array_splice($imce['subdirectories'], $index, 1));
  }
}

/**
 * Recursive directory deletion
 */
function imce_mkdir_rmdir_recursive($path) {
  static $dirlen;
  if (!isset($dirlen)) {
    $dirlen = strlen(file_directory_path()) + 1;
  }
  if (is_dir($path) && !is_link($path)) {
    if ($handle = @opendir($path)) {
      while (($file = readdir($handle)) !== FALSE) {
        if ($file == '.' || $file == '..') {
          continue;
        }
        $filepath = $path .'/'. $file;
        if (!imce_mkdir_rmdir_recursive($filepath)) {
          drupal_set_message(t('%path could not be removed.', array('%path' => substr($filepath, $dirlen))), 'error');
          break;
        }
      }
      closedir($handle);
    }
    return @rmdir($path);
  }
  return imce_mkdir_unlink($path);
}

/**
 * Delete a file in recursive directory deletion.
 */
function imce_mkdir_unlink($filepath) {

  if (function_exists('imce_delete_filepath')) {
    return imce_delete_filepath($filepath);
  }

  $file = db_fetch_object(db_query("SELECT * FROM {files} WHERE filepath = '%s'", $filepath));
  //file exists in database
  if ($file) {
    //prevent imce returning ref count
    $file->imce_noref = TRUE;
    //check references
    $refs = array_filter(module_invoke_all('file_references', $file));
    //file is in use
    if (!empty($refs)) {
      drupal_set_message(t('%filename is in use by another application.', array('%filename' => $file->filename)), 'error');
      return FALSE;
    }
    //prepare deletion
    module_invoke_all('file_delete', $file);
    if (!file_delete($file->filepath)) {
      return FALSE;
    }
    db_query('DELETE FROM {files} WHERE fid = %d', $file->fid);
  }
  //not in db. probably loaded via ftp.
  elseif (!file_delete($filepath)) {
    return FALSE;
  }

  return TRUE;
}