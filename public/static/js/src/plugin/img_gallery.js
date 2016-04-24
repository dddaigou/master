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
        reqUrl:         $('#reqUrl'),
        httpUrl:        $('#imgHttpUrl')
    };
    //html填充图片
    function fillImg(id,url){
        var http  = $dom.httpUrl.val();
        var link  = http+'/'+url;
        var html  = '<div class="g-l-item">'+
                    '<div class="g-l-imgbox"><img src="'+link+'"></div>'+
                    '<p>'+
                    '<a href="javascript:;" class="g-c-sel" data-id="'+id+'" data-link="'+link+'">選擇</a> '+
                    '<a href="javascript:;" class="g-c-del" data-id="'+id+'">刪除</a>'+
                    '</p></div>';
        return html;       
    }
    //獲取圖檔列表
    exports.getImgList = function(){
        var html = '';
        if(!$dom.imgList.html()){
            $.ajax({
                url: $dom.reqUrl.val(),
                dataType:'json',
                type:'get',
                success:function(info){
                    console.log(info)
                    if(info.length){
                        for(var i in info){
                            html += fillImg(info[i]['id'],info[i]['path']);
                        }
                        $dom.imgList.prepend(html);
                    }
                }
            });
        }
    }
    //獲取圖片
    exports.getImg = function($ele){
        var option = {
            id:     $ele.data('id'),
            url:    $ele.data('link')
        }
        return option;
    }
    //回調函數
    exports.onUploadSuccess = function(data){
        // 返回格式错误
        if(typeof data!='object') return;

        if(data.code == 200){
            $dom.imgList.prepend(fillImg(data.id,data.url));
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