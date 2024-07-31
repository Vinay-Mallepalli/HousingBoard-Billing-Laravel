(function($) {
  'use strict';
  $(function() {
      var sidebar = $('.sidebar');

      // Add active class to nav-link based on url dynamically
      function addActiveClass(element) {
          var currentUrl = window.location.href;
          if (element.attr('href') === currentUrl) {
              element.addClass('active');
              element.parents('.nav-item').last().addClass('active');
              if (element.parents('.sub-menu').length) {
                  element.closest('.collapse').addClass('show');
              }
              if (element.parents('.submenu-item').length) {
                  element.addClass('active');
              }
          }
      }

      // For main sections
      $('.nav li a', sidebar).each(function() {
          var $this = $(this);
          addActiveClass($this);
      });

      // For select housing section
      $('#selectHousingSection li a').each(function() {
          var $this = $(this);
          addActiveClass($this);
      });

      // Close other submenus in sidebar on opening any
      sidebar.on('show.bs.collapse', '.collapse', function() {
          sidebar.find('.collapse.show').collapse('hide');
      });

      $('[data-toggle="minimize"]').on("click", function() {
          $('body').toggleClass('sidebar-icon-only');
      });

      $(".form-check label,.form-radio label").append('<i class="input-helper"></i>');
  });
})(jQuery);
