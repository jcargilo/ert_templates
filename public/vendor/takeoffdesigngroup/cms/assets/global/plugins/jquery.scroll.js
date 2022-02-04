/*!
 * jScroll - jQuery Plugin for Infinite Scrolling / Auto-Paging
 * http://jscroll.com/
 *
 * Copyright 2011-2013, Philip Klauzinski
 * http://klauzinski.com/
 * Dual licensed under the MIT and GPL Version 2 licenses.
 * http://jscroll.com/#license
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @author Philip Klauzinski
 * @version 2.3.5
 * @requires jQuery v1.4.3+
 * @preserve
 */
(function($) {

    'use strict';

    // Define the jscroll namespace and default settings
    $.jscroll = {
        defaults: {
            debug: false,
            delay: 250,
            autoTrigger: true,
            autoTriggerUntil: false,
            loadingHtml: '<small>Loading...</small>',
            loadingFunction: false,
            padding: 0,
            nextSelector: 'a:last',
            container: '',
            contentSelector: '',
            pagingSelector: '',
            callback: false
        }
    };

    // Constructor
    var jScroll = function($e, options) {

        // Private vars and methods
        var _data = $e.data('jscroll'),
            _userOptions = (typeof options === 'function') ? { callback: options } : options,
            _options = $.extend({}, $.jscroll.defaults, _userOptions, _data || {}),
            _isWindow = ($e.css('overflow-y') === 'visible'),
            _$next = $e.find(_options.nextSelector).first(),
            _$window = $(window),
            _$body = $('body'),
            _$scroll = _isWindow ? _$window : $e,
            _nextHref = $.trim(_$next.attr('href') + ' ' + _options.contentSelector),

            // Wrap inner content, if it isn't already
            _wrapInnerContent = function() {
                if (!$e.find('.jscroll-inner').length) {
                    $e.contents().wrapAll('<div class="jscroll-inner" />');
                }
            },

            // Check if a loading image is defined and preload
            _preloadImage = function() {
                var src = $(_options.loadingHtml).filter('img').attr('src');
                if (src) {
                    var image = new Image();
                    image.src = src;
                }
            },

            _setBindings = function() {
                var $next = $e.find(_options.nextSelector).first();
                if (!$next.length) {
                    return;
                }
                if (_options.autoTrigger && (_options.autoTriggerUntil === false || _options.autoTriggerUntil > 0)) {
                    _nextWrap($next);
                    var scrollingBodyHeight = _$body.height() - $e.offset().top,
                        scrollingHeight = ($e.height() < scrollingBodyHeight) ? $e.height() : scrollingBodyHeight,
                        windowHeight = ($e.offset().top - _$window.scrollTop() > 0) ? _$window.height() - ($e.offset().top - $(window).scrollTop()) : _$window.height();

                    // If scroll area is shorter than the window height, initiate observation to load additional data.
                    if (scrollingHeight <= windowHeight) {
                        _observe();
                    }

                    // If scroll element is not bound to the window, load the scroll layer within it.
                    if (!_isWindow)
                        _$scroll = $e.find('.jscroll-inner');

                    // clear previous bindings, intitiate observation binding
                    _$scroll.unbind('.jscroll').bind('scroll.jscroll', function() {
                        return _observe();
                    });
                    if (_options.autoTriggerUntil > 0) {
                        _options.autoTriggerUntil--;
                    }
                } else {
                    _$scroll.unbind('.jscroll');
                    $next.bind('click.jscroll', function() {
                        _nextWrap($next);
                        _load();
                        return false;
                    });
                }
            },

            // Find the next link's parent, or add one, and hide it
            _nextWrap = function($next) {
                var $parent;
                if (_options.pagingSelector) {
                    $next.closest(_options.pagingSelector).hide();
                } else {
                    $parent = $next.parent().not('.jscroll-inner,.jscroll-adding').addClass('jscroll-next-parent').hide();
                    if (!$parent.length) {
                        $next.wrap('<div class="jscroll-next-parent" />').parent().hide();
                    }
                }
            },

            // Remove the jscroll behavior and data from an element
            _destroy = function() {
                return _$scroll.unbind('.jscroll')
                    .removeData('jscroll')
                    .find('.jscroll-inner').children().unwrap()
                    .filter('.jscroll-adding').children().unwrap();
            },

            // Observe the scroll event for when to trigger the next load
            _observe = function() {
                if ($e.is(':visible')) {
                    _wrapInnerContent();
                    var $inner = $e.find('div.jscroll-inner').first(),
                        data = $e.data('jscroll'),
                        borderTopWidth = parseInt($e.css('borderTopWidth'), 10),
                        borderTopWidthInt = isNaN(borderTopWidth) ? 0 : borderTopWidth,
                        iContainerTop = parseInt($e.css('paddingTop'), 10) + borderTopWidthInt,
                        iTopHeight = _isWindow ? _$scroll.scrollTop() : $e.offset().top,
                        innerTop = $inner.length ? $inner.offset().top : 0,
                        iTotalHeight = Math.ceil(iTopHeight - innerTop + _$scroll.height() + iContainerTop);

                    if (!data.waiting && iTotalHeight + _options.padding >= $inner.outerHeight()) {
                        //data.nextHref = $.trim(data.nextHref + ' ' + _options.contentSelector);
                        _debug('info', 'jScroll:', $inner.outerHeight() - iTotalHeight, 'from bottom. Loading next request...');
                        return _load();
                    }
                }
            },

            // Check if the href for the next set of content has been set
            _checkNextHref = function(data) {
                data = data || $e.data('jscroll');
                if (!data || !data.nextHref) {
                    _debug('warn', 'jScroll: nextSelector not found - destroying');
                    _destroy();
                    return false;
                } else {
                    _setBindings();
                    return true;
                }
            },

            // Load the next set of content, if available
            _load = function() {
                var $inner = $e.find('div.jscroll-inner').first(),
                    data = $e.data('jscroll');

                // Add jscroll-adding block where loading indicator and additional data rows will be placed
                // TODO - Add support for any block elementfor jscroll-adding (li, div, etc.)
                data.waiting = true;
                $inner.append('<div class="jscroll-adding" />')
                    .children('.jscroll-adding').last()
                    .html('<div class="jscroll-loading" id="jscroll-loading">' + _options.loadingHtml + '</div>')
                    .promise()
                    .done(function(){
                        if (_options.loadingFunction) {
                            _options.loadingFunction();
                        }
                    });

                // apply delay
                setTimeout(function() {
                    return $e.animate({scrollTop: $inner.outerHeight()}, 0, function() {
                        
                        // load next data set and replace adding block with retrieved data
                        // load() method will replace HTML in selector with output
                        $.get(data.nextHref, function(r, status) {
                            if (status === 'error') {
                                return _destroy();
                            }

                            // Remove temporary div & loading block
                            $(this).remove();
                            $inner.find('.jscroll-adding').remove();

                            // look for inner container, if supplied
                            var container = _options.container ? $inner.find(_options.container) : $inner;

                            // Check if response is an object
                            if (typeof r === "object") {
                                container.append(r.items);
                                
                                // get next page
                                var $next = $inner.find(_options.nextSelector).first();
                                
                                data.nextHref = r.nextPage;
                                $next.attr('href', data.nextHref);
                            }
                            else {
                                container.append(r);

                                // get next page
                                var $next = $inner.find(_options.nextSelector).first();
                                data.nextHref = $next.attr('href') ? $.trim($next.attr('href') + ' ' + _options.contentSelector) : false;
                                $('.jscroll-next-parent', $e).remove(); // Remove the previous next link now that we have a new one
                            }
                            
                            data.waiting = false;
                            _checkNextHref();
                            if (_options.callback) {
                                _options.callback.call(this);
                            }

                            _debug('dir', data);
                        });
                    });
                }, _options.delay);
            },

            // Safe console debug - http://klauzinski.com/javascript/safe-firebug-console-in-javascript
            _debug = function(m) {
                if (_options.debug && typeof console === 'object' && (typeof m === 'object' || typeof console[m] === 'function')) {
                    if (typeof m === 'object') {
                        var args = [];
                        for (var sMethod in m) {
                            if (typeof console[sMethod] === 'function') {
                                args = (m[sMethod].length) ? m[sMethod] : [m[sMethod]];
                                console[sMethod].apply(console, args);
                            } else {
                                console.log.apply(console, args);
                            }
                        }
                    } else {
                        console[m].apply(console, Array.prototype.slice.call(arguments, 1));
                    }
                }
            };

        // Initialization
        $e.data('jscroll', 
            $.extend({}, _data, {
                initialized: true, 
                waiting: false, 
                nextHref: _nextHref
             }));
        _wrapInnerContent();
        _preloadImage();
        _setBindings();

        // Expose API methods via the jQuery.jscroll namespace, e.g. $('sel').jscroll.method()
        $.extend($e.jscroll, {
            destroy: _destroy
        });
        return $e;
    };

    // Define the jscroll plugin method and loop
    $.fn.jscroll = function(m) {
        return this.each(function() {
            var $this = $(this),
                data = $this.data('jscroll'), jscroll;

            // Instantiate jScroll on this element if it hasn't been already
            if (data && data.initialized) {
                return;
            }
            jscroll = new jScroll($this, m);
        });
    };

})(jQuery);