// @function      Include
// @description   Includes an external scripts to the page
// @param         {string} scriptUrl

function include(scriptUrl) {
    var script = document.createElement('script');
    script.setAttribute('src', scriptUrl);
    document.getElementById('app').appendChild(script);
}




// @module Bootstrap
// @description Enables Bootstrap Plugin

// (function($) {
//     var o = $("link");
//     if (o.length > 0) {
//         for (let i = 0; i < o.length; i++) {
//             var attr = o[i].getAttribute("href");
//             var patt = /bootstrap/i;
//             var result = attr.match(patt);
//             if (result) {
//                 include("theme/stpaul/plugins/bootstrap/js/popper.js");
//                 include("theme/stpaul/plugins/bootstrap/js/bootstrap.min.js");
//             }
//         }
//     }
// })(jQuery);




// @module RD Navbar
// @description Enables RD Navbar Plugin

// (function($) {
//     var o = $(".rd-navbar");
//     if (o.length > 0) {
//         include('theme/stpaul/plugins/rd-navbar/dist/js/jquery.rd-navbar.js');
//     }
// })(jQuery);




// @module Owl Carousel
// @description Enables Owl Carousel Plugin

(function($) {
    var o = $(".owl-carousel");
    if (o.length > 0) {
        // include("theme/stpaul/plugins/owl.carousel/owl.carousel.extension.js");
        // include("theme/stpaul/plugins/owl.carousel/owl.carousel.js");
        $(window).on("load", function() {
            setTimeout(function() {
                $("#preloader").fadeOut("slow");
                $("#load").fadeOut("slow");
                $("#pre-loader").fadeOut("slow");
            }, 100);
        });

        $(document).ready(function() {
            $("#pills-bible-tab").on("click", function() {
                $("#pills-bible #pre-loader")
                    .delay(600)
                    .fadeOut("slow");
            });

            $("#pills-devotional-tab").on("click", function() {
                $("#pills-devotional #pre-loader")
                    .delay(600)
                    .fadeOut("slow");
            });
        });
    }
})(jQuery);




// @module Materialize
// @description Enables Materialize Plugin

// (function($) {
//     include("theme/stpaul/plugins/materialize/js/materialize.js");
// })(jQuery);




// @module Responsive Tabs
// @description Enables Responsive Tabs Plugin

// (function($) {
//     var o = $("[id^='responsiveTabs']");
//     if (o.length > 0) {
//         include('theme/stpaul/plugins/responsive-tabs/js/jquery.responsiveTabs.js');
//     }
// })(jQuery);




// @module AOS
// @description Enables AOS Plugin

// (function($) {
//     var o = $("link");
//     if (o.length > 0) {
//         for (let i = 0; i < o.length; i++) {
//             var attr = o[i].getAttribute("href");
//             var patt = /aos/i;
//             var result = attr.match(patt);
//             if (result) {
//                 include("theme/stpaul/plugins/aos/dist/aos.js");
//             }
//         }
//     }
// })(jQuery);




// @module JsSocial
// @description Enables JsSocial Plugin

// (function($) {
//     var o = $("link");
//     if (o.length > 0) {
//         for (let i = 0; i < o.length; i++) {
//             var attr = o[i].getAttribute("href");
//             var patt = /jssocials/i;
//             var result = attr.match(patt);
//             if (result) {
//                 include('theme/stpaul/plugins/jssocials/jssocials.js');
//             }
//         }
//     }
// })(jQuery);




// @module Price Range Silder
// @description Enables Price Range Silder Plugin

// (function($) {
//     var o = $(".js-range-slider");
//     if (o.length > 0) {
//         include('theme/stpaul/plugins/ion.rangeslider/js/ion.rangeSlider.js');
//     }
// })(jQuery);




// @module Vanilla Zoom
// @description Enables Vanilla Zoom Plugin

// (function($) {
//     var o = $(".vanilla-zoom");
//     if (o.length > 0) {
//         include('theme/stpaul/plugins/vanilla-zoom/vanilla-zoom.js');
//     }
// })(jQuery);




// @module Better Rating
// @description Enables Better Rating Plugin

// (function($) {
//     var o = $("#leave-review");
//     if (o.length > 0) {
//         include('theme/stpaul/js/better-rating.js');
//     }
// })(jQuery);




// @description FadeOut to Next Page

