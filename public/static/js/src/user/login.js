define(function (require, exports, module) {
    var ls   = require('../tool/localStorage.js');

    var $dom = {
        loginForm:  $('#login_form'),
        msg:        $('#ulMsg'),
        account:    $('input[name=account]'),
        psw:        $('input[name=password]'),
        codeImg:    $('.lgn-vcode'),
        changeCode: $('.change-vcode'),
        saveId:     $('#saved'),

        codeLine:   $('.lgn-vcode-line'),
        lgnTip:     $('.lgn-tip')
    };
    //刷新驗證碼
    function freshCode(){
        var src = '/verify/index.html?v='+Math.random();
        $dom.codeImg.attr('src',src);
    }
    //記住帳號
    function setAccount(){
        if($dom.saveId.prop('checked')){
            ls.set('lgnAccount',$dom.account.val());
        }else{
            ls.remove('lgnAccount');
        }
    }
    //讀取帳號
    function getAccount(){
        var account = ls.get('lgnAccount');
        if(account){
            $dom.account.val(account);
            $dom.saveId.prop('checked',true);
        }
    }
    //提交表單
	$dom.loginForm.submit(function(){
        var url     = $(this).attr('action');
        var method  = $(this).attr('method');
        var data    = $(this).serialize();
        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: function(info){
                if(info.code == 200){
                    setAccount();
                    window.location.href = info.url;
                }else if(info.code == 500){
                    window.location.href = '/user/unlock';
                }else if(info.code == 404||info.code == 403){ //密碼錯誤
                    $dom.msg.addClass('hk-error-msg').html(info.msg);
                    freshCode();
                    $dom.codeLine.show();
                    $dom.lgnTip.show();
                }else{ //其他錯誤
                    $dom.msg.addClass('hk-error-msg').html(info.msg);
                }
                return false;
            }
        });
        return false;
    });
    //點擊換碼
    $dom.codeImg.click(function(){
        freshCode();
    });
    $dom.changeCode.click(function(){
        freshCode();
    });
    //初始化
    $(function(){
        getAccount();
    });

});