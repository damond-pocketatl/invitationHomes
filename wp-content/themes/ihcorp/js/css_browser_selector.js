/*
CSS Browser Selector v0.4.0 (Nov 02, 2010)
Rafael Lima (http://rafael.adm.br)
http://rafael.adm.br/css_browser_selector
License: http://creativecommons.org/licenses/by/2.5/
Contributors: http://rafael.adm.br/css_browser_selector#contributors

v0.5.0 2011-08-24
modified to include 
  any version of Firefox
  more versions of Windows (Win7, Vista, XP, Win2k)
	more versions of IE under unique conditions

identifies
	browsers: Firefox; IE; Opera; Safari; Chrome, Konqueror, Iron
	browser versions: (most importantly: ie6, ie7, ie8, ie9)
	rendering engines: Webkit; Mozilla; Gecko
	platforms/OSes: Mac; Win: Win7, Vista, XP, Win2k; FreeBSD; Linux/x11 
	devices: Ipod; Ipad; Iphone; WebTV; Blackberry; Android; J2me; mobile(generic)
	enabled technology: JS
will remove "no-js" or "nojs", if found as class in HTML tag
*/
function css_browser_selector(u)
	{
	var ua=u.toLowerCase(),
	is=function(t) { return ua.indexOf(t)>-1},
	g='gecko',
	w='webkit',
	s='safari',
	o='opera',
	m='mobile',
	f='ff',
	h=document.documentElement,
	b=	[
		(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+(/trident\/4\.0/.test(ua) ? '8' : RegExp.$1))
		:is('firefox/')?f+(/firefox\/(\d+(\.?\d+)*)/.test(ua)?' '+f+RegExp.$1.replace(/\./g,"").substr(0,2):'')	
		:is('gecko/')?g
		:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:''))
		:is('konqueror')?'konqueror'
		:is('blackberry')?m+' blackberry'
		:is('android')?m+' android'
		:is('chrome')?w+' chrome'
		:is('iron')?w+' iron'
		:is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:'')
		:is('mozilla/')?g:''
		,is('j2me')?m+' j2me'
		:is('iphone')?m+' iphone'
		:is('ipod')?m+' ipod'
		:is('ipad')?m+' ipad'
		:is('mac')?'mac'
		:is('darwin')?'mac'
		:is('webtv')?'webtv'
		:is('win')?'win'+
				(is('windows nt 6.1')?' win7'
				:is('windows nt 6.0')?' vista'
				:is('windows nt 5.2') || is('windows nt 5.1') ? ' xp' 
				:is('windows nt 5.0')?' win2k': ''
				) 
		:is('freebsd')?'freebsd'
		:(is('x11')||is('linux'))?'linux':''
		,'js'
		];
	var c = b.join(' ');
	h.className =  h.className.replace(/no-?js/g,'');
	h.className += (h.className.length>0?' ':'')+c;
	return c;
	}
css_browser_selector(navigator.userAgent);