// Masonry settings to organize index and footer widgets
jQuery(document).ready(function($){
    var $container = $('#footer-widgets');
	var $index = $('#masonry-index');
    var $masonryOn;
    var $columnWidth = 315;
    
    if ($(document).width() > 768) {
        $masonryOn = true;
        runMasonry();
    }

    $(window).resize( function() {
        if ($(document).width() < 768) {
            if ($masonryOn){
                $container.masonry('destroy');
				$index.masonry('destroy');
				$masonryOn = false;
            }

        } else {
            $masonryOn = true;
            runMasonry();
        }
    });
    
    function runMasonry() {
        // initialize
        $container.masonry({
            columnWidth: $columnWidth,
            itemSelector: '.widget',
            isFitWidth: true,
            isAnimated: true
        });
		$index.masonry({
			columnWidth: $columnWidth,
            itemSelector: 'article',
			isFitWidth: true,
            isAnimated: true
        });
    }
    
});