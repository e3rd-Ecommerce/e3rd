$(function (){

    'use strict';

    //Hide Plasce Holder on Form Focus
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
});
