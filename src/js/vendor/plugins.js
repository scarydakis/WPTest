/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * Create a cookie with the given name and value and other optional parameters.
 *
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Set the value of a cookie.
 * @example $.cookie('the_cookie', 'the_value', { expires: 7, path: '/', domain: 'jquery.com', secure: true });
 * @desc Create a cookie with all available options.
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Create a session cookie.
 * @example $.cookie('the_cookie', null);
 * @desc Delete a cookie by passing null as value. Keep in mind that you have to use the same path and domain
 *       used when the cookie was set.
 *
 * @param String name The name of the cookie.
 * @param String value The value of the cookie.
 * @param Object options An object literal containing key/value pairs to provide optional cookie attributes.
 * @option Number|Date expires Either an integer specifying the expiration date from now on in days or a Date object.
 *                             If a negative value is specified (e.g. a date in the past), the cookie will be deleted.
 *                             If set to null or omitted, the cookie will be a session cookie and will not be retained
 *                             when the the browser exits.
 * @option String path The value of the path atribute of the cookie (default: path of page that created the cookie).
 * @option String domain The value of the domain attribute of the cookie (default: domain of page that created the cookie).
 * @option Boolean secure If true, the secure attribute of the cookie will be set and the cookie transmission will
 *                        require a secure protocol (like HTTPS).
 * @type undefined
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */

/**
 * Get the value of a cookie with the given name.
 *
 * @example $.cookie('the_cookie');
 * @desc Get the value of a cookie.
 *
 * @param String name The name of the cookie.
 * @return The value of the cookie.
 * @type String
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */

jQuery.cookie=function(name,value,options){if(typeof value!='undefined'){options=options||{};if(value===null){value='';options.expires=-1;} var expires='';if(options.expires&&(typeof options.expires=='number'||options.expires.toUTCString)){var date;if(typeof options.expires=='number'){date=new Date();date.setTime(date.getTime()+(options.expires*24*60*60*1000));}else{date=options.expires;} expires='; expires='+date.toUTCString();} var path=options.path?'; path='+(options.path):'';var domain=options.domain?'; domain='+(options.domain):'';var secure=options.secure?'; secure':'';document.cookie=[name,'=',encodeURIComponent(value),expires,path,domain,secure].join('');}else{var cookieValue=null;if(document.cookie&&document.cookie!=''){var cookies=document.cookie.split(';');for(var i=0;i<cookies.length;i++){var cookie=jQuery.trim(cookies[i]);if(cookie.substring(0,name.length+1)==(name+'=')){cookieValue=decodeURIComponent(cookie.substring(name.length+1));break;}}} return cookieValue;}};

/**
 * jQuery.ScrollTo - Easy element scrolling using jQuery.
 * Copyright (c) 2007-2009 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * Date: 5/25/2009
 * @author Ariel Flesler
 * @version 1.4.2
 *
 * http://flesler.blogspot.com/2007/10/jqueryscrollto.html
 */
