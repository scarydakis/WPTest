/*global objURL */
/*global tribe_ev */
/*global FB */
function extractRefererInfo()
{
	var dr=document.referrer;
	var drd='';
	if (dr.length>0)
	{
		drd=$.url(dr).attr('host');
	}
	var dd=document.domain;
	var drUrl=$.url(dr);
	var drUrlParams=drUrl.param();
	var cn='bm_ri';
	var cv={};
	// get the cookie
	if ($.cookie(cn) !== null)
	{
		try
		{
			cv=$.secureEvalJSON($.cookie(cn));
		}
		catch(err)
		{
			clog(err);
		}
	}
	var cp={
		referrer: '',
		keywords: '',
		isGoogleAdWords: false
	};
	var cookieParamExists=function(strParamName) {
		var param=cv[strParamName];
		clog('Looking for exisiting cookie value \'' + strParamName + '\' (value \'' + param + '\') exists?: ' + (typeof(param) !== 'undefined' && param.toString().length>0) + ' length: ' + (typeof(param) !== 'undefined' ? param.toString().length : 0));
		return (typeof(param) !== 'undefined' && param.toString().length>0);
	};

	var kwps=['q','p','clue','_nkw'];
	var kws='';

	// extract keywords from referer URI
	for (var i=0; i<kwps.length; i++)
	{
		var kwp=kwps[i];
		if (kwp in drUrlParams)
		{
			if (kws.length>0)
			{
				kws+= " ";
			}
			kws += drUrlParams[kwp].replace(/\+/g, ' ');
		}
	}

	cp.referrer=(!cookieParamExists('referrer') && drd.length>0 && drd.indexOf(dd) === -1) ? dr : cv.referrer;
	cp.keywords=(!cookieParamExists('keywords') && kws.length>0) ? kws : cv.keywords;
	cp.isGoogleAdWords=(!cookieParamExists('isGoogleAdWords')) ? ('gclid' in objURL.param()) : cv.isGoogleAdWords;

	$.cookie(cn,$.toJSON(cp),{'path':'/'});

	$('.externalreferrer input').each(function(){
		$(this).val(cp.referrer);
	});

	$('.keywords input').each(function(){
		$(this).val(cp.keywords);
	});

	$('.googleadwords input').each(function(){
		$(this).val(cp.isGoogleAdWords);
	});

	clog('URL Keywords: ' + kws);
	clog('input REF: ' + $('.externalreferrer input').val());
	clog('input KW: ' + $('.keywords input').val());
	clog('input GAW: ' + $('.googleadwords input').val());
}

function ScwdMenuFloat(objArgs)
{
	var fm=$('.site-header .float-me');
	var te=$('.site-header');
	var scrollTop=0;
	var triggerPos=0;
	var timer=null;

	if (fm.length===1 && te.length===1)
	{

		var doFloat=function()
		{
			scrollTop=$(document).scrollTop();
			triggerPos=te.offset().top - $('#wpadminbar').outerHeight(true);

			if (scrollTop>100)
			{
				te.addClass('min');
			}
			else
			{
				te.removeClass('min');
			}

			if (scrollTop >= triggerPos)
			{
				fm.addClass('docked').removeClass('undocked');
			}
			else if (fm.hasClass('min'))
			{
				fm.removeClass('docked').addClass('undocked');
			}
		};

		var reset=function()
		{
			fm.removeClass('docked').addClass('undocked');
			te.css({height:''});
			te.removeClass('min');
		};
		$(window).on('resize scroll', function(e){
			if (!doMobileMenu())
			{
				doFloat();
			}
			else
			{
				reset();
			}
		});
		if (!doMobileMenu())
		{
			doFloat();
		}
		else
		{
			reset();
		}
	}
}
function doMobileMenu()
{
	return $('.mobile-menu .bar').css('z-index') === '999';
}

