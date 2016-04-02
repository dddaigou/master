/**
 * @name 頭部公共文件
 * @memberOf plugin
 * @version 1.0.0
 * @author: 8591
 */
define(function (require, exports, module){
    var gamelist = require('game');

    var allGame = '<li>===所有遊戲===</li>',
        hotGame = '<li class="g-hot">===熱門遊戲===</li>',
        newGame = '<li class="g-new">===最新遊戲===</li>';
    
    var $dom = {
        hsInput:    $('.hs-input'),
        gval:       $('.g-val'),
        sval:       $('.s-val'),
        tval:       $('.t-val'),
        kInput:     $('#keywordInput'),
        sSubmit:    $('.hs-submit'),
        topIcon:    $('.top-icon'),

        gList:      $('.hs-game-list'),     
        sList:      $('.hs-server-list'),     
        tList:      $('.hs-type-list'),
        hsList:     $('.hs-list')
    };

    for(var i in gamelist){
        if(gamelist[i]['is_new'] == '1'){
            newGame += '<li class="g-new" data-id="'+gamelist[i]['id']+'" title="'+gamelist[i]['name']+'">'+gamelist[i]['name']+'</li>';
        }
        if(gamelist[i]['is_hot'] == '1'){
            hotGame += '<li class="g-hot" data-id="'+gamelist[i]['id']+'" title="'+gamelist[i]['name']+'">'+gamelist[i]['name']+'</li>';
        }
        allGame += '<li data-id="'+gamelist[i]['id']+'" title="'+gamelist[i]['name']+'">'+gamelist[i]['name']+'</li>';
    }
    $dom.gList.html(newGame + hotGame + allGame);

    //獲取伺服器列表
    function getSlist(gid){
        var server  = '<li>全部伺服器</li>';
        if(gid){
            for(var i in gamelist){
                if(gamelist[i]['id'] == gid){
                    for(var k in gamelist[i]['servers']){
                        server += '<li data-id="'+gamelist[i]['servers'][k]['id']+'" title="'+gamelist[i]['servers'][k]['name']+'">'+gamelist[i]['servers'][k]['name']+'</li>';
                    }
                    break;
                }
            }
        }
        return server;
    }
    function setSer(gid){
        $dom.sList.html(getSlist(gid));
        $dom.sval.val('').data('val','');
    }

    //獲取種類列表
    function getTlist(gid){
        var type    = '<li>全部種類</li>';
        if(gid){
            for(var i in gamelist){
                if(gamelist[i]['id'] == gid){
                    for(var k in gamelist[i]['types']){
                        type += '<li data-id="'+k+'" title="'+gamelist[i]['types'][k]+'">'+gamelist[i]['types'][k]+'</li>';
                    }
                    break;
                }
            }
        }
        return type
    }
    function setType(gid){
        $dom.tList.html(getTlist(gid));
        $dom.tval.val('').data('val','');
    }

    //遊戲搜索
    function searchGame(gName){
        var sflag       = 0,
            gameSearch  = '<li>==搜尋結果如下==</li>';
            
        if(gName == ''){
            $dom.gList.html(newGame + hotGame + allGame);
        }else{
            regName = new RegExp(gName,"gi");
            for(var i in gamelist){
                if(gamelist[i]['name'].match(regName)){
                    gameSearch += '<li data-id='+gamelist[i]['id']+' title="'+gamelist[i]['name']+'">'+gamelist[i]['name']+'</li>';
                    sflag = 1;
                }
            }
            if(!sflag){  //假如遊戲沒被搜索
                var notFound = "<li>很抱歉沒有搜索到“<span style='color:#f60;'>"+
                                gName+"</span>”相關遊戲。"+
                            "</li>";
                gameSearch += notFound;
            }
            //添加進遊戲列表
            $dom.gList.html(gameSearch);
        }
    }

    //列表顯示
    var hidden = ''; //時間標誌
    $dom.hsInput.on('focus click',function(){
        var $this = $(this);
        var $icon = $this.siblings('.top-icon');
        var $list = $this.siblings('.hs-list');
        clearTimeout(hidden);
        $dom.hsList.hide();
        $dom.topIcon.hide();
        $list.show();
        $icon.show();
    });
    //點擊區域外隱藏
    $(document).click(function(e){
        $dom.hsList.hide();
        $dom.topIcon.hide();
    });
    $(document).on('click','.hs-select',function(e){
        e.stopPropagation();
    });
    $dom.hsList.hover(function(){
        clearTimeout(hidden);
    },function(){
        var $this = $(this);
        var $icon = $this.siblings('.top-icon');
        hidden = setTimeout(function(){
            $this.hide();
            $icon.hide();
        },800);
    });

    //選中遊戲
    $dom.gList.on('click','li',function(){
        var $this   = $(this);
        var $mom    = $this.parent();
        var $icon   = $mom.siblings('.top-icon');
        var gid     = $this.data('id') ? $this.data('id') : '';
        var gName   = $this.attr('title') ? $this.attr('title') : '';
        
        $dom.gval.data('val',gid).val(gName);
        setSer(gid);
        setType(gid);
        if(gid){
            $('.s-val').focus();
        }
    });
    
    //選中伺服器
    $dom.sList.on('click','li',function(){
        var $this   = $(this);
        var $mom    = $this.parent();
        var $icon   = $mom.siblings('.top-icon');
        var sid     = $this.data('id') ? $this.data('id') : '';
        var sName   = $this.attr('title') ? $this.attr('title') : '';
        
        $dom.sval.data('val',sid).val(sName);
        $dom.hsList.hide();
        $dom.topIcon.hide();
    });
    
    //選中種類
    $dom.tList.on('click','li',function(){
        var $this   = $(this);
        var $mom    = $this.parent();
        var $icon   = $mom.siblings('.top-icon');
        var tid     = $this.data('id') ? $this.data('id') : '';
        var tName   = $this.attr('title') ? $this.attr('title') : '';
        
        $dom.tval.data('val',tid).val(tName);
        $dom.hsList.hide();
        $dom.topIcon.hide();
    });

    //輸入遊戲名
    $dom.gval.focus(function(){
        $(this).select();
    }).keyup(function(){
        var $this = $(this);
        var gName = $this.val();
        searchGame(gName)
    });
});