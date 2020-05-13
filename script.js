<script type="text/javascript">
    jQuery(document).ready(function($) {
        
        // open external links in new tab
        $('a').each(function() {
           var a = new RegExp('/' + window.location.host + '/');
           if(!a.test(this.href)) {
               $(this).click(function(event) {
                   event.preventDefault();
                   event.stopPropagation();
                   window.open(this.href, '_blank');
               });
           }
        });
    
        // adjust subpage-header top-margin on main-header resize
        $(window).on('resize', function () {
            var topMargin = $(window).width() < 1407 ? ($(window).width() < 981 ? "28px" : "54px") : "0px";
            $(".subpage-header").css("margin-top",topMargin);
            $("#content-area").css("margin-top",topMargin);
            $(".blog-index-top-post").css("margin-bottom","-"+topMargin);
        });
    
        $(window).trigger('resize');
        
        // check if user is admin, adjust header class
        if($('body').hasClass('logged-in')){
            $("#main-header").removeClass("non-admin");
        }
        
        // set map frame
        $("#map_frame").attr("src", "https://www.google.com/maps/d/embed?mid=1Sdu3m4-YMx_Kc0gWshTNQQgSFys&z=1");
    });
</script>