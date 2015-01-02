var kIllegalEmailFormatHint = "Illegal Email Format";
var kIllegalPasswordFormatHint = "Illegal Password Format";
var kPasswordNotMatchedHint = "Password Not Matched";
var kReturnToIndexTitle = "回到首页";
var kGoToLoginRegister = "登录/注册";

$(document).ready(function () {
    $('#switch_to_login_btn').click(function () {
        $('.lractions').removeClass('displayed');
        $('.login_step').addClass("displayed");
        $('.login_btn_grp').removeClass("slight_btn").removeClass("hilite_btn");
        $(this).removeClass("slight_btn").addClass("hilite_btn");
    });
    $('#switch_to_register_btn').click(function () {
        $('.lractions').removeClass('displayed');
        $('.register_step').addClass("displayed");
        $('.login_btn_grp').removeClass("slight_btn").removeClass("hilite_btn");
        $(this).removeClass("slight_btn").addClass("hilite_btn");
    });
    $('#register_next_btn').click(function () {
        if(checkRegisterForm()){
            tryRegister();
        }
    });
    $('#login_action_btn').click(function () {
        if(checkLoginForm()){
            tryLogin();
        }
    });
    $('#header_btn_wrapper').click(function(){
        if(showing === 1){
            //change to login
            showing = 2;
            $('#header_btn_wrapper span').html(kReturnToIndexTitle);
            $('.main_page_content_wrapper').removeClass("displayed");
            $('#login_register_page_content').addClass("displayed");
        }else{
            //change to first: 0
            showing = 1;
            $('#header_btn_wrapper span').html(kGoToLoginRegister);
            $('.main_page_content_wrapper').removeClass("displayed");
            $('#main_page_content').addClass("displayed");
        }
    });
});

function showHintByHintContent(selector, content){
    selector.css("display", "block").html(content);
}

function hideHintBySelector(selector){
    selector.css("display", "none");
}

function tryLogin(){

}

function checkLoginForm(){
    var result = true;
    if(isLegalEmail($('#login_email_hint').val())){
        hideHintBySelector($('#login_email_hint'));
    }else{
        showHintByHintContent($('#login_email_hint'), kIllegalEmailFormatHint);
        result = false;
    }

    if(isLegalPassword($('#login_password_hint').val())){
        hideHintBySelector($('#login_password_hint'));
    }else{
        showHintByHintContent($('#login_password_hint'), kIllegalPasswordFormatHint);
        result = false;
    }

    return result;
}

function tryRegister(){
    if(true){
        //if server returns yes
        //store password in base64 or sth in js
        $('.lractions').removeClass('active');
        $('.register_step2').addClass("active");
    }
}

function checkRegisterForm(){
    var result = true;
    if(isLegalEmail($('#register_mail').val())){
        hideHintBySelector($('#register_email_hint'));
    }else{
        showHintByHintContent($('#register_email_hint'), kIllegalEmailFormatHint);
        result = false;
    }

    if(isLegalPassword($('#register_pwd').val())){
        hideHintBySelector($('#register_password_hint'));
    }else{
        showHintByHintContent($('#register_password_hint'), kIllegalPasswordFormatHint);
        result = false;
    }

    if($('#register_pwd').val() === $('#register_pwd_again').val()){
        hideHintBySelector($('#register_password_step2_hint'));
    }else{
        showHintByHintContent($('#register_password_step2_hint'), kPasswordNotMatchedHint);
        result = false;
    }
    return result;
}

function isLegalEmail(str){
    var reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    return reg.test(str);
}

function isLegalPassword(str){
    if(str.length > 12 || str.length < 6){
        return false;
    }
    return true;
}