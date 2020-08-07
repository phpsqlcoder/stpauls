// @module Slick
// @description Enables Slick Plugin

$(window).on("load", function () {
  setTimeout(function () {
    $("#preloader").fadeOut("slow");
    $("#load").fadeOut("slow");
    $("#pre-loader").fadeOut("slow");
  }, 100);
});

// @description FadeOut to Next Page
/*$(document).ready(function() {
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
    if (href.indexOf("images/") == 0) {
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
});*/

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
  if ($(this).scrollTop() > $(".rd-navbar").height()) {
    $(".side-widget").fadeIn();
  } else {
    $(".side-widget").fadeOut();
  }
});

$("#top").click(function() {
  $("html, body").animate({ scrollTop: 0 }, 500);
});

// jQuery for Adding Z Index Navbar Dropdown
$(document).ready(function() {
  $count = $(".rd-navbar-dropdown").length;
  for (var $i = 0; $i < $count; $i++) {
    $(".rd-navbar-dropdown:eq(" + $i + ")").css("z-index", $i);
  }
});

// jQuery for closing RD Search Wrap
$(".rd-navbar-close-search__toggle").on("click", function () {
  $(".rd-navbar-search-wrap").removeClass("active");
});

// jQuery for closing Listing Filter Wrap

$(".rd-navbar-listing-close-toggle").on("click", function() {
  $(".listing-filter-wrap").removeClass("active");
});

// Script for Countdown
(function ($) {
  var o = $("ul#countdown");
  if (o.length > 0) {
    const second = 1000,
      minute = second * 60,
      hour = minute * 60,
      day = hour * 24;

    let newDateTime = document.getElementById("countdown").getAttribute("data-date-time");
    let countDown = new Date(newDateTime).getTime(),
      x = setInterval(function () {

        let now = new Date().getTime(),
          distance = countDown - now;

        document.getElementById('days').innerText = Math.floor(distance / (day)),
          document.getElementById('hours').innerText = Math.floor((distance % (day)) / (hour)),
          document.getElementById('minutes').innerText = Math.floor((distance % (hour)) / (minute)),
          document.getElementById('seconds').innerText = Math.floor((distance % (minute)) / second);

        //do something later when date is reached
        //if (distance < 0) {
        //  clearInterval(x);
        //  'IT'S MY BIRTHDAY!;
        //}

      }, second);
  }
})(jQuery);

// Input Type Number
$("input[type='number']").keydown(function() {
  return event.keyCode == 69 ||
    event.keyCode == 187 ||
    event.keyCode == 189 ||
    event.keyCode == 190
    ? false
    : true;
});


// Quantity
function customQuantity() {
  /** Custom Input number increment js **/
  jQuery(
    '<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>'
  ).insertAfter(".quantity input");
  jQuery(".quantity").each(function() {
    var spinner = jQuery(this),
      input = spinner.find('input[type="number"]'),
      btnUp = spinner.find(".quantity-up"),
      btnDown = spinner.find(".quantity-down"),
      min = input.attr("min"),
      max = input.attr("max"),
      valOfAmout = input.val(),
      newVal = 0;

    btnUp.on("click", function() {
      var varholder = input.val();
      var oldValue = parseFloat(input.val());

      if (varholder === "") {
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
    btnDown.on("click", function() {
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


// product quantity
$('.btn-number').click(function(e){
	e.preventDefault();

	fieldName = $(this).attr('data-field');
	type      = $(this).attr('data-type');
	var input = $("input[name='"+fieldName+"']");
	var currentVal = parseInt(input.val());
	if (!isNaN(currentVal)) {
		if(type == 'minus') {

			if(currentVal > input.attr('min')) {
				input.val(currentVal - 1).change();
			} 
			if(parseInt(input.val()) == input.attr('min')) {
				$(this).attr('disabled', true);
			}

		} else if(type == 'plus') {

			if(currentVal < input.attr('max')) {
				input.val(currentVal + 1).change();
			}
			if(parseInt(input.val()) == input.attr('max')) {
				$(this).attr('disabled', true);
			}

		}
	} else {
		input.val(0);
	}
});
$('.input-number').focusin(function(){
   $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function() {

	minValue =  parseInt($(this).attr('min'));
	maxValue =  parseInt($(this).attr('max'));
	valueCurrent = parseInt($(this).val());

	name = $(this).attr('name');
	if(valueCurrent >= minValue) {
		$(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
	} else {
		alert('Sorry, the minimum value was reached');
		$(this).val($(this).data('oldValue'));
	}
	if(valueCurrent <= maxValue) {
		$(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
	} else {
		alert('Sorry. Currently, there is no sufficient stocks for the item you wish to order');
		$(this).val($(this).data('oldValue'));
	}
});


// product category list slide down
$(".catalog-top span, .quick-nav span").click(function() {
  $(this).siblings("ul").stop(true).slideToggle();
  $(this).toggleClass("default rotate");
});


// catalog menu
/* Set the width of the side navigation to 250px */
function openNav() {
  document.getElementById("mySidenav").style.left = "0";
  $(".dark-curtain").fadeIn();
}
/* Set the width of the side navigation to 0 */
function closeNav() {
  document.getElementById("mySidenav").style.left = "-300px";
	$(".dark-curtain").fadeOut();
} 
function myFunction(x) {
  if (x.matches) { // If media query matches
    $(".sidenav").append($(".catalog-category, .quick-nav"));
  } else {
    $(".desk-cat").append($(".sidenav .catalog-category"));
    $(".desk-cat").append($(".sidenav .quick-nav"));
  }
}
var x = window.matchMedia("(max-width: 900px)")
myFunction(x) 
x.addListener(myFunction)

// forms validation
$("#btnSubmit").click(function(event) {
// Fetch form to apply custom Bootstrap validation
var form = $("#myForm")
if (form[0].checkValidity() === false) {
  event.preventDefault()
  event.stopPropagation()
}
form.addClass('was-validated');
// Perform ajax submit here...
});
