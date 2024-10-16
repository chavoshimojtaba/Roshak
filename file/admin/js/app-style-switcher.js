$(function () {
  "use strict"; 
  
  function handlelogobg() {
    $(".logo-theme .theme-item .theme-link").on("click", function () {
      var logobgskin = $(this).attr("data-logobg"); 
      $(".topbar .top-navbar .navbar-header").attr("data-logobg", logobgskin); 
      localStorage.setItem('logo-skin',logobgskin);
    });
  }

  handlelogobg();
  
  function handlenavbarbg() {
    if ($("#main-wrapper").attr("data-navbarbg") == "skin6") { 
      $(".topbar .navbar").addClass("navbar-light");
      $(".topbar .navbar").removeClass("navbar-dark");
    }
    $(".navbar-theme .theme-item .theme-link").on("click", function () {
      var navbarbgskin = $(this).attr("data-navbarbg");
      $("#main-wrapper").attr("data-navbarbg", navbarbgskin);
      $(".topbar").attr("data-navbarbg", navbarbgskin);
      $(".topbar .navbar-collapse").attr("data-navbarbg", navbarbgskin);
      localStorage.setItem('navbar-skin',navbarbgskin); 
      if ($("#main-wrapper").attr("data-navbarbg") == "skin6") { 
        $(".topbar .navbar").addClass("navbar-light");
        $(".topbar .navbar").removeClass("navbar-dark");
      } else {
        // do that
        $(".topbar .navbar").removeClass("navbar-light");
        $(".topbar .navbar").addClass("navbar-dark");
      }
    });
  }

  handlenavbarbg();
  
  function handlesidebartype() {}
  handlesidebartype();
  
  function handlesidebarbg() {
    $(".sidebar-theme .theme-item .theme-link").on("click", function () {
      var sidebarbgskin = $(this).attr("data-sidebarbg");
      $(".left-sidebar").attr("data-sidebarbg", sidebarbgskin);
      localStorage.setItem('sidebar-skin',sidebarbgskin);
    });
  }

  handlesidebarbg();
  
  function handlesidebarposition() {
    $("#sidebar-position").change(function () {
      if ($(this).is(":checked")) {
        $("#main-wrapper").attr("data-sidebar-position", "fixed");
        $(".topbar .top-navbar .navbar-header").attr("data-navheader", "fixed");
      } else {
        $("#main-wrapper").attr("data-sidebar-position", "absolute");
        $(".topbar .top-navbar .navbar-header").attr(
          "data-navheader",
          "relative"
        );
      }
    });
  }

  handlesidebarposition(); 

  function handleheaderposition() {
    $("#header-position").change(function () {
      if ($(this).is(":checked")) {
        $("#main-wrapper").attr("data-header-position", "fixed");
      } else {
        $("#main-wrapper").attr("data-header-position", "relative");
      }
    });
  }

  handleheaderposition(); 

  function handleboxedlayout() {
    $("#boxed-layout").change(function () {
      if ($(this).is(":checked")) {
        $("#main-wrapper").attr("data-boxed-layout", "boxed");
      } else {
        $("#main-wrapper").attr("data-boxed-layout", "full");
      }
    });
  }

  handleboxedlayout(); 

  function handlethemeview() {
    $("#theme-view").change(function () {
      console.log('theme-skin');
      if ($(this).is(":checked")) {
        $("body").attr("data-theme", "dark");
        localStorage.setItem('theme-skin',1); 
      } else {
        $("body").attr("data-theme", "light");
        localStorage.setItem('theme-skin',0);
      }
    });
  }
  handlethemeview();
});
