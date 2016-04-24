define(function (require, exports, module) {
    var goodType = require('goodType');

    var goodItem  = '<li class="g-tle g-pc">商品分类</li>',
        mobGame = '<li class="g-tle g-mob">手機遊戲</li>',
        webGame = '<li class="g-tle g-web">網頁遊戲</li>';

    var $dom = {
        glist:      $('.g-box-ul'),
        slist:      $('.s-box-ul'),
        tlist:      $('.t-box-ul'),
        srhBox:     $('.g-box-input'),
        srhInput:   $('.g-search'),
        navGame:    $('.tag-game'),
        navServer:  $('.tag-server'),
        navType:    $('.tag-type'),

        subForm:    $('#submitForm'),
        subGid:     $('input[name="game_id"]'),
        subSid:     $('input[name="server_id"]'),
        subTid:     $('input[name="type"]'),

        subBtn:     $('.sb-submit')
    };
    var val = {
        gid:        $('.step1-box').data('gid'),
        sid:        $('.step1-box').data('sid'),
        tid:        $('.step1-box').data('tid')
    };
    for(var i in goodType){
        //if(goodType[i]['type'] == 'pc'){
        goodItem += '<li class="sel-item" data-id="'+goodType[i]['id']+'" title="'+goodType[i]['name']+'">'+goodType[i]['name']+'<i></i></li>';
        // }else if(goodType[i]['type'] == 'mobile'){
        //     mobGame += '<li class="sel-item" data-id="'+goodType[i]['id']+'" title="'+goodType[i]['name']+'">'+goodType[i]['name']+'<i></i></li>';
        // }else if(goodType[i]['type'] == 'web'){
        //     webGame += '<li class="sel-item" data-id="'+goodType[i]['id']+'" title="'+goodType[i]['name']+'">'+goodType[i]['name']+'<i></i></li>';
        // }
    }
    //console.log(goodType)
    //提交data
    var submitData ={
        game_id:    '',
        server_id:  '',
        type:       ''
    };
    //new submit data
    var newSubmit = {

    }
    //重置數據
    function resetData(gid){
        submitData.game_id      = gid;
        submitData.server_id    = '';
        submitData.type         = '';
    }
    //設置遊戲
    function setGame(gid){
        $dom.glist.html(goodItem);
    }
    //設置伺服器
    function setServer(gid){
        var severList = '';

        for(var i in goodType){
            if(goodType[i]['id'] == gid){
                for(var k in goodType[i]['subType']){
                    severList += '<li class="sel-item" data-id="'+goodType[i]['subType'][k]['id']+'" title="'+goodType[i]['subType'][k]['name']+'">'+goodType[i]['subType'][k]['name']+'<i></i></li>';
                }
                break;
            }
        }
        $dom.slist.html(severList);
    }
    //設置種類
    function setType(gid,sid){
        var typeList = '';
        for(var i in goodType){
            if(goodType[i]['id'] == gid){

                for(var k in goodType[i]['subType']){
                    if(goodType[i]['subType'][k]['id'] == sid){

                        for(var j in goodType[i]['subType'][k]['subType']){
                            typeList += '<li class="sel-item no-next" data-id="'+goodType[i]['subType'][k]['subType'][j]['id']+'" title="'+goodType[i]['subType'][k]['subType'][j]['name']+'">'+goodType[i]['subType'][k]['subType'][j]['name']+'<i></i></li>';
                        }
                        break;
                    }
                }
                break;
            }
        }
        $dom.tlist.html(typeList);
    }
    // //遊戲搜索
    // function searchGame(gName){
    //     var sflag       = 0,
    //         gameSearch  = '';
            
    //     if(gName == ''){
    //         setGame();
    //         $dom.slist.parent().hide();
    //         $dom.tlist.parent().hide();
    //         $dom.navGame.hide();
    //         $dom.navServer.hide();
    //         $dom.navType.hide();
    //     }else{
    //         regName = new RegExp(gName,"gi");
    //         for(var i in gamelist){
    //             if(gamelist[i]['name'].match(regName)){
    //                 gameSearch += '<li class="sel-item" data-id='+gamelist[i]['id']+' title="'+gamelist[i]['name']+'">'+gamelist[i]['name']+'<i></i></li>';
    //                 sflag = 1;
    //             }
    //         }
    //         if(!sflag){  //假如遊戲沒被搜索
    //             var notFound = "<li>很抱歉沒有搜索到“<span style='color:#f60;'>"+gName+"</span>”相關遊戲。</li>";
    //                 gameSearch += notFound;
    //         }
    //         //添加進遊戲列表
    //         $dom.glist.html(gameSearch);
    //     }
    // }
    //提交
    function s1submit(){
        //console.log(submitData)
        if(submitData.game_id && submitData.server_id && submitData.type){
            $dom.subGid.val(submitData.game_id);
            $dom.subSid.val(submitData.server_id);
            $dom.subTid.val(submitData.type);
            $dom.subForm.submit();
        }else{
            alert('請選擇完整')
        }
    }
    // //記錄預選
    // function getPreset(gid,sid){
    //     if(gid){
    //         $dom.glist.find('[data-id='+gid+']').trigger('click');
    //         $dom.slist.find('[data-id='+sid+']').trigger('click');
    //     }
    // }
    //搜索
    $dom.srhInput.focus(function(){
        $(this).select();
        $dom.srhBox.addClass('g-box-input-on');
    }).blur(function(){
        $dom.srhBox.removeClass('g-box-input-on');
    }).keyup(function(){
        var $this = $(this);
        searchGame($this.val());
    });
    //遊戲點擊
    $dom.glist.on('click','.sel-item',function(){
        var $this = $(this);
        var gid   = $this.data('id');
        var gn    = $this.attr('title');

        $dom.glist.find('.sel-item').removeClass('sel-active');
        $this.addClass('sel-active');
        $dom.tlist.parent().hide();
        $dom.navServer.hide();
        $dom.navType.hide();
        setServer(gid);
        submitData.game_id = gid;
        $dom.slist.parent().fadeIn('fast');
        $dom.navGame.html(gn).show();
        resetData(gid);
    });
    //伺服器點擊
    $dom.slist.on('click','.sel-item',function(){
        var $this   = $(this);
        var sid     = $this.data('id');
        var sn      = $this.attr('title');
        var pid     = $this.data('parent_id');
        setType(submitData.game_id,sid);
        $dom.slist.find('.sel-item').removeClass('sel-active');
        $this.addClass('sel-active');
        $dom.navServer.html('&gt; '+sn).show();
        $dom.tlist.parent().fadeIn('fast');
        submitData.server_id = sid;
    });
    //種類點擊
    $dom.tlist.on('click','.sel-item',function(){
        var $this   = $(this);
        var tid     = $this.data('id');
        var tn      = $this.attr('title');

        $dom.tlist.find('.sel-item').removeClass('sel-active');
        $this.addClass('sel-active');
        $dom.navType.html('&gt; '+tn).show();
        submitData.type = tid;
    });
    //提交按鈕
    $dom.subBtn.click(function(){
        s1submit();
    });
    //初始化
    $(function(){
        setGame();
        //getPreset(val.gid,val.sid)
    });
});