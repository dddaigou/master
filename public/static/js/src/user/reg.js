define(function (require, exports, module) {
    var $dom = {
        phone:      $('.phone'),
        phoneCode:  $('.phone-code'),
        account:    $('.account'),
        psw:        $('.psw'),
        rePsw:      $('.re-psw'),
        name:       $('.name'),

        sendCode:   $('.ub-send'),
        agree:      $('#ubAgree'),
        aTip:       $('.ub-a-tip'),
        submit:     $('.ub-submit')
    };

    //提交data
    var submitData = {};
    //手機號格式
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
    //查驗證碼
    function checkCode(code){
        var $tip = $dom.phoneCode.parent().siblings('.ub-tip'),
            flag = 0;

        var data = {
            mobile:     $dom.phone.val(),
            code:       code,
            type:       'reg',
            check_only: 1
        };
        $.ajax({
            url:        '/User/checkOtp',
            data:       data,
            dataType:   'json',
            type:       'post',
            async:      false,
            success:    function(info){
                if(info.code == 200){
                    $tip.empty().html('<span class="ub-icon ub-s-icon"></span>填寫正確').show();
                    flag = 1;
                }else{
                    $tip.empty().html('<span class="ub-icon ub-e-icon"></span>'+info.msg).show();
                }
            }
        });
        return flag;
    }
    //查帳號
    function checkAcnt(acnt){
        var $tip = $dom.account.parent().siblings('.ub-tip');
        var flag = 0;

        var testAcnt = /^\w{6,12}$/;
        if(testAcnt.test(acnt)){
            var data ={
                account: $dom.account.val(),
                type:    'account'
            };
            $.ajax({
                data:       data,
                dataType:   'json',
                type:       'post',
                url:        '/User/validIsRegistered',
                async:      false,
                success:    function(info){
                    if(info.code == 200){
                        $tip.removeClass('ub-l-tip').empty().html('<span class="ub-icon ub-s-icon"></span>驗證通過').show();
                        flag = 1;
                    }else{
                        $tip.removeClass('ub-l-tip').empty().html('<span class="ub-icon ub-e-icon"></span>'+info.msg).show();
                    }
                }
            });
        }else{
            $tip.addClass('ub-l-tip').empty().html('<span class="ub-icon ub-e-icon"></span>必填項，長度為6-12字符，由字母、數字及_符號組成').show();
        }
        return flag;
    }
    //查密碼
    function checkPsw(psw){
        var $tip = $dom.psw.parent().siblings('.ub-tip');
        var flag = 0;
        var testPsw = /^\w{6,20}$/;
        if(testPsw.test(psw)){
            $tip.empty().html('<span class="ub-icon ub-s-icon"></span>填寫正確').show();
            $dom.rePsw.val('').prop('disabled',false);
            flag = 1;
        }else{
            $tip.empty().html('<span class="ub-icon ub-e-icon"></span>必填項，長度為6-20字符，字母區分大小寫').show();
            $dom.rePsw.val('').prop('disabled',true);
        }
        return flag;
    }
    function checkRepsw(psw){
        var $tip = $dom.rePsw.parent().siblings('.ub-tip');
        var flag = 0;
        var oPsw = $dom.psw.val();
        if(psw == oPsw && oPsw){
            $tip.empty().html('<span class="ub-icon ub-s-icon"></span>填寫正確').show();
            flag = 1;
        }else{
            $tip.empty().html('<span class="ub-icon ub-e-icon"></span>兩次填寫密碼不一致，請重新填寫！').show();
        }
        return flag;
    }
    function regSubmit(submit){
        var data = submit;
        $.ajax({
            data:       data,
            dataType:   'json',
            type:       'post',
            url:        '/User/reg',
            async:      false, 
            success:    function(info){
                console.log(info)
                if(info.code == 200){
                    location.href = info.url;
                }else{
                    alert(info.msg);
                    checkCode(data.code);
                }
            }
        });
    }
    //發送驗證碼
    $dom.sendCode.click(function(){
        var $this = $(this);
        if($this.hasClass('hk-disabled-btn')) return false;
        submitData.mobile = '';

        if(checkPhone($dom.phone.val())){ 
            var data = {
                mobile: $dom.phone.val(),
                type:   'reg'
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
                        submitData.mobile = data.mobile;
                    }else{
                        alert(info.msg)
                    }
                }
            });
        }
    });
    //填入驗證碼
    $dom.phoneCode.blur(function(){
        var $this = $(this);
        submitData.code = '';
        if($this.val().length >= 4){
            if(checkCode($this.val())){
                submitData.code = $this.val();
            }
        }
    });
    //填入帳號
    $dom.account.blur(function(){
        var $this = $(this);
        submitData.account = '';
        if(checkAcnt($this.val())){
            submitData.account = $this.val();
        }
    });
    //填入密碼
    $dom.psw.blur(function(){
        var $this = $(this);
        submitData.login_pwd = '';
        if(checkPsw($this.val())){
            submitData.login_pwd = $this.val();
        }
    });
    //填入2次密碼
    $dom.rePsw.blur(function(){
        var $this = $(this);
        submitData.login_pwd_confirm = '';
        if(checkRepsw($this.val())){
            submitData.login_pwd_confirm = $this.val();
        }
    });
    //填入姓名
    $dom.name.blur(function(){
        var $this = $(this);
        submitData.realname = '';
        if($this.val()){
            submitData.realname = $this.val();
        }
    });
    //同意條款
    $dom.agree.change(function(){
        if($(this).prop('checked')){
            $dom.aTip.hide();
        }else{
            $dom.aTip.show();
        }
    });
    //提交按鈕
    $dom.submit.click(function(){
        if($dom.agree.prop('checked')){
            regSubmit(submitData);
        }else{
            $dom.aTip.show();
        }
    });
    
});