$(document).ready(function() {
    $('.my-select').select2();
    var unitPrice = 0;
    var itemQty = 0;

    function updateSubtotal() {
        var subtotal = unitPrice * itemQty;
        $('#subtotal_display').text(subtotal.toFixed(2)); // Update the subtotal display
    }

    $('#item_name').change(function() {
        var itemName = $(this).val();
        if(itemName) {
            $.ajax({
                url: 'user_autocomple.php', // The server-side script to call
                type: 'POST',
                data: {item_name: itemName},
                dataType: 'json',
                success: function(data) {
                    if(data.success) {
                        unitPrice = parseFloat(data.unit_price); // Update the unit price
                        $('#unit_price_display').text(unitPrice.toFixed(2)); // Update the span with the price
                        $('#current_qty').text(current_qty);
                    } else {
                        unitPrice = 0;
                        $('#unit_price_display').text('Price not available');
                    }
                    updateSubtotal(); // Update the subtotal whenever the unit price changes
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    $('#unit_price_display').text('Failed to retrieve price');
                }
            });
        } else {
            unitPrice = 0;
            $('#unit_price_display').text('0'); // Reset the price if no item is selected
            updateSubtotal(); // Update the subtotal whenever the unit price changes
        }
    });

    $('input[name="item_qty"]').on('input', function() {
        itemQty = $(this).val() ? parseInt($(this).val(), 10) : 0; // Update the item quantity
        updateSubtotal(); // Update the subtotal whenever the item quantity changes
    });
    $(document).on('click', '.decr', function() {
    var $qty_box = $(this).closest('.qty_box');
    var $qty_input = $qty_box.find('.quantityInput');
    var current_qty = parseInt($qty_input.val());

    if (!isNaN(current_qty) && current_qty > 1) {
        $qty_input.val(current_qty - 1).trigger('input');
    }
});

$(document).on('click', '.incr', function() {
    var $qty_box = $(this).closest('.qty_box');
    var $qty_input = $qty_box.find('.quantityInput');
    var current_qty = parseInt($qty_input.val());

    if (!isNaN(current_qty)) {
        $qty_input.val(current_qty + 1).trigger('input');
    }
});

$('input.quantityInput').on('input', function() {
    var $row = $(this).closest('tr');
    var price = parseFloat($row.find('td:eq(2)').text());
    var quantity = parseInt($(this).val());
    var subtotal = price * quantity;
    $row.find('td:eq(4)').text(subtotal.toFixed(2)); // Assuming this is the correct selector for the subtotal
});

// You may need to trigger the input event on page load to update the subtotals
$('input.quantityInput').trigger('input');


function calculateTotal() {
    var total = 0;
    $('td[id="order_subtotal"]').each(function() {
        total += parseFloat($(this).text()) || 0;
    });
    $('h1.text-end b').text( total.toFixed(2));
}

// Update the total when the quantity is changed
$(document).on('input', '.quantityInput', function() {
    calculateTotal();
});

// Update the total when an item is removed
$(document).on('click', 'a.btn-danger', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.get(url, function() {
        // Assuming the server-side script returns the updated session items
        // You might need to adjust this to reload the page or update the table with the new data
        location.reload();
    });
});

// Initial calculation on page load
calculateTotal();
});