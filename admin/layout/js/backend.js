$(function (){  

    'use strict';

    // dashboard

    $('.toggle-info').click(function(){
        $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(100);
        
        if($(this).hasClass('selected')){
            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        }else{
            $(this).html('<i class="fa fa-plus fa-lg"></i>');
        }

    });


    // trigger the selectboxit
    $("select").selectBoxIt
    ({
        autoWidth: false ,

        // Uses the jQuery 'fadeIn' effect when opening the drop down
        showEffect: "fadeIn" ,
    
        // Sets the jQuery 'fadeIn' effect speed to 400 milleseconds
        showEffectSpeed: 400 ,
    
        // Uses the jQuery 'fadeOut' effect when closing the drop down
        hideEffect: "fadeOut" ,
    
        // Sets the jQuery 'fadeOut' effect speed to 400 milleseconds
        hideEffectSpeed: 400
    });


    // Hide Plasce Holder on Form Focus
    $('[placeholder]').focus(function(){

        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function(){

        $(this).attr('placeholder',$(this).attr('data-text'));
    });


    // Add Astrisk On Requierd Field
    // $('input').each(function() {

    //     if($(this).attr('required') === 'required'){
    //         $(this).after('<span class="asterisk">*</span>')
    //     }

    // });


    // رسالة تأكيد على الزر  

    $('.confirm').click(function (){
        return confirm('are you sure?');

    });


    // category view option

    $('.cat h5').click(function(){

        $(this).next('.full-view').fadeToggle(200);

    });


    $('.option span').click(function(){
        $(this).addClass('active').siblings('span').removeClass('active');

        if($(this).data('view')=='full'){
            $(' .cat .full-view').fadeIn(200);
        }else{
            $(' .cat .full-view').fadeOut(200);
        }
    });


    // show delete button on child cats 

    $('.child-link').hover(function(){

        $(this).find('.show-delete').fadeIn(400) ;

    }, function() {

        $(this).find('.show-delete').fadeOut(400);



    });

    
});
