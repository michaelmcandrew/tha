; $Id

This module has been developed by Prime Focus Technology Ltd (http://www.primefocustechnology.com)

Description
-----------

The purpose of this module is to patch an issue with the drupal calendar module
that creates a link to the calendar at the bottom of every node.  By patching this 
as a module, it is simple for the user to switch off, and remove the patch as 
and when the issues is fixed in the calendar module.

The code is courtesy of NikLP on http://drupal.org/node/462748#comment-2099292 item #7.
Prime Focus Technology has wrapped the function in a module for ease of use.

The module does not contain any administration, and will arbitrarily remove the calendar
link from all nodes.  Future enhancements may allow the user to select the content
types on which to apply this rule, but at the moment this is not in the development plan
as it is simply a patch.

To contact Prime Focus Technology Ltd please visit www.primefocustechnology.com


Installation
------------

1.  Extract and copy the remove_calendar_link folder to sites/all/modules. 
 
2.  Go to your modules list, and find the module in the "date/time" block.  

3.  Enable the module.  There is no further configuration required.

Note.  When the calendar module is update to fix the issue, it is recommended
that you disable and uninstall this module.

License
-------
This module is issued under GNU GENERAL PUBLIC LICENSE.  Please see included LICENSE.txt for full details.
