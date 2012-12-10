$(document).ready(function() {
  var menus = ["newcomparo", "newgroup"];
  currentMenu = 1;
  
  for ( var i = 0; i < menus.length; i++ ) {
    if ( (i+1) == currentMenu ) {
      $("#"+menus[i]).css("display", "block");
      $("#menu-"+menus[i]).addClass("selected");
    }
    else {
      $("#"+menus[i]).css("display", "none");
      $("#menu-"+menus[i]).removeClass("selected");
    }
    eval("$(\"#menu-\"+menus["+i+"]).click(function(){toggleMenu("+(i+1)+"); return false;})");
  }

  function toggleMenu(menu) {  
    if ( menu != currentMenu ) {
      if ( currentMenu > 0 ) {
        $("#"+menus[currentMenu-1]).hide("blind");
        $("#menu-"+menus[currentMenu-1]).removeClass("selected");
      }
      $("#"+menus[menu-1]).show("blind");
      $("#menu-"+menus[menu-1]).addClass("selected");
      currentMenu = menu;
    }
  }
});