;(function(d){var k=d.scrollTo=function(a,i,e){d(window).scrollTo(a,i,e)};k.defaults={axis:'xy',duration:parseFloat(d.fn.jquery)>=1.3?0:1};k.window=function(a){return d(window)._scrollable()};d.fn._scrollable=function(){return this.map(function(){var a=this,i=!a.nodeName||d.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!i)return a;var e=(a.contentWindow||a).document||a.ownerDocument||a;return d.browser.safari||e.compatMode=='BackCompat'?e.body:e.documentElement})};d.fn.scrollTo=function(n,j,b){if(typeof j=='object'){b=j;j=0}if(typeof b=='function')b={onAfter:b};if(n=='max')n=9e9;b=d.extend({},k.defaults,b);j=j||b.speed||b.duration;b.queue=b.queue&&b.axis.length>1;if(b.queue)j/=2;b.offset=p(b.offset);b.over=p(b.over);return this._scrollable().each(function(){var q=this,r=d(q),f=n,s,g={},u=r.is('html,body');switch(typeof f){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f)){f=p(f);break}f=d(f,this);case'object':if(f.is||f.style)s=(f=d(f)).offset()}d.each(b.axis.split(''),function(a,i){var e=i=='x'?'Left':'Top',h=e.toLowerCase(),c='scroll'+e,l=q[c],m=k.max(q,i);if(s){g[c]=s[h]+(u?0:l-r.offset()[h]);if(b.margin){g[c]-=parseInt(f.css('margin'+e))||0;g[c]-=parseInt(f.css('border'+e+'Width'))||0}g[c]+=b.offset[h]||0;if(b.over[h])g[c]+=f[i=='x'?'width':'height']()*b.over[h]}else{var o=f[h];g[c]=o.slice&&o.slice(-1)=='%'?parseFloat(o)/100*m:o}if(/^\d+$/.test(g[c]))g[c]=g[c]<=0?0:Math.min(g[c],m);if(!a&&b.queue){if(l!=g[c])t(b.onAfterFirst);delete g[c]}});t(b.onAfter);function t(a){r.animate(g,j,b.easing,a&&function(){a.call(this,n,b)})}}).end()};k.max=function(a,i){var e=i=='x'?'Width':'Height',h='scroll'+e;if(!d(a).is('html,body'))return a[h]-d(a)[e.toLowerCase()]();var c='client'+e,l=a.ownerDocument.documentElement,m=a.ownerDocument.body;return Math.max(l[h],m[h])-Math.min(l[c],m[c])};function p(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);

