$Id: README.txt,v 1.1.2.10 2009/03/05 03:27:27 michellec Exp $

CONTENTS OF THIS FILE
-----------------------------------------------------------------------------------------
 * Introduction
 * Installation
 * Usage
  
INTRODUCTION
-----------------------------------------------------------------------------------------
Author Pane (http://drupal.org/project/author_pane) provides information about the author 
of a node, comment, or profile page. From core, it displays the user picture, name, join 
date, online status, contact link, and profile information. In addition, it gathers data 
from many user related contributed modules and puts it together in a modifiable template 
file.

INSTALLATION
-----------------------------------------------------------------------------------------
1. Copy the entire author_pane module directory into your normal directory for modules, 
   usually sites/all/modules.
   
2. Enable the Author Pane module in ?q=admin/build/modules.

USAGE
-----------------------------------------------------------------------------------------
* If you have Advanced Forum installed, it will make use of Author Pane automatically.
   
* If you have Advanced Profile Kit installed, it will make use of Author Pane 
  automatically.

* If you have Panels installed, you can add the Author Pane to any panel display. It 
  requires the user context. You can set a custom image path and template file to use
  or let it fall back to the ones that come with the module.
  
* There is an Author Pane block provided that you can enable. The block will show up on
  user/NN, blog/NN, and node/NN where the node type is one that you allow in the block
  config. If you want to exclude it from one of those page types, use the core block
  visibility option. You can also set a seperate image path / template file for this
  usage of the Author Pane or leave it blank to use the ones in the module.
  
* You can insert the Author Pane any place that has a user object available with the 
  following syntax. $account is user object, $image_path is the path to the images
  Author Pane can use (optional) and $template_file is the name of the template file
  to use, without the .tpl.php part (optional).
  
  <?php print theme('author_pane', $account, $image_path, $template_file); ?>
  
  
  



