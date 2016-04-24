define(function (require, exports, module) {

    var img     = require('../plugin/img_gallery.js');

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
        brand:      $('.sb-send-time'),
        subUrl:     $('#submitUrl'),
        subBtn:     $('.publish-submit'),

        tip:        $('.img-tip'),
        gallery:    $('.img-gallery'),
        galleryS:   $('.img-shadow'),
        imgBox:     $('.img-box'),
        addImg:     $('.add-img'),
        selImg:     $('.selected-img'),

    };
    var pageData = {
        tid:        $('.step2-box').data('tid')
    };

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
    //显示图档集
    function showImgItem(){
        img.getImgList();
        $dom.gallery.show();
        $dom.galleryS.show();
    }
    //插入圖片
    function insertImg(option){
        if($('.img-item').not('.has-img').length){
            var img = '<img src="'+option.url+'"><a href="javascript:;" class="del-img" data-id="'+option.id+'">刪除</a>';
            $('.img-item').not('.has-img').eq(0).append(img).addClass('has-img');
            collectImg();
        }else{
            alert('圖檔已插滿');
        }
    }
    //刪除圖片
    function delImg(id){
        $('.del-img[data-id='+id+']').parent().removeClass('has-img').empty();
        collectImg();
    }
    //圖片數據
    var imgJson = [];
    //收集圖片數據
    function collectImg(){
        imgJson = [];
        $('.img-item').each(function(i){
            var $this = $(this);
            if($this.hasClass('has-img')){
                imgJson.push($this.find('.del-img').data('id'));
            }
        });
        submitData.images = imgJson+'';
        console.log(submitData)
    }
    //提交data
    var submitData ={
        brand:'11',
        title:'',
        price:'',
        stock:'',
        images:'',
        detail:'',
        type:pageData.tid
    };
    //整合数据
    function s2submit(){
    submitData.detail = $dom.content.val();
        for(var i in submitData){
            if(!submitData[i]){
                break;
                alert('信息填写不完整');
                return false;
            }
        }
        $.ajax({
            type:       'post',
            url:        $dom.subUrl.val(),
            dataType:   'json',
            data:       submitData,
            success:    function(info){
                console.log(info)
            }
        });
    }
    //选择品牌
    $dom.brand.change(function(){
        var $this = $(this);
        if($this.val()){
            submitData.brand = $this.val();
        }else{
            submitData.brand = '';
        }
    });
    //輸入標題
    $dom.title.keyup(function(){
        var $this = $(this);
        tleSize($this.val());
        submitData.title = $this.val();
    });
    //價格輸入
    $dom.price.keyup(function(){
        var $this = $(this);
        var $tip  = $this.parent().siblings('.ub-tip');
        var val   = $this.val().replace(/\D/g,'');

        $this.val(val);
        if(checkPrice(val)){
            $tip.hide();
            submitData.price = val;
        }else{
            $tip.show();
        }
    });
    //填入库存
    $dom.kucun.keyup(function(){
        var $this = $(this);
        submitData.stock = $this.val();
    });
    //添加圖檔
    $dom.addImg.click(function(){
        addImg();
    });
    //圖檔+号
    $dom.imgBox.on('click','.img-item',function(){
        showImgItem();
    });
    //图档集插入
    var denyRepeat  = '';
    var showTip     = '';
    $dom.gallery.on('click','.g-c-sel',function(){
        var option = img.getImg($(this));
        if(denyRepeat != option.id){
            insertImg(option);
            denyRepeat = option.id;
            clearTimeout(showTip);
            $dom.tip.fadeIn('fast');
            showTip  = setTimeout(function(){
                $dom.tip.fadeOut('fast');
            },800);
            
        }else{
            alert('请勿重复插入')
        }
    });
    // $dom.imgBox.on('dbclick','.g-l-imgbox',function(e){
    //     var $this  = $(this);
    //     var $sel   = $this.parent().find('.g-c-sel')
    //     var option = img.getImg($sel);
    //     insertImg(option);
    // });
    //圖檔刪除
    $dom.imgBox.on('click','.del-img',function(e){
        e.stopPropagation();
        var $this = $(this);
        var i = $this.data('id');
        delImg(i);
    });
    //提交按钮
    $dom.subBtn.click(function(){
        s2submit();
    });
    //初始化
    $(function(){

    });
});