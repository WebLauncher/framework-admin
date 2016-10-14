/**
 * @author Mihai
 */
$(document).ready(function() {
	$('[descriptor]').each(function(i, el) {
		$(el).find("a").each(function(ii, eel) {
			$(eel).next("div").hide();
			$(eel).hover(function() {
				$("#" + $(el).attr('descriptor')).html($(this).next().html()).show();
			}, function() {
			});
		});
	});

	//topmenu
	$('#topmenu li').on('mouseenter click',function(el) {
		$(this).prevAll().children('ul').hide();
		$(this).nextAll().children('ul').hide();
		$(this).prevAll().children('a').removeClass('selected');
		$(this).nextAll().children('a').removeClass('selected');
		$(this).children('ul').show();
		$(this).children('a').addClass('selected');
	});

	$('#topmenu li').on('mouseleave',function(el) {
		$(this).children('ul').hide();
		$(this).children('a').removeClass('selected');
	});
});

function admin_InitJs() {
	jQuery('.j_button').each(function(i, el) {
		if (!jQuery(el).data("button")) {
			options = {};
			if(!jQuery(el).html())
				options.text=false;
			if (jQuery(el).attr('preicon') || jQuery(el).attr('posticon')) {
				options.icons = {};
				if (jQuery(el).attr('preicon'))
					options.icons.primary = jQuery(el).attr('preicon');
				if (jQuery(el).attr('posticon'))
					options.icons.secondary = jQuery(el).attr('posticon');
			}
			jQuery(el).button(options);
		}
	});
	$('[ajax_url]').each(function(i, el) {
		if(!jQuery(el).attr('ajax_loaded')){
			url=jQuery(el).attr('ajax_url');
			ajax_load(url,'',el);
			jQuery(el).attr('ajax_loaded',1);
		}
	});
}

function admin_go_to(url) {
	ajax_load(url, '', '#content');
}

function ajax_del(url, id, evalonsuccess, confirm_msg) {
	if (confirm("Sunteti sigur ca doriti sa stergeti?")) {
		ajax_action(url, "delete:" + id, evalonsuccess);
	}
}

function ajax_order(url, id, order, evalonsuccess) {
	ajax_load(url + "?a=order:" + id + ":" + order, "", "", evalonsuccess);
}

function ajax_active(url, id, value, evalonsuccess, confirm_msg) {
   if (confirm_msg != "" && typeof confirm_msg !== 'undefined') {
        if (confirm(confirm_msg))
           ajax_load(url + "?a=active:" + id + ":" + value, "", "", evalonsuccess);
    }else{
        ajax_load(url + "?a=active:" + id + ":" + value, "", "", evalonsuccess);
	}
}

function ajax_action(url, action, evalonsuccess, confirm_msg) {
	if (confirm_msg != "") {
		if (confirm(confirm_msg))
			ajax_load(url + "?a=" + action, "", "", evalonsuccess);
	} else
		ajax_load(url + "?a=" + action, "", "", evalonsuccess);
}


function start_editor(type,id,full_page)
{
            CKEDITOR.replace( id,
            {
                fullPage:full_page,
                 filebrowserBrowseUrl : root+'objects/filemanagers/ckfinder/ckfinder.html',
                 filebrowserImageBrowseUrl : root+'objects/filemanagers/ckfinder/ckfinder.html?Type=Images',
                 filebrowserFlashBrowseUrl : root+'objects/filemanagers/ckfinder/ckfinder.html?Type=Flash',
                 filebrowserUploadUrl : root+'objects/filemanagers/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                 filebrowserImageUploadUrl : root+'objects/filemanagers/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                 filebrowserFlashUploadUrl : root+'objects/filemanagers/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
                 entities:false
            });

}

function show_submit_loader(message) {
	id = 'submit_loader';
	jid = '#' + id;
	if ($(jid).length <= 0)
		$('body').prepend('<div id="' + id + '"></div>');
	$(jid).css({

		position : 'absolute',
		'z-index' : '200000000000000',
		'line-height' : $(window).height() + 'px'

	});
	$(jid).width($(window).width());
	$(jid).height($(window).height());
	$(jid).html('<div>' + message + '</div>');
}

function hide_submit_loader() {
	$(jid).remove();
}

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
;(function(d) {
	var k = d.scrollTo = function(a, i, e) {
		d(window).scrollTo(a, i, e)
	};
	k.defaults = {
		axis : 'xy',
		duration : parseFloat(d.fn.jquery) >= 1.3 ? 0 : 1
	};
	k.window = function(a) {
		return d(window)._scrollable()
	};
	d.fn._scrollable = function() {
		return this.map(function() {
			var a = this, i = !a.nodeName || d.inArray(a.nodeName.toLowerCase(), ['iframe', '#document', 'html', 'body']) != -1;
			if (!i)
				return a;
			var e = (a.contentWindow || a).document || a.ownerDocument || a;
			return d.browser.safari || e.compatMode == 'BackCompat' ? e.body : e.documentElement
		})
	};
	d.fn.scrollTo = function(n, j, b) {
		if ( typeof j == 'object') {
			b = j;
			j = 0
		}
		if ( typeof b == 'function')
			b = {
				onAfter : b
			};
		if (n == 'max')
			n = 9e9;
		b = d.extend({}, k.defaults, b);
		j = j || b.speed || b.duration;
		b.queue = b.queue && b.axis.length > 1;
		if (b.queue)
			j /= 2;
		b.offset = p(b.offset);
		b.over = p(b.over);
		return this._scrollable().each(function() {
			var q = this, r = d(q), f = n, s, g = {}, u = r.is('html,body');
			switch(typeof f) {
				case'number':
				case'string':
					if (/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f)) {
						f = p(f);
						break
					}
					f = d(f, this);
				case'object':
					if (f.is || f.style)
						s = ( f = d(f)).offset()
			}
			d.each(b.axis.split(''), function(a, i) {
				var e = i == 'x' ? 'Left' : 'Top', h = e.toLowerCase(), c = 'scroll' + e, l = q[c], m = k.max(q, i);
				if (s) {
					g[c] = s[h] + ( u ? 0 : l - r.offset()[h]);
					if (b.margin) {
						g[c] -= parseInt(f.css('margin' + e)) || 0;
						g[c] -= parseInt(f.css('border' + e + 'Width')) || 0
					}
					g[c] += b.offset[h] || 0;
					if (b.over[h])
						g[c] += f[i=='x'?'width':'height']() * b.over[h]
				} else {
					var o = f[h];
					g[c] = o.slice && o.slice(-1) == '%' ? parseFloat(o) / 100 * m : o
				}
				if (/^\d+$/.test(g[c]))
					g[c] = g[c] <= 0 ? 0 : Math.min(g[c], m);
				if (!a && b.queue) {
					if (l != g[c])
						t(b.onAfterFirst);
					delete g[c]
				}
			});
			t(b.onAfter);
			function t(a) {
				r.animate(g, j, b.easing, a &&
				function() {
					a.call(this, n, b)
				})

			}

		}).end()
	};
	k.max = function(a, i) {
		var e = i == 'x' ? 'Width' : 'Height', h = 'scroll' + e;
		if (!d(a).is('html,body'))
			return a[h] - d(a)[e.toLowerCase()]();
		var c = 'client' + e, l = a.ownerDocument.documentElement, m = a.ownerDocument.body;
		return Math.max(l[h], m[h]) - Math.min(l[c], m[c])
	};
	function p(a) {
		return typeof a == 'object' ? a : {
			top : a,
			left : a
		}
	}

})(jQuery);
