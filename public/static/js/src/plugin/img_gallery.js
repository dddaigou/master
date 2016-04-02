/**
 * @name 圖檔集
 * @memberOf plugin
 * @version 1.0.0
 * @author: 10482
 */
define(function(require, exports, module) {
    var $dom = {
        uploadBtn:      $('#uploadBtn'),
        uploadIframe:   $('#uploadIframe'),
        uploadForm:     $('.gallery-up'),
        imgList:        $('.gallery-list'),
        bigBox:         $('.img-gallery'),
        shadow:         $('.img-shadow'),
        close:          $('.gallery-close'),
        tip:            $('.img-tip')
    };
    var showTip = '';
    //獲取圖檔列表
    exports.url = 
    exports.getImgList = function(){
        $.ajax({

        });
    }
    //獲取圖片
    exports.getImg = function($ele){
        var link = $ele.data('link');
        clearTimeout(showTip);
        $dom.tip.fadeIn('fast');
        showTip  = setTimeout(function(){
            $dom.tip.fadeOut('fast');
        },800)
        return link;
    }
    //回調函數
    exports.onUploadSuccess = function(data){
        // 返回格式错误
        if(typeof data!='object') return;

        if(data.status == 200){
            var url   = 'http://image.dev.8591.com.hk/'+data.url;
            var html  = '<div class="g-l-item">'+
                        '<div class="g-l-imgbox"><img src="'+url+'"></div>'+
                        '<p>'+
                        '<a href="javascript:;" class="g-c-sel" data-link="'+url+'">選擇</a>'+
                        '<a href="javascript:;" class="g-c-del" data-id="'+data.id+'">刪除</a>'+
                        '</p></div>';
            $dom.imgList.prepend(html);
        }
    }
    window.onUploadSuccess = exports.onUploadSuccess;

    //上傳按鈕
    $dom.uploadBtn.change(function(){
        $dom.uploadForm.submit();
    });
    //close按鈕
    $dom.close.click(function(){
        $dom.bigBox.hide();
        $dom.shadow.hide();
    });
});