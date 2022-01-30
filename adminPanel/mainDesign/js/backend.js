//Admid Dashboard 'Navbar bttton'
let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".sidebarBtn");

sidebarBtn.onclick = function(){
    sidebar.classList.toggle("active");
}

$(function (){

    'use strict';

    //Trigger The SelectBoxIt Plugin
    $("select").selectBoxIt({

         autoWidth:false
    });

    //Dashboard Plus Icon in latest users
    $('.toggle-info').click(function () {

        $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(100);
        
        if($(this).hasClass('selected')){

            $(this).html('<i class="fa fa-minus fa-lg"></i>')
        
        } else {

            $(this).html('<i class="fa fa-plus fa-lg"></i>')
        }
    });


    //Hide Placeholder on Form Focus
    $('[placeholder]').focus(function(){

        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function(){

        $(this).attr('placeholder',$(this).attr('data-text'));
    });

    // //Add Astrisk On Requierd Field
    // $('input').each(function() {

    //     if($(this).attr('required') === 'required'){
    //         $(this).after('<span class="asterisk">*</span>')
    //     }

    // });

    //Confirmation Message On Button(Delete)
    $('.confirm').click(function () {
        
        return confirm('Are You Sure To Delete This?');
    });

    //Cteagory View Options
    $('.cat h3').click(function () {
        
        $(this).next('.full-view').fadeToggle(400);
    });


    $('.options span').click(function () {

        $(this).addClass('active').siblings('span').removeClass('active')
        
        if($(this).data('view') === 'full'){

            $('.cat .full-view').fadeIn(200);
            
        } else {
            
            $('.cat .full-view').fadeOut(200);
        }
    });


    //Show Delete Button on Hover on child cats
    $(".child-link").hover(function(){

        $(this).find('.show-delete').fadeIn(400);

    }, function(){

        $(this).find('.show-delete').fadeOut(400);
    });

});

$("nice-message").before("<p><b>Hello javatpoint.com</b></p>");  

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

