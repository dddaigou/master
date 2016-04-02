define(function (require, exports, module) {
	require('../plugin/simple.js');
	require('../plugin/public.js');

	$('.n-u-list').advPaoma(3000,200);
	$('.lg-banner').photoPlay({
		hasArrow:true,
        speed:4000,
        aSpeed:500,
        animate:'easeOutSine',
        effect:'fade'
	});
});