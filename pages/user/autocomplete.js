// $( "#item_name" ).autocomplete({
//     source: function( request,response){
//         $.ajax({
//             url: "http://localhost/capstoneJoke/pages/user/rdu_autocomple.php",
//             type: "GET",
//             dataType: "json",
//            success: function( data ) {
//             aData = $.map(data, function(value, key) {
//                 return {
//                     id:value.id,
//                     label: value.item_name,
//                     price: value.price
//                 };
//             });
//             var results =$.ui.autocomplete.filter( aData, request.term );
//             response( results );
//            }
//         })

        
//     },
//     select: function(event, ui){
//         console.log(ui.item.price);
//         $('#price').text(ui.item.price);;
//     }
//   });

$(document).ready(function() {
    $('#item_name').change(function() {
        var itemName = $(this).val();
        if(itemName) {
            $.ajax({
                url: 'rdu_autocomple.php', // This is the PHP file that will return the price for the selected item
                type: 'POST',
                data: {item_name: itemName},
                dataType: 'json',
                success: function(data) {
                    if(data.success) {
                        $('#price').text(data.price);
                    } else {
                        $('#price').text('Price not available');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    $('#price').text('Failed to retrieve price');
                }
            });
        } else {
            $('#price').text(''); // Clear the price if no item is selected
        }
    });
});



