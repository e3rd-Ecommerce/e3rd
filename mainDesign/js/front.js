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
    