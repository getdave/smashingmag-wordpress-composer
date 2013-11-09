window.matchMedia = window.matchMedia || function (e, t) {
	var n, r = e.documentElement, i = r.firstElementChild || r.firstChild, s = e.createElement("body"), o = e.createElement("div");
	o.id = "mq-test-1";
	o.style.cssText = "position:absolute;top:-100em";
	s.style.background = "none";
	s.appendChild(o);
	return function (e) {
		o.innerHTML = '­<style media="' + e + '"> #mq-test-1 { width: 42px; }</style>';
		r.insertBefore(s, i);
		n = o.offsetWidth == 42;
		r.removeChild(s);
		return{matches: n, media: e}
	}
}(document);
(function (e) {
	function S() {
		b(true)
	}

	e.respond = {};
	respond.update = function () {
	};
	respond.mediaQueriesSupported = e.matchMedia && e.matchMedia("only all").matches;
	if (respond.mediaQueriesSupported) {
		return
	}
	var t = e.document, n = t.documentElement, r = [], i = [], s = [], o = {}, u = 30, a = t.getElementsByTagName("head")[0] || n, f = t.getElementsByTagName("base")[0], l = a.getElementsByTagName("link"), c = [], h = function () {
		var t = l, n = t.length, r = 0, i, s, u, a;
		for (; r < n; r++) {
			i = t[r], s = i.href, u = i.media, a = i.rel && i.rel.toLowerCase() === "stylesheet";
			if (!!s && a && !o[s]) {
				if (i.styleSheet && i.styleSheet.rawCssText) {
					d(i.styleSheet.rawCssText, s, u);
					o[s] = true
				} else {
					if (!/^([a-zA-Z:]*\/\/)/.test(s) && !f || s.replace(RegExp.$1, "").split("/")[0] === e.location.host) {
						c.push({href: s, media: u})
					}
				}
			}
		}
		p()
	}, p = function () {
		if (c.length) {
			var e = c.shift();
			w(e.href, function (t) {
				d(t, e.href, e.media);
				o[e.href] = true;
				p()
			})
		}
	}, d = function (e, t, n) {
		var s = e.match(/@media[^\{]+\{([^\{\}]*\{[^\}\{]*\})+/gi), o = s && s.length || 0, t = t.substring(0, t.lastIndexOf("/")), u = function (e) {
			return e.replace(/(url\()['"]?([^\/\)'"][^:\)'"]+)['"]?(\))/g, "$1" + t + "$2$3")
		}, a = !o && n, f = 0, l, c, h, p, d;
		if (t.length) {
			t += "/"
		}
		if (a) {
			o = 1
		}
		for (; f < o; f++) {
			l = 0;
			if (a) {
				c = n;
				i.push(u(e))
			} else {
				c = s[f].match(/@media *([^\{]+)\{([\S\s]+?)$/) && RegExp.$1;
				i.push(RegExp.$2 && u(RegExp.$2))
			}
			p = c.split(",");
			d = p.length;
			for (; l < d; l++) {
				h = p[l];
				r.push({media: h.split("(")[0].match(/(only\s+)?([a-zA-Z]+)\s?/) && RegExp.$2 || "all", rules: i.length - 1, hasquery: h.indexOf("(") > -1, minw: h.match(/\(min\-width:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/) && parseFloat(RegExp.$1) + (RegExp.$2 || ""), maxw: h.match(/\(max\-width:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/) && parseFloat(RegExp.$1) + (RegExp.$2 || "")})
			}
		}
		b()
	}, v, m, g = function () {
		var e, r = t.createElement("div"), i = t.body, s = false;
		r.style.cssText = "position:absolute;font-size:1em;width:1em";
		if (!i) {
			i = s = t.createElement("body");
			i.style.background = "none"
		}
		i.appendChild(r);
		n.insertBefore(i, n.firstChild);
		e = r.offsetWidth;
		if (s) {
			n.removeChild(i)
		} else {
			i.removeChild(r)
		}
		e = y = parseFloat(e);
		return e
	}, y, b = function (e) {
		var o = "clientWidth", f = n[o], c = t.compatMode === "CSS1Compat" && f || t.body[o] || f, h = {}, p = l[l.length - 1], d = (new Date).getTime();
		if (e && v && d - v < u) {
			clearTimeout(m);
			m = setTimeout(b, u);
			return
		} else {
			v = d
		}
		for (var w in r) {
			var E = r[w], S = E.minw, x = E.maxw, T = S === null, N = x === null, C = "em";
			if (!!S) {
				S = parseFloat(S) * (S.indexOf(C) > -1 ? y || g() : 1)
			}
			if (!!x) {
				x = parseFloat(x) * (x.indexOf(C) > -1 ? y || g() : 1)
			}
			if (!E.hasquery || (!T || !N) && (T || c >= S) && (N || c <= x)) {
				if (!h[E.media]) {
					h[E.media] = []
				}
				h[E.media].push(i[E.rules])
			}
		}
		for (var w in s) {
			if (s[w] && s[w].parentNode === a) {
				a.removeChild(s[w])
			}
		}
		for (var w in h) {
			var k = t.createElement("style"), L = h[w].join("\n");
			k.type = "text/css";
			k.media = w;
			a.insertBefore(k, p.nextSibling);
			if (k.styleSheet) {
				k.styleSheet.cssText = L
			} else {
				k.appendChild(t.createTextNode(L))
			}
			s.push(k)
		}
	}, w = function (e, t) {
		var n = E();
		if (!n) {
			return
		}
		n.open("GET", e, true);
		n.onreadystatechange = function () {
			if (n.readyState != 4 || n.status != 200 && n.status != 304) {
				return
			}
			t(n.responseText)
		};
		if (n.readyState == 4) {
			return
		}
		n.send(null)
	}, E = function () {
		var e = false;
		try {
			e = new XMLHttpRequest
		} catch (t) {
			e = new ActiveXObject("Microsoft.XMLHTTP")
		}
		return function () {
			return e
		}
	}();
	h();
	respond.update = h;
	if (e.addEventListener) {
		e.addEventListener("resize", S, false)
	} else if (e.attachEvent) {
		e.attachEvent("onresize", S)
	}
})(this);
jQuery(document).ready(function (e) {
	e("a[href=#scroll-top]").click(function () {
		e("html, body").animate({scrollTop: 0}, "slow");
		return false
	})
});
(function (e) {
	e.simplePlaceholder = {placeholderClass: null, hidePlaceholder: function () {
		var t = e(this);
		if (t.val() == t.attr("placeholder") && t.data(e.simplePlaceholder.placeholderData)) {
			t.val("").removeClass(e.simplePlaceholder.placeholderClass).data(e.simplePlaceholder.placeholderData, false)
		}
	}, showPlaceholder: function () {
		var t = e(this);
		if (t.val() == "") {
			t.val(t.attr("placeholder")).addClass(e.simplePlaceholder.placeholderClass).data(e.simplePlaceholder.placeholderData, true)
		}
	}, preventPlaceholderSubmit: function () {
		e(this).find(".simple-placeholder").each(function (t) {
			var n = e(this);
			if (n.val() == n.attr("placeholder") && n.data(e.simplePlaceholder.placeholderData)) {
				n.val("")
			}
		});
		return true
	}};
	e.fn.simplePlaceholder = function (t) {
		if (document.createElement("input").placeholder == undefined) {
			var n = {placeholderClass: "placeholding", placeholderData: "simplePlaceholder.placeholding"};
			if (t)e.extend(n, t);
			e.extend(e.simplePlaceholder, n);
			this.each(function () {
				var t = e(this);
				t.focus(e.simplePlaceholder.hidePlaceholder);
				t.blur(e.simplePlaceholder.showPlaceholder);
				t.data(e.simplePlaceholder.placeholderData, false);
				if (t.val() == "") {
					t.val(t.attr("placeholder"));
					t.addClass(e.simplePlaceholder.placeholderClass);
					t.data(e.simplePlaceholder.placeholderData, true)
				}
				t.addClass("simple-placeholder");
				e(this.form).submit(e.simplePlaceholder.preventPlaceholderSubmit)
			})
		}
		return this
	}
})(jQuery);
(function (e) {
	"use strict";
	e.fn.fitVids = function (t) {
		var n = {customSelector: null};
		var r = document.createElement("div"), i = document.getElementsByTagName("base")[0] || document.getElementsByTagName("script")[0];
		r.className = "fit-vids-style";
		r.innerHTML = "­<style>               .fluid-width-video-wrapper {                 width: 100%;                              position: relative;                       padding: 0;                            }                                                                                   .fluid-width-video-wrapper iframe,        .fluid-width-video-wrapper object,        .fluid-width-video-wrapper embed {           position: absolute;                       top: 0;                                   left: 0;                                  width: 100%;                              height: 100%;                          }                                       </style>";
		i.parentNode.insertBefore(r, i);
		if (t) {
			e.extend(n, t)
		}
		return this.each(function () {
			var t = ["iframe[src*='player.vimeo.com']", "iframe[src*='www.youtube.com']", "iframe[src*='www.youtube-nocookie.com']", "iframe[src*='fast.wistia.com']", "embed"];
			if (n.customSelector) {
				t.push(n.customSelector)
			}
			var r = e(this).find(t.join(","));
			r.each(function () {
				var t = e(this);
				if (this.tagName.toLowerCase() === "embed" && t.parent("object").length || t.parent(".fluid-width-video-wrapper").length) {
					return
				}
				var n = this.tagName.toLowerCase() === "object" || t.attr("height") && !isNaN(parseInt(t.attr("height"), 10)) ? parseInt(t.attr("height"), 10) : t.height(), r = !isNaN(parseInt(t.attr("width"), 10)) ? parseInt(t.attr("width"), 10) : t.width(), i = n / r;
				if (!t.attr("id")) {
					var s = "fitvid" + Math.floor(Math.random() * 999999);
					t.attr("id", s)
				}
				t.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top", i * 100 + "%");
				t.removeAttr("height").removeAttr("width")
			})
		})
	}
})(jQuery);
(function (e) {
	var t = e(".main-nav li.current-menu-item a").html();
	t = e(".main-nav li.current_page_item a").html();
	if (e("span").hasClass("custom-mobile-menu-title")) {
		t = e("span.custom-mobile-menu-title").html()
	} else if (typeof t == "undefined" || t === null) {
		if (e("body").hasClass("home")) {
			if (e("#logo span").hasClass("site-name")) {
				t = e("#logo .site-name a").html()
			} else {
				t = e("#logo img").attr("alt")
			}
		} else {
			if (e("body").hasClass("woocommerce")) {
				t = e("h1.page-title").html()
			} else if (e("body").hasClass("woocommerce")) {
				t = e("h1.entry-title").html()
			} else if (e("body").hasClass("archive")) {
				t = e("h6.title-archive").html()
			} else if (e("body").hasClass("search-results")) {
				t = e("h6.title-search-results").html()
			} else if (e("body").hasClass("page-template-blog-excerpt-php")) {
				t = e(".current_page_item").text()
			} else if (e("body").hasClass("page-template-blog-php")) {
				t = e(".current_page_item").text()
			} else if (e("h1").hasClass("post-title")) {
				t = e("h1.post-title").html()
			} else {
				t = " "
			}
		}
	}
	e(".main-nav").append('<a id="responsive_menu_button"></a>');
	e(".main-nav").prepend('<div id="responsive_current_menu_item">' + t + "</div>");
	e("a#responsive_menu_button, #responsive_current_menu_item").click(function () {
		e(".js .main-nav .menu").slideToggle(function () {
			if (e(this).is(":visible")) {
				e("a#responsive_menu_button").addClass("responsive-toggle-open")
			} else {
				e("a#responsive_menu_button").removeClass("responsive-toggle-open");
				e(".js .main-nav .menu").removeAttr("style")
			}
		})
	})
})(jQuery);
(function (e) {
	e("html").click(function () {
		if (e("a#responsive_menu_button").hasClass("responsive-toggle-open")) {
			e(".js .main-nav .menu").slideToggle(function () {
				e("a#responsive_menu_button").removeClass("responsive-toggle-open");
				e(".js .main-nav .menu").removeAttr("style")
			})
		}
	})
})(jQuery);
jQuery(".main-nav").click(function (e) {
	var t = window.location.pathname;
	if (t != "/wp-admin/customize.php") {
		e.stopPropagation()
	}
});
jQuery(function () {
	jQuery("input[placeholder], textarea[placeholder]").simplePlaceholder()
});
jQuery(document).ready(function () {
	jQuery("#wrapper").fitVids()
})