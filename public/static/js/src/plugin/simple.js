/**
 * @name JQ小擴展
 * @memberOf plugin
 * @version 1.0.0
 * @author: 10482
 */
define(function(require, exports, module) {
    require('./easing.js');
    $.fn.extend({
        photoPlay: function(option) { //幻燈片切換
            var $this   = $(this),
                sWidth  = $this.width(),
                len     = 0,
                index   = 0,
                picTimer;
            var defaultOption = {
                delay:      2000,
                speed:      5000,
                aSpeed:     300,
                hasArrow:   false,
                animate:    'swing',
                finish:     '',
                effect:     'slide'
            };
            var dop = $.extend({},defaultOption,option || {});
            var showOnce = 1;
            setTimeout(function() { //延遲一些，等頁面內容装填好，比如openx
                var btnHtml = '<div class="photo-play-btn clearfix">';
                $this.find('li').each(function(i) {
                    if ($(this).html()) {
                        len++;
                        btnHtml += '<i></i>';
                    }else{
                        $(this).remove();
                    }
                });
                btnHtml += '</div>';
                if(len > 1){
                    if(dop.hasArrow){
                        var toPrev = '<span class="play-btn photo-play-prev">&lt;</span>';
                        var toNext = '<span class="play-btn photo-play-next">&gt;</span>';
                        $this.append(toNext+toPrev);
                    }
                    //佈置按鈕樣式
                    if (typeof slideStyle == 'undefined') { //填充樣式
                        var style = '<style>.photo-play-btn{display: block;position: absolute;bottom: 8px;right: 8px;z-index: 2;padding:3px;background: rgba(255,255,255,.4);background: #dbd5d2\9;filter:alpha(opacity=70);}.photo-play-btn i{display: block;float: left;width: 14px;height: 5px;margin-right: 3px;background: #fff;cursor:pointer;}.photo-play-btn i:last-child{ margin-right: 0;}.photo-play-btn .active{background: #ff4e00;}.play-btn{display:none;width:26px;height:46px;position:absolute;top:50%;z-index:10;margin-top:-23px;background:rgba(0,0,0,.7);color:#fff;font-size:42px;text-align:center;font-family:serif;font-weight:700;cursor:pointer;}.photo-play-prev{left:5px;}.photo-play-next{right:5px;}</style>';

                        $('head').append(style);
                        slideStyle = 1;
                    }

                    //布置元素
                    $this.append(btnHtml);
                    $this.find('ul').css({
                        "width": sWidth * len,
                        'position': 'relative'
                    });
                    $this.find('ul li').css("width", sWidth);

                    //按鈕點擊
                    $this.find('.photo-play-btn i').click(function() {
                        index = $(this).index();
                        showPics(index);
                        stop();
                    });
                    $this.find('.photo-play-prev').click(function() { //上一張
                        var v = $this.find('i.active').index();
                        if(v == 0){
                            showPics(len-1)
                        }else{
                            showPics(--v);
                        }
                        stop();
                    });
                    $this.find('.photo-play-next').click(function() { //下一張
                        var v = $this.find('i.active').index();
                        if(v == len-1){
                            showPics(0)
                        }else{
                            showPics(++v);
                        }
                        stop();
                    });

                    $this.hover(function(){
                        $this.find('.play-btn').show();
                        stop();
                    }, function() {
                        $this.find('.play-btn').hide();
                        play();
                    });
                    
                    showPics(0);
                    showOnce = 0;
                }

            }, dop.delay);

            function showPics(a) {
                switch(dop.effect){
                    case 'slide':
                        var nowLeft = -a * sWidth;
                        $this.find('ul').stop().animate({
                            "left": nowLeft
                        }, 300,dop.animate,function(){
                            play();
                            dop.finish && dop.finish();
                        });
                    break;
                    case 'fade':
                        if(!showOnce){
                            $this.find('ul li').hide();
                        }
                        $this.find('ul li:eq('+a+')').fadeIn(600);
                        play();
                    break;
                }
                
                $this.find('.photo-play-btn i').removeClass("active").eq(a).addClass("active");
                if (a + 1 >= len) {
                    index = 0;
                } else {
                    index = a + 1;
                }
            }
            function play() {
                stop();
                picTimer = setTimeout(function(){
                    showPics(index);
                    play();
                },dop.speed);
            }
            function stop(){
                clearTimeout(picTimer);
                picTimer = undefined;
            }
        },
        //photoPlay end

        /* //跑馬燈 可擴展
        .deal-list{
            margin-left: 96px;
            height: 100%;
            overflow: hidden;
        }
        .deal-list ul{
            float: left;
        }
        .deal-list li{
            float: left;
            margin-right: 14px;
            line-height: 40px;
        }
        */
        paomadeng: function(type) {
            var $this   = $(this),
                $ul     = $this.find('ul'),
                $wp     = $this.find('.ul-wp'),
                i       = 0;
            var sidTime;

            var allW = $ul.width();

            $wp.append($ul.clone()).css({position:'relative',width:'9999px'});

            sidTime = setInterval(function() {
                doSlide();
            }, 50);

            function doSlide() {

                if (allW > -parseInt($wp.css('left'))) {
                    i++;
                } else {
                    i = 0;
                }
                $wp.css('left', -i);
            }
            $this.hover(function() {
                clearInterval(sidTime);
            }, function() {
                sidTime = setInterval(function() {
                    doSlide();
                }, 50);
            });
        },
        //paomadeng end

        setEleTop: function(className) { //設置置頂浮層 $('.menu').setEleTop(); 如果有class則切換
            var $this   = $(this),
                $mom    = $this.parent(),
                o_top   = $this.offset().top,
                o_wid   = $this.width(),
                o_hg    = $this.height(),
                c_n     = className;

            setT();
            $(window).scroll(function() {
                setT();
            });

            function setT() {
                if (c_n) {
                    //有類名切換
                } else {
                    if ($(window).scrollTop() > o_top) {
                        $this.css({
                            'position': 'fixed',
                            'top': 0,
                            'width': o_wid,
                            'z-index': 5
                        });
                        $mom.css('paddingTop', o_hg);
                    } else {
                        $this.css('position', 'static');
                        $mom.css('paddingTop', 0);
                    }
                }
            }
        },
        //setEleTop end
        placeHolder: function() {

        },
        /*結構
        <div class="q">
           <ul class="clearfix">
                <li></li>
                <li></li>
                <li></li>
            </ul>
            <div class="q-c"></div>
            <div class="q-c"></div>
            <div class="q-c"></div>
        </div>
        */
        slideTab: function(option) { //選項卡  show-默認顯示塊 0開始
            var $this         = $(this);
            var cn            = $this.selector;
            var $ul           = $this.find('ul').eq(0);
            var defaultOption = {
                event: 'click',
                show:  0,
                type:  ''
            };
            var dop = $.extend({},defaultOption,option || {});
            if (typeof dop.show != undefined) { //顯示默認塊
                $ul.find('li').removeClass('on');
                $ul.find('li').eq(dop.show).addClass('on');
                $this.find( cn + '-c').hide().eq(dop.show).show();
            }
            switch (dop.type) {
                case '':
                    //無動畫效果
                    $ul.find('li').on(dop.event,function() {
                        if($(this).data('link')) return false;
                        var i = $(this).index();
                        $ul.find('li').removeClass('on');
                        $(this).addClass('on');
                        $this.find( cn + '-c').hide().eq(i).show();
                    });
                    break;
                case 'lineup':
                    console.log(1);
                    break;
            }
        },

        advPaoma: function(t1, t2) { //增強跑馬燈 t1:停留时间  t2:动画时间
            var $this = $(this);
            var h = $this.find('li').eq(0).outerHeight();
            if ($this.find('li').eq(0).html()) { //有內容
                setInterval(function() {
                    goUp();
                }, t1);
            }

            function goUp() {
                var $firstLi = $this.find('li:first')
                var html = $firstLi.clone();

                $firstLi.animate({
                    "marginTop": -h
                }, t2, function() {
                    $this.append(html);
                    $firstLi.remove();
                });
            }
        },

        preventScroll: function() { //阻止滾動冒泡
            $(this).each(function() {
                var _this = this;
                if (navigator.userAgent.indexOf('Firefox') >= 0) { //firefox  
                    _this.addEventListener('DOMMouseScroll', function(e) {
                        _this.scrollTop += e.detail > 0 ? 60 : -60;
                        e.preventDefault();
                    }, false);
                } else {
                    _this.onmousewheel = function(e) {
                        e = e || window.event;
                        _this.scrollTop += e.wheelDelta > 0 ? -60 : 60;
                        return false;
                    }
                }
            });
        },

        scrollShow: function(num) { //滾動顯示
            var $this = $(this);
            $(window).scroll(function() {
                if ($(window).scrollTop() >= num) {
                    $this.fadeIn();
                } else {
                    $this.fadeOut();
                }
            });
            if ($(window).scrollTop() >= num) {
                $this.fadeIn();
            } else {
                $this.fadeOut();
            }
        }

    });
});