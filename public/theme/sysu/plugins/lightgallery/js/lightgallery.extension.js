$(document).ready(function() {

  window.prettyPrint && prettyPrint()

  var $animThumb = $('#lightgallery');
  if ($animThumb.length) {
    $animThumb.justifiedGallery({
      border: 3,
      rowHeight: 225
    }).on('jg.complete', function () {
      lightGallery($animThumb[0], {
        thumbnail: true
      });
    });
  };
});