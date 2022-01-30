
$(function (){

    'use strict';

    //Switch between Login & sign up
    $('.login-block h1 span').click(function () {
        
        $(this).addClass('selected').siblings().removeClass('selected');
        
        $('.login-page form').hide();

        $('.' + $(this).data('class')).fadeIn(100);
    
    });


    //Trigger The SelectBoxIt Plugin
    $("select").selectBoxIt({

        autoWidth:false
    });


    //Hide Plasce Holder on Form Focus
    $('[placeholder]').focus(function(){

        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function(){

        $(this).attr('placeholder',$(this).attr('data-text'));
    });

    //Add Astrisk On Requierd Field
    $('input').each(function() {

        if($(this).attr('required') === 'required'){
            $(this).after('<span class="asterisk">*</span>')
        }

    });

    //Confirmation Message On Button(Delete)
    $('.confirm').click(function () {
        
        return confirm('Are You Sure To Delete This?');
    });

    $(".live").keyup(function(){

        $($(this).data('class')).text($(this).val());
    });
});

function bigImg(x) {
    x.style.height = "34%";
    x.style.width = "34%";
}

function normalImg(x) {
    x.style.height = "30%";
    x.style.width = "30%";
}

function deleteimage(x) {
    let text = "Are you Sure To Delete This Image !\n      Either OK or Cancel.";
    if (confirm(text) == true) {
        //alert("You pressed OK!");
        let from_data = new FormData;
        from_data.append('imagePath',x);
        const xhttp = new XMLHttpRequest();
        
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //alert(this.responseText);
            }
        };
        xhttp.open("POST","deleteImage.php",true);
        xhttp.send(from_data);
        location.reload();

    } else {
        alert("You canceled!");
    }
}

function search() {
    let from_data = new FormData;
    let process = "search";
    from_data.append('process',process);
    from_data.append('cat_id',document.getElementById('cat_id').value);
    from_data.append('search_text',document.getElementById('search').value);
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //alert(this.responseText);
                document.getElementById('output').innerHTML = 
                    this.responseText;
            }
        };
        xhttp.open("POST","././search.php",true);
        xhttp.send(from_data);
}


// $(document).ready(function(){
//     $("#search").keypress(function(){
//       $.ajax({
//         type:'POST',
//         url:'././search.php',
//         data:{
//           name:$("#search").val(),
//         },
//         success:function(data){
//           $("#output").html(data);
//         }
//       });
//     });
//   });

    