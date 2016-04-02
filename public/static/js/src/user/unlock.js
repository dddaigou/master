define(function (require, exports, module) {
    var $dom = {
        phone:      $('.phone'),
        phoneCode:  $('.phone-code'),
        send:       $('.ub-send'),
        submit:     $('.ub-submit')
    };
    //手機格式
    function checkPhone(phone){
        var flag      = 0;
        var testPhone = /^1\d{10}$/;
        if(testPhone.test(phone)){
            flag = 1;
        }else{
            alert('手機號碼格式不正確');
        }
        return flag;
    }
    //提交
    function unlockSubmit(){
        if(!($dom.phone.val() && $dom.phoneCode.val())){
            alert('請填寫完整');
            return false;
        }
        var data = {
            mobile: $dom.phone.val(),
            code:   $dom.phoneCode.val(),
            type:   'unlock'
        };
        $.ajax({
            url:        '/User/checkUnlock',
            data:       data,
            dataType:   'json',
            type:       'post',
            success:    function(info){
                if(info.code == 200){
                    location.href = "/"
                }else{
                    alert(info.msg)
                }
            }
        });
    }
    //發送驗證碼
    $dom.send.click(function(){
        var $this = $(this);
        if($this.hasClass('hk-disabled-btn')) return false;

        if(checkPhone($dom.phone.val())){ 
            var data = {
                mobile: $dom.phone.val(),
                type:   'unlock'
            };
            $.ajax({
                url:        '/User/sendOtp',
                data:       data,
                dataType:   'json',
                type:       'post',
                success:    function(info){
                    if(info.code == '200'){
                        $this.removeClass('hk-orange-btn').addClass('hk-disabled-btn').html('已發送驗證碼 (<span>10</span>秒)');
                        $dom.phoneCode.prop('disabled',false);
                        var time  = 10;
                        var timer = setInterval(function(){
                            $this.find('span').html(--time);
                            if(!time){
                                $this.removeClass('hk-disabled-btn').addClass('hk-orange-btn').html('發送驗證碼');
                                clearInterval(timer);
                            }
                        },1000);
                    }else{
                        alert(info.msg)
                    }
                }
            });
        }
    });
    //提交按鈕
    $dom.submit.click(function(){
        unlockSubmit();
    });
    $dom.phoneCode.keyup(function(e){
        if(e.keyCode==13) unlockSubmit();
    });
    
});