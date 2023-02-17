
// // Live Search
// // Take element from html

// var keyword = document.querySelector("#searchBar");
// var loadingGif = document.querySelector("#loading");
// var container = document.querySelector("#container");

// // Add event to searchbar
// keyword.addEventListener('keyup',function(){

//     // Create ajax object
//     var xhr = new XMLHttpRequest();

//     // Check, ajax ready or no
//     xhr.onreadystatechange = function(){

//         // readystate == 4 means ajax ready, status == 200 means page ok
//         if(xhr.readyState ==4 && xhr.status == 200){
//             container.innerHTML = xhr.responseText;
//         } 
//     }

//     // Execute ajax
//     xhr.open("GET","../php/ajax.php?keyword="+keyword.value,true);
//     xhr.send();
// });


// Live search use Jquery

$(document).ready(function(){

    // Add event "keyup" on searchbarindex
    $('#searchBarIndex').on('keyup',function(){
        // Show loader gif
        $('#loading').show();
        
        $.get('./assets/php/displayData.php?keyword=' + $('#searchBarIndex').val() + '&page=index',function(data){
            $('#containerIndex').html(data);
            $('#loading').hide();
        });
    });
    
    // Add event "keyup" on searchbaradmin
    $('#searchBarAdmin').on('keyup',function(){
        // Show loader gif
        $('#loading').show();

        $.get('./displayData.php?keyword=' + $('#searchBarAdmin').val() + '&page=admin',function(data){
            $('#containerAdmin').html(data);
            $('#loading').hide();
        });
    });   
});

// Add to cart button
function addToCart(id) {
    $.get('./assets/php/shoppingCart.php?idProduct=' + id +"&action=addToCart", function(data){
        $(".shoppingCart").html(data);
    });
}

// Min item button
function minItem(id){
    $.get('./assets/php/shoppingCart.php?idProduct=' + id +"&action=minBtn", function(data){
        $(".shoppingCart").html(data);
    });
}

// Plus item button
function plusItem(id){
    $.get('./assets/php/shoppingCart.php?idProduct=' + id +"&action=plusBtn", function(data){
        $(".shoppingCart").html(data);
    });
}

// Manual update when user write in textbox
function manualUpdate(id){

    idBox = "#quantity"+id;

    $.get('./assets/php/shoppingCart.php?idProduct=' + id +"&action=manualAdd" + "&quantity=" + ($(idBox).val()), function(data){
        $(".shoppingCart").html(data);
    });
}

// Dete item button
function deleteItm(id){

    $.get('./assets/php/shoppingCart.php?idProduct=' + id +"&action=deleteItm", function(data){
        $(".shoppingCart").html(data);
    });
}

// Reload Shopping Cart when it printed
function printing(){

    window.open("./assets/php/print.php","_blank");
    
    setTimeout(() => {
        $.get('./assets/php/shoppingCart.php?action=printSPC', function(data){
            $(".shoppingCart").html(data);
        });
        $.get('./assets/php/displayData.php?keyword=' + $('#searchBarIndex').val() + '&page=index', function(data){
            $('#containerIndex').html(data);
        });
    }, 1000);
}

// Function additional discount
function discount(id){
    
    idBox = "#discountItem"+id;

    $.get('./assets/php/shoppingCart.php?idProduct=' + id +"&action=discount" + "&discount=" + ($(idBox).val()), function(data){
        $(".shoppingCart").html(data);
    });
}

// Function clear shopping cart
function ClearCart(){
    $.get('./assets/php/shoppingCart.php?action=printSPC', function(data){
        $(".shoppingCart").html(data);
    });
}

// Funtion auto format number
function formatNumber(id){
    idBox = "#"+id
    var values = $(idBox).val();
    var values = values.replaceAll(",","");
    var newVal = new Intl.NumberFormat('en-US').format(values);
    if(newVal == "NaN"){
        newVal = values.replace(/.$/,"")
        var rawVal = newVal;
        var newVal = new Intl.NumberFormat('en-US').format(newVal);
        while(newVal == "NaN"){
            newVal = rawVal.replace(/.$/,"")
            rawVal = newVal
            var newVal = new Intl.NumberFormat('en-US').format(newVal);
        }
    }
    $(idBox).val(newVal);
}

// Function customername
function customer (){
    
    $.get('./assets/php/shoppingCart.php?action=customer&customer='+$("#customer").val(), function(data){
    $(".shoppingCart").html(data)
    });
}


