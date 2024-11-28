$(document).ready(function(){
    $('.slider-brands').slick({
        infinite: true,
        slidesToShow: 7,
        slidesToScroll: 1,
        dots:false,
        arrows:true,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
                centerMode: true,
                slidesToShow: 2,
                slidesToScroll: 1,
            }
          }
          // You can unslick at a given breakpoint now by adding:
          // settings: "unslick"
          // instead of a settings object
        ]
    });
})
$(document).ready(function(){
    $('.slider-latest').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        dots:false,
        arrows:true,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1,
            }
          },
          {
            breakpoint: 900,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
            }
          }
        ]
    });

})
$(document).ready(function(){
    $('.img-db-list').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots:true,
        arrows:false,
    });

})
$(document).ready(function(){
    $('.customer-slider').slick({
      autoplay:true,
      delay:4000,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots:false,
        arrows:true,
    });

})


$(document).ready(function() {
    // Initially, show only 4 items
    var itemsToShow = 8;
    showItems(itemsToShow);

    // // Show all items on "See More" click
    // $('.car-rental-section .cr-box .see-more').on('click', function() {
    //   $('.car-rental-section .cr-box .loader').show(); // Show loader
    //   setTimeout(function() {
    //     showAllItems();
    //     $('.car-rental-section .cr-box .loader').hide(); // Hide loader after 2 seconds
    //   }, 2000);
    // });

    // Function to show a specific number of items
    function showItems(num) {
      $('.car-rental-section .cr-box .items').slice(0, num).show();
    }

    // Function to show all items
    function showAllItems() {
      $('.car-rental-section .cr-box .items').show();
      $('.car-rental-section .cr-box .see-more').hide(); // Hide "See More" link after showing all items
    }

    // menu

    $(".mobile-btn").click(function () {
      $("header .mainheader .flex").slideToggle();
  });

  // Ensure .flex is always displayed as flex on desktop
  $(window).resize(function () {
      if ($(window).width() >= 1024) {
          $("header .mainheader .flex").css("display", "flex");
      } else {
          $("header .mainheader .flex").css("display", "");
      }
  });

  $("header .topheader .rightsection>div.dropdown a").click(function () {
    $(".langdd").toggleClass("display-flex"); // Toggle the 'display-flex' class
});


// dashboard

$(document).ready(function () {
//   $(".tablinks").click(function () {
//       var cityName = $(this).data("city");

//       $(".tabcontent").hide();
//       $(".tablinks").removeClass("active");

//       $("#" + cityName).show();
//       $(this).addClass("active");
//   });

  // file upload

  $(".addCarImagesBox").click(function(){
    $("#image-upload").click();
})
});

  });

//   jQuery(document).ready(function( $ ) {
//     $('.counting').counterUp({
//         delay: 10,
//         time: 1000
//     });
// });

$(document).ready(function() {
    $('.slider-product').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      fade: true,
      asNavFor: '.slider-product-small'
    });
    $('.slider-product-small').slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      asNavFor: '.slider-product',
      dots: false,
      focusOnSelect: true
    });

  })



$(document).ready(function() {
  // Select toggle buttons
  var toggleButtons = $('.toggle-password');

  // Toggle password visibility
  toggleButtons.on('click', function() {
    // Get the target ID from the data attribute
    var targetId = $(this).data('target');
    var targetInput = $('#' + targetId);

    var type = targetInput.attr('type') === 'password' ? 'text' : 'password';
    targetInput.attr('type', type);

    // Change the image based on the password visibility
    var imagePath = type === 'password' ? './img/view.png' : './img/hide.png';
    $(this).find('img').attr('src', imagePath);
  });
});
