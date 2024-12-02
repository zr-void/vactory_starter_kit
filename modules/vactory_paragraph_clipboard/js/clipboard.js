(function ($, Drupal, drupalSettings) {
  'use strict';

  Drupal.behaviors.vactoryParagraphClipboard = {
    attach: function (context, settings) {

      const paragraphToCopy = localStorage.getItem('paragraphToCopy');
      if (paragraphToCopy) {
        $('.paragraph-clipboard-paste').show();
      }

      // Bouton Copier
      $('.paragraph-clipboard-copy', context).on('click', function(e) {
        e.preventDefault();
        const paragraphId = $(this).attr('data-paragraph-id');
        localStorage.setItem('paragraphToCopy', paragraphId);
        $('.paragraph-clipboard-paste').show();
      });

      // Bouton Coller
      $('.paragraph-clipboard-paste', context).on('click', function(e) {
        e.preventDefault();
        const nodeId = $(this).attr('data-node-id');

        // Appel Ajax pour coller le paragraphe
        $.ajax({
          url: '/ajax/paragraph-clipboard/paste',
          method: 'POST',
          data: JSON.stringify({
            paragraph_id: paragraphToCopy,
            node_id: nodeId
          }),
          contentType: 'application/json',
          success: function(response) {
            localStorage.removeItem('paragraphToCopy')
            $(this).hide()
            window.location.reload();
          },
          error: function(xhr) {
            console.error('Error pasting paragraph:', xhr.responseText);
          }
        });
      });
    }
  };

})(jQuery, Drupal, drupalSettings); 