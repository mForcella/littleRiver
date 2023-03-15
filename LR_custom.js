jQuery(document).ready(function ($) {
  "use strict";

  $('.single_add_to_cart_button').click(function (e) {
    e.preventDefault();
    var id = $(this).next().next().attr('value');
    var data = {
      product_id: id,
      quantity: 1
    };
    $(this).parent().addClass('loading');
    $.post(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'), data, function (response) {

      if (!response) {
        return;
      }
      if (response.error) {
        alert("Custom Massage ");
        $('.single_add_to_cart_button').parent().removeClass('loading');
        return;
      }
      if (response) {

        var url = woocommerce_params.wc_ajax_url;
        url = url.replace("%%endpoint%%", "get_refreshed_fragments");
        $.post(url, function (data, status) {
          $(".woocommerce.widget_shopping_cart").html(data.fragments["div.widget_shopping_cart_content"]);
          if (data.fragments) {
            jQuery.each(data.fragments, function (key, value) {

              jQuery(key).replaceWith(value);
            });
          }
          jQuery("body").trigger("wc_fragments_refreshed");
        });
        $('.single_add_to_cart_button').parent().removeClass('loading');

      }

    });

 });
});