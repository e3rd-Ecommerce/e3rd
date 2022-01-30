// to add product to your cart
function AddToCart(product_id ,user_id) {
    if (product_id > 0){
        let s = document.getElementById('cart').innerText;
        s++;
        document.getElementById('cart').innerHTML = s;
        let from_data = new FormData;
        let process = "AddToCart";
        from_data.append('process',process);
        from_data.append('item_id',product_id);
        from_data.append('user_id',user_id);
        const xhttp = new XMLHttpRequest();
        document.getElementById(product_id).style.display ='none';
        //document.getElementsById(name_prodcur).style.display ='block !important';
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //alert(this.responseText);
            }
        };
        xhttp.open("POST","././servesCart.php",true);
        xhttp.send(from_data);  
    } 
}

//to change number the product
function onch(product_id,user_id,name_product,price_product) {
    
    let from_data = new FormData;
    let process = "number_product";
    from_data.append('process',process);
    from_data.append('prodect_id',product_id);
    from_data.append('user_id',user_id);
    from_data.append('name_prodcur',name_product.value);
    document.getElementById(product_id).innerHTML = 
                totalPrice(name_product.value,price_product);

        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //alert(this.responseText);
                document.getElementById('total').
                innerHTML = this.responseText;
            }
        };
        xhttp.open("POST","././servesCart.php",true);
        xhttp.send(from_data);
}

//function that return the amount product
function totalPrice(number_product,price_product) {
    return number_product * price_product;
}

function deleteCart(user_id) {
    let from_data = new FormData;
    let process = "deleteCart";
    from_data.append('process',process);
    from_data.append('user_id',user_id);
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //alert(this.responseText);
            }
        };
        xhttp.open("POST","././servesCart.php",true);
        xhttp.send(from_data);
        location.reload();
        //window.location("index.php"); 
}

