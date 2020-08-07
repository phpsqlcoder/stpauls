// @function      Include
// @description   Includes an external scripts to the page
// @param         {string} scriptUrl

function include(scriptUrl) {
  $("body").append('<script src="' + scriptUrl + '"></script>');
}

// @module Navik Menu
// @description Enables Navik Menu Plugin

(function($) {
  var o = $(".navik-menu");
  if (o.length > 0) {
    include("plugins/navik/navik.menu.js");
  }
})(jQuery);

// @module RD Navbar
// @description Enables RD Navbar Plugin

(function($) {
  var o = $(".rd-navbar");
  if (o.length > 0) {
    include("plugins/rd-navbar/dist/js/jquery.rd-navbar.min.js");

    $(document).ready(function() {
      o.RDNavbar({
        stickUpClone: false,
        stickUpOffset: 170
      });
    });
  }
})(jQuery);

// @module Owl Carousel
// @description Enables Owl Carousel Plugin

(function($) {
  var o = $(".owl-carousel");
  if (o.length > 0) {
    include("plugins/owl.carousel/owl.carousel.extension.js");
    include("plugins/owl.carousel/owl.carousel.js");
    $(window).on("load", function() {
      $("#preloader")
        .delay(100)
        .fadeOut("slow");
      $("#load")
        .delay(100)
        .fadeOut("slow");
    });
  }
})(jQuery);

// @module Magnific Popup
// @description Enables Magnific Popup Plugin

(function($) {
  var o = $(".zoom-gallery");
  if (o.length > 0) {
    include("plugins/magnific-popup/jquery.magnific-popup.min.js");

    $(document).on("click", "a.product-zoom", function(e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      return false;
    });

    $(document).on("click", "a.popup-youtube", function(e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      return false;
    });

    $(document).ready(function() {
      $("script[src*='magnific-popup']").after(
        "<script type='text/javascript'>$('.zoom-gallery').each(function() { $(this).magnificPopup({ delegate: 'a.product-zoom', type: 'image', gallery: { enabled: true } }); });" +
          "var yPopup = $('.popup-youtube');if (yPopup.length) {yPopup.magnificPopup({disableOn: 700,type: 'iframe',mainClass: 'mfp-fade',removalDelay: 160,preloader: false,fixedContentPos: false});}</script>"
      );
    });
  }
})(jQuery);

// // @module Bootstrap
// // @description Enables Bootstrap Plugin

(function($) {
  var o = $("link");
  if (o.length > 0) {
    for (let i = 0; i < o.length; i++) {
      var attr = o[i].getAttribute("href");
      var patt = /bootstrap/i;
      var result = attr.match(patt);
      if (result) {
        include("plugins/bootstrap/js/bootstrap.min.js");
      }
    }
  }
})(jQuery);

// @module AOS
// @description Enables AOS Plugin

(function($) {
  var o = $("link");
  if (o.length > 0) {
    for (let i = 0; i < o.length; i++) {
      var attr = o[i].getAttribute("href");
      var patt = /aos/i;
      var result = attr.match(patt);
      if (result) {
        include("plugins/aos/dist/aos.js");
        $(document).ready(function() {
          $("script[src*='aos']").after("<script type='text/javascript'>AOS.init();</script>");
        });
      }
    }
  }
})(jQuery);

// @module WOW
// @description Enables WOW Plugin

(function($) {
  var o = $(".wow");
  if (o.length > 0) {
    include("plugins/wow/dist/wow.min.js");
    $(document).ready(function() {
      $("script[src*='wow']").after("<script>new WOW().init();</script>");
    });
  }
})(jQuery);

// @module JS Social
// @description Enables JS Social Plugin

(function($) {
  var o = $("link");
  if (o.length > 0) {
    for (let i = 0; i < o.length; i++) {
      var attr = o[i].getAttribute("href");
      var patt = /jssocials/i;
      var result = attr.match(patt);
      if (result) {
        include("plugins/jssocials/jssocials.min.js");
        $(document).ready(function() {
          $("script[src*='jssocials']").after(
            "<script type='text/javascript'>$('#article-social').jsSocials({showLabel: false,showCount: false,shares: ['twitter', 'facebook', 'googleplus', 'linkedin', 'pinterest']});</script>"
          );
        });
      }
    }
  }
})(jQuery);

// @module Materialize
// @description Enables Materialize Plugin for Parallax

(function($) {
  var o = $(".parallax");
  if (o.length > 0) {
    include("plugins/materialize/js/materialize.js");
    $(document).ready(function() {
      $(".parallax").parallax();
    });
  }
})(jQuery);

// @module Preloader
// @description Set timer for preloader

var timerStart = Date.now();

$(window).on("load", function() {
  var preloader = $(".preloader");
  var timerSet = Date.now() - timerStart;

  if (preloader.length) {
    setTimeout(function() {
      preloader.addClass("loaded");
    }, timerSet);
  }
});

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
      .animate(
        {
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
      .animate(
        {
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
