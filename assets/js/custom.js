$(document).ready(function() {
    // $(".add_to_cart").unbind('click');
    $(".add_to_cart").click(function() {
        var product_id = $(this).data('product_id');
        $('.product_id').val(product_id);
        $("#add_to_cart").submit();
    });

    $("#card_number").mask("0000 0000 0000 0000");
    $("#cvv").mask("000");
    $("#expdate").mask("00/00");

    $(".sub-category, #myDropdown").on("mouseenter", function() {
        $("#myDropdown").addClass("show");    
    });
    $(".sub-category, #myDropdown").on("mouseleave", function() {
        $("#myDropdown").removeClass("show");    
    });
});