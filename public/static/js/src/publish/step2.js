define(function (require, exports, module) {

    var gamelist= require('game');
        img     = require('../plugin/img_gallery.js');

    var $dom = { 
        selServer:  $('.sb-server'),
        yxbNum:     $('.yxb-num'),
        yxbUnit:    $('input[name=currency_unit]'),
        price:      $('.sb-price'),
        aCount:     $('.sb-auto-count'), //幣值計算结果栏
        aTle:       $('.sb-auto-tle'),
        tle:        $('.sb-tle'),
        title:      $('.sb-title'),
        tleNum:     $('.now-tle-num'),
        kucun:      $('.sb-num'),
        content:    $('.sb-content'),
        sendTime:   $('.sb-send-time'),
        subBtn:     $('.publish-submit'),

        gallery:    $('.img-gallery'),
        galleryS:   $('.img-shadow'),
        imgBox:     $('.img-box'),
        addImg:     $('.add-img'),
        selImg:     $('.selected-img')

    };
    var val = {
        gid:        $('.step2-box').data('gid'),
        sid:        $('.step2-box').data('sid'),
        tid:        $('.step2-box').data('tid')
    };
    var option = '';
    for(var i in gamelist){
        if(gamelist[i]['id'] == val.gid){
            for(var k in gamelist[i]['servers']){
                if(gamelist[i]['servers'][k]['id'] == val.sid){
                    option += '<option value="'+gamelist[i]['servers'][k]['id']+'" selected>'+gamelist[i]['servers'][k]['name']+'</option>';
                }else{
                    option += '<option value="'+gamelist[i]['servers'][k]['id']+'">'+gamelist[i]['servers'][k]['name']+'</option>';
                }
            }
        }
    }

    //提交data
    var submitData ={};
    //獲取服務器
    function getServer(){
        $dom.selServer.html(option)
    }
    //標題長度
    function tleSize(tle){
        var tlength = tle.length;
        $dom.tleNum.html(tlength+'字');
    }
    //價格判斷
    function checkPrice(price){
        var flag = 0;
        if(price>=10 && price<=200000){
            flag = 1;
        }
        return flag;
    }
    //遊戲幣數量判斷
    function checkYxbNum(){
        var flag = 0;
        if($dom.yxbNum.val()){
            flag = 1
        }
        return flag;
    }
    //幣值計算
    function countYxb(){
        var result      = '',
            yxb         = $dom.yxbNum.val()*1,
            unitCheck   = $('input[name=currency_unit]:checked').val();
            unitTxt     = $('input[name=currency_unit][value='+unitCheck+']').data('txt'),
            unitVal     = $('input[name=currency_unit][value='+unitCheck+']').data('unit')*1,
            price       = $dom.price.val()*1;

        if(yxb&&price&&unitCheck){
            result      = (yxb / price).toFixed(2);
            var str     = '<span class="sunshine-txt">1元='+result+unitTxt+'</span>';
            var data    = {
                yxb:     yxb,
                unitTxt: unitTxt,
                price:   price,
                type:    1
            };
            $dom.aCount.html(str);
            tleFill(data);
        }
    }
    //自動標題
    function tleFill(option){
        if(option.type == 1){ //遊戲幣
            var str = option.yxb + option.unitTxt+'='+option.price+'元';
            $dom.aTle.val(str);
        }else if(option.type == 2){
            //其他
        }
    }
    //添加更多
    function addImg(){
        var itemNum = $('.img-item').length;
        var html    = '';
        if(itemNum<15){
            var a = 5-(itemNum % 5);
            for(var i=1;i<=a;i++){
                html += '<div class="img-item"></div>';
            }
            $dom.imgBox.append(html);
        }
        if($('.img-item').length == 15){
            $dom.addImg.hide();
        }
    }
    //獲取圖片數據
    var imgVal  = $dom.selImg.val().split(',');
    var imgJson = {}; 
    for(var i = 0;i<imgVal.length;i++){
        imgJson[i] = imgVal[i];
    }
    //插入圖片
    function insertImg(url){
        if($('.img-item').not('.has-img').length){
            var img = '<img src="'+url+'"><a href="javascript:;" class="del-img">刪除</a>';
            $('.img-item').not('.has-img').eq(0).append(img).addClass('has-img');
            collectImg();
        }else{
            alert('圖檔已插滿');
        }
    }
    //刪除圖片
    function delImg(id){
        imgJson[id] = '';
        $('.img-item').eq(id).removeClass('has-img').empty();
        collectImg();
    }
    //收集圖片數據
    function collectImg(){
        $('.img-item').each(function(i){
            var $this = $(this);
            if($this.hasClass('has-img')){
                imgJson[i] = $this.find('img').attr('src');
            }else{
                imgJson[i] = '';
            }
        });
        var data = '';
        for(var i in imgJson){
            if(imgJson[i]){
                data += imgJson[i]+',';
            }
        }
        $dom.selImg.val(data);
    }
    //輸入標題
    $dom.title.keyup(function(){
        var $this = $(this);
        tleSize($this.val());
    });
    $dom.tle.keyup(function(){
        var $this = $(this);
        tleSize($this.val());
    });
    //價格輸入
    $dom.price.keyup(function(){
        var $this = $(this);
        var $tip  = $this.parent().siblings('.ub-tip');

        var val   = $this.val().replace(/\D/g,'');
        $this.val(val);

        if(checkPrice($this.val())){
            $tip.hide();
            countYxb();
        }else{
            $tip.show();
        }
    });
    //幣值輸入
    $dom.yxbNum.keyup(function(){
        countYxb();
    });
    $dom.yxbUnit.change(function(){
        countYxb();
    });
    //添加圖檔
    $dom.addImg.click(function(){
        addImg();
    });
    //圖檔+号
    $dom.imgBox.on('click','.img-item',function(){
        $dom.gallery.show();
        $dom.galleryS.show();
    });
    //图档集插入
    $dom.gallery.on('click','.g-c-sel',function(){
        var url = img.getImg($(this));
        insertImg(url);
    });
    //圖檔刪除
    $dom.imgBox.on('click','.del-img',function(e){
        e.stopPropagation();
        var $this = $(this);
        var i = $this.parent().index();
        delImg(i);
    });
    //初始化
    $(function(){
        getServer();
    });
});