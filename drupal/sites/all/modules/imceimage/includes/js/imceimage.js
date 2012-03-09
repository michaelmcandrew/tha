/**
 * $Id: imceimage.js,v 1.1.2.1 2010/02/17 01:36:06 vincenzo Exp $
 * imceimage javascript handler functions supporting the imceimage module
 */
var imceImage = {};

imceImage.initiate = function() {}

imceImage.open = function (url, field) {
   imceImage.target = field;
   imceImage.pop = window.open(url + (url.indexOf('?') < 0 ? '?' : '&') +'app=nomatter|imceload@imceImageImceLoad', '', 'width='+ 760 +',height='+ 560 +',resizable=1');
   imceImage.pop.focus();
}

function imceImageImceLoad(win) {
  win.imce.setSendTo(Drupal.t('Send to @app', {'@app': Drupal.t('imceimage')}), imceImage.insert);
  $(window).unload(function() {
    if (imceInline.pop && !imceInline.pop.closed) imceInline.pop.close();
  });
}
imceImage.insert = function (file, win) {
	win.close();
	
	fld = imceImage.target;
	var img = '#imceimageimg-' + fld + '-wrapper img';
        
        //only display a valid image here..
        $.ajax({
         type: 'GET',
         url: Drupal.settings.imceimage.url + file.url,
         data: '',
         dataType: 'json',
         success: function(ret) {
            if(ret.validimage) {
               $(img).attr('src', file.url);
               $(img).attr('width', 80);
               $(img).attr('height', 80);
         
               /* form elements here. */
               $('#imceimagepath-' + fld).val(file.url);
               $('#imceimagewidth-' + fld).val(file.width);
               $('#imceimageheight-' + fld).val(file.height);

               $('#imceimagefieldset-' + fld).show();
            }
            else {
               alert('not a valid image');
            }
         },
         error: function(xmlhttp) {
            alert(Drupal.ahahError(xmlhttp, Drupal.settings.imceimage.url));
         }
        });
}


imceImage.remove = function(fld) {

  // reset the Title value
  $('#imceimagetitle-' + fld).val('');
  
  // reset the Alt value
  $('#imceimagealt-' + fld).val('');

  // reset the Height, Width, and Path values
  $('#imceimageheight-' + fld).val('');
  $('#imceimagewidth-' + fld).val('');
  $('#imceimagepath-' + fld).val('');

  // reset the Image value
  $('#imceimageimg-' + fld).attr('src', 'includes/images/1x1.png');

  // hide the Fieldset
  $('#imceimagefieldset-' + fld).hide();
}