var kAddCommentButtonTitle = "评论";
var kSubmitCommentButtonTitle = "提交";

$(document).ready(function () {
    $('#comment_btn_wrapper').click(function () {
        if(showing_comment === 1){
            //submit comment
            trySubmitComment($('#comment_content').val());
        }else{
            $('#comment_form_area').slideDown();
            $('#cancel_comment_btn_wrapper').css("visibility", "visible");
            $("#comment_btn_wrapper .wrapped_btn").html(kSubmitCommentButtonTitle);
        }
    });

    $('#cancel_comment_btn_wrapper').click(function () {
        $(this).css("visibility", "hidden");
        $('#comment_form_area').slideUp();
        //TODO: clear form content
        $("#comment_btn_wrapper .wrapped_btn").html(kAddCommentButtonTitle);
    });
});

function trySubmitComment(){
    //TODO: submit the comment
}