/*global extractRefererInfo */
/*global standardiseHeights */
/*global ScwdMobileMenu */
/*global ScwdMenuFloat */
/*global objURL */
/*global tribe_ev */
/*global FB */
var monthNames={
	short : [
		'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
	],
	long : [
		'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
	]
};
$(function(){

	extractRefererInfo();
	standardiseHeights();
	//loadFBSDK();

	var mobileMenu=new ScwdMobileMenu();
	var menuFloat=new ScwdMenuFloat({
		mobileMenuInstance : mobileMenu,
		floatTrigger : 0
	});

	/*clog(tribe_ev.data);
	clog(tribe_ev.events);
	clog(tribe_ev.fn);
	clog(tribe_ev.state);
	clog(tribe_ev.tests);*/
	if (typeof(tribe_ev) !== 'undefined')
	{
		var yearSepBeforeMonthSep=function()
		{
			// move year sperator above month sperator
			$('.tribe-events-list-separator-year').each(function(){
				$(this).prev('.tribe-events-list-separator-month').before($(this));
			});
		};
		$(tribe_ev.events).on('tribe_ev_ajaxSuccess',function(e){
			$('.archive-description .archive-title').html($('#tribe-events-header').data('page_title'));
			yearSepBeforeMonthSep();
		});

		$(tribe_ev.events).on('tribe_ev_serializeBar',function(a,b,c){

		});
		yearSepBeforeMonthSep();

	}
	$('a[href*=#]:not(a[href="#"])').on('click', function(e){
		var href=$(this);
		$('html, body').animate({
			scrollTop: $(href.attr('href')).offset().top - ($('.site-header').position().top+$('.site-header').outerHeight(true))
		}, 250).promise().done(function(){
			var focusFirstFormEl=href.data('focusfirstformel').toString()==='true';
			if (focusFirstFormEl)
			{
				$(':input:visible:enabled:first', $('form:first')).focus();
			}
		});
		e.preventDefault();
	});

	$(document).on('gform_post_render',function(e,formid){
		var error=$('#gform_' + formid + ' .validation_error');
		var success=$('.gforms_confirmation_message');
		var el=null;

		if (error.length > 0)
		{
			el=error;
		}
		else if (success.length > 0)
		{
			el=success;
		}

		if (el !== null && el.length>0)
		{
			$('html, body').animate({
				scrollTop: el.offset().top - 10 - (menuFloat.spacerEl.outerHeight(true))
			}, 500).promise().done(function(){
				if (error.length >0)
				{
					$(':input:visible:enabled:first', $('.gfield.gfield_error:first')).focus();
				}
			});
		}
	});

});