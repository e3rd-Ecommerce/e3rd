$(function (){

    'use strict';

    // switch between login & sigunup

    $('.login-page h1 span').click(function(){

        $(this).addClass('selected').siblings().removeClass('selected');

        $('.login-page form').hide(); // بخفي الكلاس الي بكبس عليه
        $( '.' + $(this).data('class')).fadeIn(100);  // بعرض الكلاس البكبس عليه وبخفي الثاني 


    });

    // trigger the selectboxit
    $("select").selectBoxIt
    ({
        autoWidth: false  ,

        // Uses the jQuery 'fadeIn' effect when opening the drop down
        showEffect: "fadeIn",
    
        // Sets the jQuery 'fadeIn' effect speed to 400 milleseconds
        showEffectSpeed: 400,
    
        // Uses the jQuery 'fadeOut' effect when closing the drop down
        hideEffect: "fadeOut",
    
        // Sets the jQuery 'fadeOut' effect speed to 400 milleseconds
        hideEffectSpeed: 400
    });



    //Hide Plasce Holder on Form Focus
    $('[placeholder]').focus(function(){

        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function(){

        $(this).attr('placeholder',$(this).attr('data-text'));
    });

    //Add Astrisk On Requierd Field
    // $('input').each(function() {

    //     if($(this).attr('required') === 'required'){
    //         $(this).after('<span class="asterisk">*</span>')
    //     }

    // });

    
    // رسالة تأكيد على الزر  

    $('.confirm').click(function (){
        return confirm('are you sure?');

    });

    // create live ad 

    $('.live-name').keyup(function(){
        $('.live-preview .caption h3').text($(this).val())

    });

    $('.live-decs').keyup(function(){
        $('.live-preview .caption p').text($(this).val())

    });

    $('.live-price').keyup(function(){
        $('.live-preview  .price-tag').text('$' + $(this).val())

    });



});