/** Tempo **/
function TempoEvent(type,item,element){this.type=type;this.item=item;this.element=element;return this}TempoEvent.Types={RENDER_STARTING:'render_starting',ITEM_RENDER_STARTING:'item_render_starting',ITEM_RENDER_COMPLETE:'item_render_complete',RENDER_COMPLETE:'render_complete'};var Tempo=(function(tempo){var utils={memberRegex:function(obj){var member_regex='';for(var member in obj){if(obj.hasOwnProperty(member)){if(member_regex.length>0){member_regex+='|'}member_regex+=member}}return member_regex},pad:function(val,pad,size){while(val.length<size){val=pad+val}return val},trim:function(str){return str.replace(/^\s*([\S\s]*?)\s*$/,'$1')},startsWith:function(str,prefix){return(str.indexOf(prefix)===0)},replaceVariables:function(renderer,_tempo,item,str){return str.replace(/\{\{[ ]?([A-Za-z0-9\._\[\]]*?)([ ]?\|[ ]?.*?)?[ ]?\}\}/g,function(match,variable,args){try{var val=null;if(utils.startsWith(variable,'_tempo.')){return eval(variable)}if(utils.typeOf(item)==='array'){val=eval('item'+variable)}else{val=eval('item.'+variable)}if(args!==undefined&&args!==''){var filters=utils.trim(utils.trim(args).substring(1)).split(/\|/);for(var i=0;i<filters.length;i++){var filter=utils.trim(filters[i]);var filter_args=[];if(filter.indexOf(' ')>-1){var f=filter.substring(filter.indexOf(' ')).replace(/^[ ']*|[ ']*$/g,'');filter_args=f.split(/(?:[\'"])[ ]?,[ ]?(?:[\'"])/);filter=filter.substring(0,filter.indexOf(' '))}val=renderer.filters[filter](val,filter_args)}}if(val!==undefined){return val}}catch(err){}return''})},replaceObjects:function(renderer,_tempo,item,str){var regex=new RegExp('(?:__[\\.]?)((_tempo|\\[|'+utils.memberRegex(item)+')([A-Za-z0-9\\._\\[\\]]+)?)','g');return str.replace(regex,function(match,variable,args){try{var val=null;if(utils.startsWith(variable,'_tempo.')){return eval(variable)}if(utils.typeOf(item)==='array'){val=eval('item'+variable)}else{val=eval('item.'+variable)}if(val!==undefined){if(utils.typeOf(val)==='string'){return'\''+val+'\''}else{return val}}}catch(err){}return undefined})},applyAttributeSetters:function(renderer,item,str){return str.replace(/([A-z0-9]+?)(?==).*?data-\1="(.*?)"/g,function(match,attr,data_value){if(data_value!==''){return attr+'="'+data_value+'"'}return match})},clearContainer:function(el){if(el!==undefined&&el.childNodes!==undefined){for(var i=el.childNodes.length;i>=0;i--){if(el.childNodes[i]!==undefined&&el.childNodes[i].getAttribute!==undefined&&el.childNodes[i].getAttribute('data-template')!==null){el.childNodes[i].parentNode.removeChild(el.childNodes[i])}}}},isNested:function(el){var p=el.parentNode;while(p){if(p.getAttribute!==undefined&&p.getAttribute('data-template')!==null){return true}p=p.parentNode}return false},equalsIgnoreCase:function(str1,str2){return str1.toLowerCase()===str2.toLowerCase()},getElement:function(template,html){if(utils.equalsIgnoreCase(template.tagName,'tr')){var el=document.createElement('div');el.innerHTML='<table><tbody>'+html+'</tbody></table>';var depth=3;while(depth--){el=el.lastChild}return el}else{template.innerHTML=html;return template}},typeOf:function(obj){if(typeof(obj)==="object"){if(obj===null){return"null"}if(obj.constructor===([]).constructor){return"array"}if(obj.constructor===(new Date()).constructor){return"date"}if(obj.constructor===(new RegExp()).constructor){return"regex"}return"object"}return typeof(obj)},notify:function(listener,event){if(listener!==undefined){listener(event)}}};function Templates(nestedItem){this.defaultTemplate=null;this.namedTemplates={};this.container=null;this.nestedItem=nestedItem!==undefined?nestedItem:null;return this}Templates.prototype={parse:function(container){this.container=container;var children=container.getElementsByTagName('*');for(var i=0;i<children.length;i++){if(children[i].getAttribute!==undefined&&children[i].getAttribute('data-template')!==null&&(this.nestedItem===children[i].getAttribute('data-template')||children[i].getAttribute('data-template')===''&&!utils.isNested(children[i]))){this.createTemplate(children[i])}else if(children[i].getAttribute!==undefined&&children[i].getAttribute('data-template-fallback')!==null){children[i].style.display='none'}}if(this.defaultTemplate===null){var el=document.createElement('div');el.setAttribute('data-template','');el.innerHTML=this.container.innerHTML;this.container.innerHTML='';this.container.appendChild(el);this.createTemplate(el)}utils.clearContainer(this.container)},createTemplate:function(node){var element=node.cloneNode(true);if(element.style.removeAttribute){element.style.removeAttribute('display')}else{element.style.removeProperty('display')}this.container=node.parentNode;var nonDefault=false;for(var a=0;a<element.attributes.length;a++){var attr=element.attributes[a];if(utils.startsWith(attr.name,'data-if-')){var val;if(attr.value===''){val=true}else{val='\''+attr.value+'\''}this.namedTemplates[attr.name.substring(8,attr.name.length)+'=='+val]=element;element.removeAttribute(attr.name);nonDefault=true}}if(!nonDefault){this.defaultTemplate=element}},templateFor:function(item){for(var templateName in this.namedTemplates){if(eval('item.'+templateName)){return this.namedTemplates[templateName].cloneNode(true)}}if(this.defaultTemplate){return this.defaultTemplate.cloneNode(true)}}};function Renderer(templates){this.templates=templates;this.listener=undefined;this.started=false;return this}Renderer.prototype={notify:function(listener){this.listener=listener;return this},starting:function(){this.started=true;utils.notify(this.listener,new TempoEvent(TempoEvent.Types.RENDER_STARTING,undefined,undefined));return this},renderItem:function(renderer,tempo_info,item,fragment){var template=renderer.templates.templateFor(item);if(template&&item){utils.notify(this.listener,new TempoEvent(TempoEvent.Types.ITEM_RENDER_STARTING,item,template));var nestedDeclaration=template.innerHTML.match(/data-template="(.*?)"/g);if(nestedDeclaration){for(var i=0;i<nestedDeclaration.length;i++){var nested=nestedDeclaration[i].match(/"(.*?)"/)[1];var t=new Templates(nested);t.parse(template);var r=new Renderer(t);r.render(eval('item.'+nested))}}var html=template.innerHTML.replace(/%7B%7B/g,'{{').replace(/%7D%7D/g,'}}');for(var p=0;p<renderer.tags.length;p++){html=html.replace(new RegExp(renderer.tags[p].regex,'gi'),renderer.tags[p].handler(renderer,item))}html=utils.replaceVariables(this,tempo_info,item,html);html=utils.replaceObjects(this,tempo_info,item,html);if(template.getAttribute('class')){template.className=utils.replaceVariables(this,tempo_info,item,template.className)}if(template.getAttribute('id')){template.id=utils.replaceVariables(this,tempo_info,item,template.id)}html=utils.applyAttributeSetters(this,item,html);fragment.appendChild(utils.getElement(template,html));utils.notify(this.listener,new TempoEvent(TempoEvent.Types.ITEM_RENDER_COMPLETE,item,template))}},_createFragment:function(data){if(data){var tempo_info={};var fragment=document.createDocumentFragment();if(utils.typeOf(data)==='object'){data=[data]}for(var i=0;i<data.length;i++){tempo_info.index=i;this.renderItem(this,tempo_info,data[i],fragment)}return fragment}return null},render:function(data){if(!this.started){utils.notify(this.listener,new TempoEvent(TempoEvent.Types.RENDER_STARTING,undefined,undefined))}this.clear();this.append(data);return this},append:function(data){if(!this.started){utils.notify(this.listener,new TempoEvent(TempoEvent.Types.RENDER_STARTING,undefined,undefined))}var fragment=this._createFragment(data);if(fragment!==null){this.templates.container.appendChild(fragment)}utils.notify(this.listener,new TempoEvent(TempoEvent.Types.RENDER_COMPLETE,undefined,undefined));return this},prepend:function(data){if(!this.started){utils.notify(this.listener,new TempoEvent(TempoEvent.Types.RENDER_STARTING,undefined,undefined))}var fragment=this._createFragment(data);if(fragment!==null){this.templates.container.insertBefore(fragment,this.templates.container.firstChild)}utils.notify(this.listener,new TempoEvent(TempoEvent.Types.RENDER_COMPLETE,undefined,undefined));return this},clear:function(data){utils.clearContainer(this.templates.container)},tags:[{'regex':'\\{\\{if ([\\s\\S]*?)\\}\\}([\\s\\S]*?)\\{\\{endif\\}\\}','handler':function(renderer,item){return function(match,condition,content){var member_regex=utils.memberRegex(item);condition=condition.replace(/&amp;/g,'&');condition=condition.replace(new RegExp(member_regex,'gi'),function(match){return'item.'+match});if(eval(condition)){return content}return''}}}],filters:{'upper':function(value,args){return value.toUpperCase()},'lower':function(value,args){return value.toLowerCase()},'trim':function(value,args){return utils.trim(value)},'replace':function(value,args){if(value!==undefined&&args.length===2){return value.replace(new RegExp(args[0],'g'),args[1])}return value},'append':function(value,args){if(value!==undefined&&args.length===1){return value+''+args[0]}return value},'prepend':function(value,args){if(value!==undefined&&args.length===1){return args[0]+''+value}return value},'default':function(value,args){if(value!==undefined&&value!==null){return value}if(args.length===1){return args[0]}return value},'date':function(value,args){if(value!==undefined&&args.length===1){var date=new Date(value);var format=args[0];if(format==='localedate'){return date.toLocaleDateString()}else if(format==='localetime'){return date.toLocaleTimeString()}else if(format==='date'){return date.toDateString()}else if(format==='time'){return date.toTimeString()}else{var MONTHS=['January','February','March','April','May','June','July','August','September','October','November','December'];var DAYS=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];var DATE_PATTERNS={'YYYY':function(date){return date.getFullYear()},'YY':function(date){return date.getFullYear().toFixed().substring(2)},'MMMM':function(date){return MONTHS[date.getMonth()]},'MMM':function(date){return MONTHS[date.getMonth()].substring(0,3)},'MM':function(date){return utils.pad((date.getMonth()+1).toFixed(),'0',2)},'M':function(date){return date.getMonth()+1},'DD':function(date){return utils.pad(date.getDate().toFixed(),'0',2)},'D':function(date){return date.getDate()},'EEEE':function(date){return DAYS[date.getDay()]},'EEE':function(date){return DAYS[date.getDay()].substring(0,3)},'E':function(date){return date.getDay()},'HH':function(date){return utils.pad(date.getHours().toFixed(),'0',2)},'H':function(date){return date.getHours()},'mm':function(date){return utils.pad(date.getMinutes().toFixed(),'0',2)},'m':function(date){return date.getMinutes()},'ss':function(date){return utils.pad(date.getSeconds().toFixed(),'0',2)},'s':function(date){return date.getSeconds()},'SSS':function(date){return utils.pad(date.getMilliseconds().toFixed(),'0',3)},'S':function(date){return date.getMilliseconds()},'a':function(date){return date.getHours()<12?'AM':'PM'}};format=format.replace(/(\\)?(Y{2,4}|M{1,4}|D{1,2}|E{1,4}|H{1,2}|m{1,2}|s{1,2}|S{1,3}|a)/g,function(match,escape,pattern){if(!escape){if(DATE_PATTERNS.hasOwnProperty(pattern)){return DATE_PATTERNS[pattern](date)}}return pattern});return format}}return''}}};tempo.prepare=function(container){if(typeof container==='string'){container=document.getElementById(container)}var templates=new Templates();templates.parse(container);return new Renderer(templates)};return tempo})(Tempo||{});

// JQuery URL Parser plugin - https://github.com/allmarkedup/jQuery-URL-Parser
// Written by Mark Perkins, mark@allmarkedup.com
// License: http://unlicense.org/ (i.e. do what you want with it!)
;(function($,undefined){var tag2attr={a:'href',img:'src',form:'action',base:'href',script:'src',iframe:'src',link:'href'},key=["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","fragment"],aliases={"anchor":"fragment"},parser={strict:/^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,loose:/^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/},querystring_parser=/(?:^|&|;)([^&=;]*)=?([^&;]*)/g,fragment_parser=/(?:^|&|;)([^&=;]*)=?([^&;]*)/g;function parseUri(url,strictMode){var str=decodeURI(url),res=parser[strictMode||false?"strict":"loose"].exec(str),uri={attr:{},param:{},seg:{}},i=14;while(i--){uri.attr[key[i]]=res[i]||"";}uri.param['query']={};uri.param['fragment']={};uri.attr['query'].replace(querystring_parser,function($0,$1,$2){if($1){uri.param['query'][$1]=$2;}});uri.attr['fragment'].replace(fragment_parser,function($0,$1,$2){if($1){uri.param['fragment'][$1]=$2;}});uri.seg['path']=uri.attr.path.replace(/^\/+|\/+$/g,'').split('/');uri.seg['fragment']=uri.attr.fragment.replace(/^\/+|\/+$/g,'').split('/');uri.attr['base']=uri.attr.host?uri.attr.protocol+"://"+uri.attr.host+(uri.attr.port?":"+uri.attr.port:''):'';return uri;};function getAttrName(elm){var tn=elm.tagName;if(tn!==undefined)return tag2attr[tn.toLowerCase()];return tn;}$.fn.url=function(strictMode){var url='';if(this.length){url=$(this).attr(getAttrName(this[0]))||'';}return $.url(url,strictMode);};$.url=function(url,strictMode){if(arguments.length===1&&url===true){strictMode=true;url=undefined;}strictMode=strictMode||false;url=url||window.location.toString();return{data:parseUri(url,strictMode),attr:function(attr){attr=aliases[attr]||attr;return attr!==undefined?this.data.attr[attr]:this.data.attr;},param:function(param){return param!==undefined?this.data.param.query[param]:this.data.param.query;},fparam:function(param){return param!==undefined?this.data.param.fragment[param]:this.data.param.fragment;},segment:function(seg){if(seg===undefined){return this.data.seg.path;}else
{seg=seg<0?this.data.seg.path.length+seg:seg-1;return this.data.seg.path[seg];}},fsegment:function(seg){if(seg===undefined){return this.data.seg.fragment;}else
{seg=seg<0?this.data.seg.fragment.length+seg:seg-1;return this.data.seg.fragment[seg];}}};};})(jQuery);

// Jquery JSON parsing plugin
(function($){var escapeable=/["\\\x00-\x1f\x7f-\x9f]/g,meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'};$.toJSON=typeof JSON==='object'&&JSON.stringify?JSON.stringify:function(o){if(o===null){return'null';}
var type=typeof o;if(type==='undefined'){return undefined;}
if(type==='number'||type==='boolean'){return''+o;}
if(type==='string'){return $.quoteString(o);}
if(type==='object'){if(typeof o.toJSON==='function'){return $.toJSON(o.toJSON());}
if(o.constructor===Date){var month=o.getUTCMonth()+1,day=o.getUTCDate(),year=o.getUTCFullYear(),hours=o.getUTCHours(),minutes=o.getUTCMinutes(),seconds=o.getUTCSeconds(),milli=o.getUTCMilliseconds();if(month<10){month='0'+month;}
if(day<10){day='0'+day;}
if(hours<10){hours='0'+hours;}
if(minutes<10){minutes='0'+minutes;}
if(seconds<10){seconds='0'+seconds;}
if(milli<100){milli='0'+milli;}
if(milli<10){milli='0'+milli;}
return'"'+year+'-'+month+'-'+day+'T'+
hours+':'+minutes+':'+seconds+'.'+milli+'Z"';}
if(o.constructor===Array){var ret=[];for(var i=0;i<o.length;i++){ret.push($.toJSON(o[i])||'null');}
return'['+ret.join(',')+']';}
var name,val,pairs=[];for(var k in o){type=typeof k;if(type==='number'){name='"'+k+'"';}else if(type==='string'){name=$.quoteString(k);}else{continue;}
type=typeof o[k];if(type==='function'||type==='undefined'){continue;}
val=$.toJSON(o[k]);pairs.push(name+':'+val);}
return'{'+pairs.join(',')+'}';}};$.evalJSON=typeof JSON==='object'&&JSON.parse?JSON.parse:function(src){return eval('('+src+')');};$.secureEvalJSON=typeof JSON==='object'&&JSON.parse?JSON.parse:function(src){var filtered=src.replace(/\\["\\\/bfnrtu]/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,'');if(/^[\],:{}\s]*$/.test(filtered)){return eval('('+src+')');}else{throw new SyntaxError('Error parsing JSON, source is not valid.');}};$.quoteString=function(string){if(string.match(escapeable)){return'"'+string.replace(escapeable,function(a){var c=meta[a];if(typeof c==='string'){return c;}
c=a.charCodeAt();return'\\u00'+Math.floor(c/16).toString(16)+(c%16).toString(16);})+'"';}
return'"'+string+'"';};})(jQuery);
/*
 * ScrollToFixed
 * https://github.com/bigspotteddog/ScrollToFixed
 *
 * Copyright (c) 2011 Joseph Cava-Lynch
 * MIT license
 */
/*
 * ScrollToFixed
 * https://github.com/bigspotteddog/ScrollToFixed
 *
 * Copyright (c) 2011 Joseph Cava-Lynch
 * MIT license
 */
(function(a){a.isScrollToFixed=function(b){return !!a(b).data("ScrollToFixed")};a.ScrollToFixed=function(d,i){var l=this;l.$el=a(d);l.el=d;l.$el.data("ScrollToFixed",l);var c=false;var G=l.$el;var H;var E;var e;var y;var D=0;var q=0;var j=-1;var f=-1;var t=null;var z;var g;function u(){G.trigger("preUnfixed.ScrollToFixed");k();G.trigger("unfixed.ScrollToFixed");f=-1;D=G.offset().top;q=G.offset().left;if(l.options.offsets){q+=(G.offset().left-G.position().left)}if(j==-1){j=q}H=G.css("position");c=true;if(l.options.bottom!=-1){G.trigger("preFixed.ScrollToFixed");w();G.trigger("fixed.ScrollToFixed")}}function n(){var I=l.options.limit;if(!I){return 0}if(typeof(I)==="function"){return I.apply(G)}return I}function p(){return H==="fixed"}function x(){return H==="absolute"}function h(){return !(p()||x())}function w(){if(!p()){t.css({display:G.css("display"),width:G.outerWidth(true),height:G.outerHeight(true),"float":G.css("float")});cssOptions={"z-index":l.options.zIndex,position:"fixed",top:l.options.bottom==-1?s():"",bottom:l.options.bottom==-1?"":l.options.bottom,"margin-left":"0px"};if(!l.options.dontSetWidth){cssOptions.width=G.width()}G.css(cssOptions);G.addClass(l.options.baseClassName);if(l.options.className){G.addClass(l.options.className)}H="fixed"}}function b(){var J=n();var I=q;if(l.options.removeOffsets){I="";J=J-D}cssOptions={position:"absolute",top:J,left:I,"margin-left":"0px",bottom:""};if(!l.options.dontSetWidth){cssOptions.width=G.width()}G.css(cssOptions);H="absolute"}function k(){if(!h()){f=-1;t.css("display","none");G.css({"z-index":y,width:"",position:E,left:"",top:e,"margin-left":""});G.removeClass("scroll-to-fixed-fixed");if(l.options.className){G.removeClass(l.options.className)}H=null}}function v(I){if(I!=f){G.css("left",q-I);f=I}}function s(){var I=l.options.marginTop;if(!I){return 0}if(typeof(I)==="function"){return I.apply(G)}return I}function A(){if(!a.isScrollToFixed(G)){return}var K=c;if(!c){u()}else{if(h()){D=G.offset().top;q=G.offset().left}}var I=a(window).scrollLeft();var L=a(window).scrollTop();var J=n();if(l.options.minWidth&&a(window).width()<l.options.minWidth){if(!h()||!K){o();G.trigger("preUnfixed.ScrollToFixed");k();G.trigger("unfixed.ScrollToFixed")}}else{if(l.options.maxWidth&&a(window).width()>l.options.maxWidth){if(!h()||!K){o();G.trigger("preUnfixed.ScrollToFixed");k();G.trigger("unfixed.ScrollToFixed")}}else{if(l.options.bottom==-1){if(J>0&&L>=J-s()){if(!x()||!K){o();G.trigger("preAbsolute.ScrollToFixed");b();G.trigger("unfixed.ScrollToFixed")}}else{if(L>=D-s()){if(!p()||!K){o();G.trigger("preFixed.ScrollToFixed");w();f=-1;G.trigger("fixed.ScrollToFixed")}v(I)}else{if(!h()||!K){o();G.trigger("preUnfixed.ScrollToFixed");k();G.trigger("unfixed.ScrollToFixed")}}}}else{if(J>0){if(L+a(window).height()-G.outerHeight(true)>=J-(s()||-m())){if(p()){o();G.trigger("preUnfixed.ScrollToFixed");if(E==="absolute"){b()}else{k()}G.trigger("unfixed.ScrollToFixed")}}else{if(!p()){o();G.trigger("preFixed.ScrollToFixed");w()}v(I);G.trigger("fixed.ScrollToFixed")}}else{v(I)}}}}}function m(){if(!l.options.bottom){return 0}return l.options.bottom}function o(){var I=G.css("position");if(I=="absolute"){G.trigger("postAbsolute.ScrollToFixed")}else{if(I=="fixed"){G.trigger("postFixed.ScrollToFixed")}else{G.trigger("postUnfixed.ScrollToFixed")}}}var C=function(I){if(G.is(":visible")){c=false;A()}};var F=function(I){(!!window.requestAnimationFrame)?requestAnimationFrame(A):A()};var B=function(){var J=document.body;if(document.createElement&&J&&J.appendChild&&J.removeChild){var L=document.createElement("div");if(!L.getBoundingClientRect){return null}L.innerHTML="x";L.style.cssText="position:fixed;top:100px;";J.appendChild(L);var M=J.style.height,N=J.scrollTop;J.style.height="3000px";J.scrollTop=500;var I=L.getBoundingClientRect().top;J.style.height=M;var K=(I===100);J.removeChild(L);J.scrollTop=N;return K}return null};var r=function(I){I=I||window.event;if(I.preventDefault){I.preventDefault()}I.returnValue=false};l.init=function(){l.options=a.extend({},a.ScrollToFixed.defaultOptions,i);y=G.css("z-index");l.$el.css("z-index",l.options.zIndex);t=a("<div />");H=G.css("position");E=G.css("position");e=G.css("top");if(h()){l.$el.after(t)}a(window).bind("resize.ScrollToFixed",C);a(window).bind("scroll.ScrollToFixed",F);if("ontouchmove" in window){a(window).bind("touchmove.ScrollToFixed",A)}if(l.options.preFixed){G.bind("preFixed.ScrollToFixed",l.options.preFixed)}if(l.options.postFixed){G.bind("postFixed.ScrollToFixed",l.options.postFixed)}if(l.options.preUnfixed){G.bind("preUnfixed.ScrollToFixed",l.options.preUnfixed)}if(l.options.postUnfixed){G.bind("postUnfixed.ScrollToFixed",l.options.postUnfixed)}if(l.options.preAbsolute){G.bind("preAbsolute.ScrollToFixed",l.options.preAbsolute)}if(l.options.postAbsolute){G.bind("postAbsolute.ScrollToFixed",l.options.postAbsolute)}if(l.options.fixed){G.bind("fixed.ScrollToFixed",l.options.fixed)}if(l.options.unfixed){G.bind("unfixed.ScrollToFixed",l.options.unfixed)}if(l.options.spacerClass){t.addClass(l.options.spacerClass)}G.bind("resize.ScrollToFixed",function(){t.height(G.height())});G.bind("scroll.ScrollToFixed",function(){G.trigger("preUnfixed.ScrollToFixed");k();G.trigger("unfixed.ScrollToFixed");A()});G.bind("detach.ScrollToFixed",function(I){r(I);G.trigger("preUnfixed.ScrollToFixed");k();G.trigger("unfixed.ScrollToFixed");a(window).unbind("resize.ScrollToFixed",C);a(window).unbind("scroll.ScrollToFixed",F);G.unbind(".ScrollToFixed");t.remove();l.$el.removeData("ScrollToFixed")});C()};l.init()};a.ScrollToFixed.defaultOptions={marginTop:0,limit:0,bottom:-1,zIndex:1000,baseClassName:"scroll-to-fixed-fixed"};a.fn.scrollToFixed=function(b){return this.each(function(){(new a.ScrollToFixed(this,b))})}})(jQuery);