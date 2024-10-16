$(function () {
  "use strict"; 
  $("#main-wrapper").AdminSettings({
    Theme: localStorage.getItem('theme-skin') || false, // this can be true or false ( true means dark and false means light ),
    Layout: "vertical",
    LogoBg: localStorage.getItem('logo-skin') || "skin1", // You can change the Value to be skin1/skin2/skin3/skin4/skin5/skin6
    NavbarBg: localStorage.getItem('navbar-skin') || 'skin1', // You can change the Value to be skin1/skin2/skin3/skin4/skin5/skin6
    SidebarType: "full", // You can change it full / mini-sidebar / iconbar / overlay
    SidebarColor: localStorage.getItem('sidebar-skin') || "skin6", // You can change the Value to be skin1/skin2/skin3/skin4/skin5/skin6
    SidebarPosition: true, // it can be true / false ( true means Fixed and false means absolute )
    HeaderPosition: true, // it can be true / false ( true means Fixed and false means absolute )
    BoxedLayout: false, // it can be true / false ( true means Boxed and false means Fluid )
  });
});
