// @module Slick
// @description Enables Slick Plugin

(function ($) {
  var o = $(".slick-slider");
  if (o.length > 0) {
    $(window).on("load", function () {
      setTimeout(function () {
        $("#preloader").fadeOut("slow");
        $("#load").fadeOut("slow");
        $("#pre-loader").fadeOut("slow");
      }, 100);
    });
  }
})(jQuery);




// @description FadeOut to Next Page

$(document).ready(function () {
  $(document).on("click", "a", function (e) {
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

    setTimeout(function () {
      $("html").fadeOut(function () {
        window.location = href;
      });
    });
  });
});




// jQuery for page scrolling feature - requires jQuery Easing plugin

$(function () {
  $(".navbar-nav li a").bind("click", function (event) {
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
  $(".page-scroll a").bind("click", function (event) {
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

$(function () {
  var navikMenuListDropdown = $(".side-menu ul li:has(ul)");

  navikMenuListDropdown.each(function () {
    $(this).append('<span class="dropdown-append"></span>');
  });

  $(".side-menu li").each(function () {
    $(this)
      .parents("ul")
      .css("display", "block");
    $(this)
      .parents("ul")
      .next(".dropdown-append")
      .addClass("dropdown-open");
  });

  $(".side-menu .active").each(function () {
    $(this)
      .parents("ul")
      .css("display", "block");
    $(this)
      .parents("ul")
      .next(".dropdown-append")
      .addClass("dropdown-open");
  });

  $(".dropdown-append").on("click", function () {
    $(this)
      .prev("ul")
      .slideToggle(300);
    $(this).toggleClass("dropdown-open");
  });
});




// jQuery for Jump to Top

$(window).scroll(function () {
  if ($(this).scrollTop() > $(".rd-navbar").height()) {
    $(".side-widget").fadeIn();
  } else {
    $(".side-widget").fadeOut();
  }
});

$("#top").click(function () {
  $("html, body").animate({ scrollTop: 0 }, 500);
});




// Quantity

function customQuantity() {
  /** Custom Input number increment js **/
  jQuery(
    '<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>'
  ).insertAfter(".quantity input");
  jQuery(".quantity").each(function () {
    var spinner = jQuery(this),
      input = spinner.find('input[type="number"]'),
      btnUp = spinner.find(".quantity-up"),
      btnDown = spinner.find(".quantity-down"),
      min = input.attr("min"),
      max = input.attr("max"),
      valOfAmout = input.val(),
      newVal = 0;

    btnUp.on("click", function () {
      var varholder = input.val();
      var oldValue = parseFloat(input.val());

      if(varholder === "") {
        var newVal = 1;
      } else {
        if (oldValue >= max) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue + 1;
        }
      }
      spinner.find("input").val(newVal);
      spinner.find("input").trigger("change");
    });
    btnDown.on("click", function () {
      var varholder = input.val();
      var oldValue = parseFloat(input.val());

      if (varholder === "") {
        var newVal = 1;
      } else {
        if (oldValue <= min) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue - 1;
        }
      }
      spinner.find("input").val(newVal);
      spinner.find("input").trigger("change");
    });
  });
}
customQuantity();

// jQuery for Removing Product on Cart

$('a.remove').click(function () {
  event.preventDefault();
  $(this).parent().parent().hide(400);
});




// jQuery for closing Listing Filter Wrap

$(".rd-navbar-listing-close-toggle").on("click", function () {
  $(".listing-filter-wrap").removeClass("active");
});




// jQuery for closing Listing Filter Wrap

$(".rd-navbar-shopping-cart-close-toggle").on("click", function () {
  $(".shopping-cart-wrap").removeClass("active");
});




// jQuery for Adding Z Index Navbar Dropdown

$(document).ready(function () {
  $count = $(".rd-navbar-dropdown").length;
  for (var $i = 0; $i < $count; $i++) {
    $(".rd-navbar-dropdown:eq(" + $i + ")").css("z-index", $i);
  }
});

// jQuery for Sponsor Option

$(".with-sponsor").on("click", function() {
  $(".sponsor-details").css("display", "block");
  $(".sponsor-details").addClass("fadeIn");
});

$(".no-sponsor").on("click", function () {
  $(".sponsor-details").css("display", "");
});



$(document).ready(function () {
  setInterval(function(){
    if(document.getElementById("shopping-cart-wrap").scrollHeight > $(".shopping-cart-wrap").height()) {
      $(".shopping-cart-btn").addClass("pos-sticky");
    } else {
      $(".shopping-cart-btn").removeClass("pos-sticky");
    }
  }, 500);
});