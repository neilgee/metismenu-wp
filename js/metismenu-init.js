
jQuery(document).ready(function($) {
          $(metismenuVars.ng_metis.ng_metismenu_selection).metisMenu({
            toggle: metismenuVars.ng_metis.ng_metismenu_toggle,
            activeClass: 'active',
            collapseClass: 'collapse',
            collapseInClass: 'in',
         //   doubleTapToGo: true,
            preventDefault: false

          });
        //add in a unique class to add some default styling from Navgoco
        $(metismenuVars.ng_metis.ng_metismenu_selection).addClass( "metismenu" );
        //add a span tag for toggle markup
        $( ".metismenu li > a" ).append( "<span></span>" );
    });