function ScwdMobileMenu(args)
{
	var self=this;

	this.defaults={
		mobileMenuParentSelector : '.mobile-menu',
		menuBarSelector : '.mobile-menu .bar',
		menuSelector : '.mobile-menu .menu-wrap',
		triggerSelector : '.mobile-menu-icon'
	};

	this.settings=$.extend(true, this.defaults, args);

	this.mobileMenuParent=$(this.settings.mobileMenuParentSelector);
	this.menuBar=$(this.settings.menuBarSelector);
	this.menu=$(this.settings.menuSelector);
	this.triggerEl=$(this.settings.triggerSelector);
	this.mobileMenuParent.removeClass('hoverable');

	this.isMobileMenu=function()
	{
		return self.menuBar.css('z-index') === '999';
	};

	this.init=function()
	{
		self.menu.trigger('mobilemenu-pre-init');
		self.mobileMenuParent.data('params',{
			isOpen : false,
			isActive : false
		});
		self.mobileMenuParent.data('params').isActive=self.isMobileMenu();
		self.setActiveCssClass();
		self.setOpenCssClass();
		self.menu.trigger('mobilemenu-post-init');
	};

	this.resizeMobileMenu=function()
	{
		self.menu.trigger('mobilemenu-resize');
		if (!self.mobileMenuParent.data('params').isActive)
		{
			self.setOpenCssClass();
			self.menu.removeAttr('style');
			//self.mobileMenuParent.removeAttr('style');
		}
	};

	this.setOpenCssClass=function(isLoad)
	{
		if (typeof(isLoad) === 'boolean' && isLoad)
		{
			self.mobileMenuParent.addClass('closed');
		}
		else if (self.mobileMenuParent.data('params').isActive)
		{
			self.mobileMenuParent.data('params').isOpen=self.menu.is(':visible');
			if (self.mobileMenuParent.data('params').isOpen)
			{
				self.mobileMenuParent.removeClass('closed').addClass('open');
			}
			else
			{
				self.mobileMenuParent.removeClass('open').addClass('closed');
			}
		}
	};

	this.setActiveCssClass=function()
	{
		if (self.mobileMenuParent.data('params').isActive)
		{
			self.mobileMenuParent.removeClass('inactive').addClass('active');
		}
		else
		{
			self.mobileMenuParent.removeClass('active').addClass('inactive');
		}
	};

	self.triggerEl.on('click',function(e){
		if (self.menu.is(':animated'))
		{
			return false;
		}
		var state=self.menu.is(':visible') ? 'close' : 'open';
		var speed=self.menu.is(':visible') ? 150 : 250;
		self.menu.trigger('mobilemenu-pre-'+ state);
		self.menu.stop(true,false).slideToggle(speed).promise().done(function(){
			self.setOpenCssClass();
			var triggerName='mobilemenu-' + (self.mobileMenuParent.data('params').isOpen ? 'opened' : 'closed');
			self.menu.trigger(triggerName);
		});
	});

	$(window).on('resize',_.debounce(function(e){
		self.mobileMenuParent.data('params').isActive=self.isMobileMenu();
		self.setActiveCssClass();
		self.resizeMobileMenu();
	},40,false));

	self.init();
}

function loadFBSDK()
{
	$.cachedScript('//connect.facebook.net/en_US/sdk.js').done(function(script, textStatus)
	{
		FB.init({
			appId : '296425023891091',
			xfbml : true,
			version : 'v2.3' // or v2.0, v2.1, v2.0
		});
		/*$('#loginbutton,#feedbutton').removeAttr('disabled');
		FB.getLoginStatus(updateStatusCallback);*/
	});
}

function updateStatusCallback(response)
{
	clog(response);
	if (response.status==='connected')
	{
		clog('Logged in.');
	}
	else
	{
		FB.login();
	}
}

// try to convert a string to a json object. returns false on failure or json object on success.
function strToJson(strIn)
{
	var retVal=false;
	try
	{
		retVal=$.secureEvalJSON(strIn);
	}
	catch(err)
	{
		clog(err);
	}
	return retVal;
}

function formatCurrency(total,includeFloat)
{
	var neg = false;
	if(total < 0)
	{
		neg = true;
		total = Math.abs(total);
	}
	//parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
	total=parseFloat(total, 10);
	if (includeFloat) { total=total.toFixed(2); }
	return (neg ? "-$" : '$') + total.toString();
}

function clog(strIn)
{
	if (typeof(console)!=='undefined') { console.log(strIn); }
}

// get a cached script
jQuery.cachedScript = function(url, options)
{
	options = $.extend( options || {}, {
		dataType: 'script',
		cache: true,
		url: url
	});
	return jQuery.ajax( options );
};