$(document).ready(function() {
    $(document).on("click", "a", function(e) {
        e.preventDefault();

        var link = $(this);
        var href = link.attr("href");
        var target = link.attr("target");
        if (target && target.indexOf("_blank") >= 0) {
            window.open(href, "_blank");
            return;
        }
        if (target && target.indexOf("_self") >= 0) {
            window.open(href, "_self");
            return;
        }
        if (href.indexOf("mailto:") == 0) {
            window.location = href;
            return;
        }
        if (href.indexOf("tel:") == 0) {
            window.location = href;
            return;
        }
        if (!href || href[0] === "#") {
            return;
        }

        setTimeout(function() {
            $("html").fadeOut(function() {
                window.location = href;
            });
        });
    });
});




// jQuery for page scrolling feature - requires jQuery Easing plugin

$(function() {
    $(".navbar-nav li a").bind("click", function(event) {
        var $anchor = $(this);
        $("html, body")
            .stop()
            .animate({
                    scrollTop: $($anchor.attr("href")).offset().top
                },
                1500,
                "easeInOutExpo"
            );
        event.preventDefault();
    });
    $(".page-scroll a").bind("click", function(event) {
        var $anchor = $(this);
        $("html, body")
            .stop()
            .animate({
                    scrollTop: $($anchor.attr("href")).offset().top
                },
                1500,
                "easeInOutExpo"
            );
        event.preventDefault();
    });
});




// jQuery for Side Menu feature

$(function() {
    var navikMenuListDropdown = $(".side-menu ul li:has(ul)");

    navikMenuListDropdown.each(function() {
        $(this).append('<span class="dropdown-append"></span>');
    });

    $(".side-menu li").each(function() {
        $(this)
            .parents("ul")
            .css("display", "block");
        $(this)
            .parents("ul")
            .next(".dropdown-append")
            .addClass("dropdown-open");
    });

    $(".side-menu .active").each(function() {
        $(this)
            .parents("ul")
            .css("display", "block");
        $(this)
            .parents("ul")
            .next(".dropdown-append")
            .addClass("dropdown-open");
    });

    $(".dropdown-append").on("click", function() {
        $(this)
            .prev("ul")
            .slideToggle(300);
        $(this).toggleClass("dropdown-open");
    });
});




// jQuery for Jump to Top

$(window).scroll(function() {
    if ($(this).scrollTop()) {
        $("#top").fadeIn();
    } else {
        $("#top").fadeOut();
    }
});

$("#top").click(function() {
    $("html, body").animate({ scrollTop: 0 }, 500);
});




// Quantity

// function customQuantity() {
//     /** Custom Input number increment js **/
//     jQuery(
//         '<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>'
//     ).insertAfter(".quantity input");
//     jQuery(".quantity").each(function() {
//         var spinner = jQuery(this),
//             input = spinner.find('input[type="number"]'),
//             btnUp = spinner.find(".quantity-up"),
//             btnDown = spinner.find(".quantity-down"),
//             min = input.attr("min"),
//             max = input.attr("max"),
//             valOfAmout = input.val(),
//             newVal = 0;

//         btnUp.on("click", function() {
//             var varholder = input.val();
//             var oldValue = parseFloat(input.val());

//             if (varholder === "") {
//                 var newVal = 1;
//             } else {
//                 if (oldValue >= max) {
//                     var newVal = oldValue;
//                 } else {
//                     var newVal = oldValue + 1;
//                 }
//             }
//             spinner.find("input").val(newVal);
//             spinner.find("input").trigger("change");
//         });
//         btnDown.on("click", function() {
//             var varholder = input.val();
//             var oldValue = parseFloat(input.val());

//             if (varholder === "") {
//                 var newVal = 1;
//             } else {
//                 if (oldValue <= min) {
//                     var newVal = oldValue;
//                 } else {
//                     var newVal = oldValue - 1;
//                 }
//             }
//             spinner.find("input").val(newVal);
//             spinner.find("input").trigger("change");
//         });
//     });
// }
// customQuantity();

// jQuery for Removing Product on Cart

$('a.remove').click(function() {
    event.preventDefault();
    $(this).parent().parent().hide(400);
});




// jQuery for closing Listing Filter Wrap

$(".rd-navbar-listing-close-toggle").on("click", function() {
    $(".listing-filter-wrap").removeClass("active");
});