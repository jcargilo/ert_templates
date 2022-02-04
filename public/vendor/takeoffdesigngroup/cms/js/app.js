"use strict";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    error: function(event, request, options, error) {
        switch (event.status) {
            case 401: window.location.replace(window.location.href); break;
            case 500:
                KTApp.unblockPage('.page-content');
                
                toastr.options.closeButton = true;
                toastr.options.positionClass = "toast-top-center";
                toastr.options.preventDuplicates = false;
                toastr.options.timeOut = 0;
                toastr.options.extendedTimeOut = 0;
                toastr.error("An unexpected error has occurred.  A report of this event has been sent to our support team.  If this issue persists, please contact an administrator.");
                break;

        }
    }
});

(function($,undefined){
    '$:nomunge'; // Used by YUI compressor.
  
    $.fn.serializeObject = function(){
        var obj = {};
    
        $.each( this.serializeArray(), function(i,o){
            var n = o.name,
            v = o.value;
        
            obj[n] = obj[n] === undefined ? v
                : $.isArray( obj[n] ) ? obj[n].concat( v )
                : [ obj[n], v ];
        });
    
        return obj;
    };

    /**
     * Debounce and throttle function's decorator plugin 1.0.5
     *
     * Copyright (c) 2009 Filatov Dmitry (alpha@zforms.ru)
     * Dual licensed under the MIT and GPL licenses:
     * http://www.opensource.org/licenses/mit-license.php
     * http://www.gnu.org/licenses/gpl.html
     *
     */

    $.extend({
        debounce : function(fn, timeout, invokeAsap, ctx) {
            if(arguments.length == 3 && typeof invokeAsap != 'boolean') {
                ctx = invokeAsap;
                invokeAsap = false;
            }

            var timer;

            return function() {
                var args = arguments;
                ctx = ctx || this;

                invokeAsap && !timer && fn.apply(ctx, args);

                clearTimeout(timer);

                timer = setTimeout(function() {
                    !invokeAsap && fn.apply(ctx, args);
                    timer = null;
                }, timeout);
            };
        },

        throttle : function(fn, timeout, ctx) {
            var timer, args, needInvoke;

            return function() {
                args = arguments;
                needInvoke = true;
                ctx = ctx || this;

                if(!timer) {
                    (function() {
                        if(needInvoke) {
                            fn.apply(ctx, args);
                            needInvoke = false;
                            timer = setTimeout(arguments.callee, timeout);
                        }
                        else {
                            timer = null;
                        }
                    })();
                }
            };
        }
    });
})(jQuery);

$('#filter').on('keyup', $.debounce(function(e) {
    if (e.which !== 0 &&
        !e.ctrlKey && !e.metaKey && !e.altKey) {
        return newSearch();
    }
}, 300));

var Login = function() {
    var signin = $('#signin');
    var forgot = $('#forgot-password');

    var showErrorMsg = function(form, type, msg) {
        var alert = $('<div class="alert alert-solid-' + type + ' alert-dismissible" role="alert">\
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\
			<span></span>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form);
        KTUtil.animateClass(alert[0], 'fadeIn animated');
        alert.find('span').html(msg);
    }

    // Private Functions
    var displaySignInForm = function() {
        signin.removeClass('hidden');
        forgot.addClass('hidden');
        KTUtil.animateClass(signin[0], 'flipInX animated');
    }

    var displayForgotForm = function() {
        signin.addClass('hidden');
        forgot.removeClass('hidden');
        KTUtil.animateClass(forgot[0], 'flipInX animated');

    }

    var handleFormSwitch = function() {
        $('#forgot').click(function(e) {
            e.preventDefault();
            displayForgotForm();
        });

        $('#forgot_cancel').click(function(e) {
            e.preventDefault();
            displaySignInForm();
        });
    }

    var handleSignInFormSubmit = function() {
        $('.login__form button').on('click', function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');           

            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: '',
                success: function(response, status, xhr, $form) {
                	window.location.reload();
                }, error: function(response) {
                    btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false); // remove
                    showErrorMsg(signin, 'danger', 'These credentials do not match our records.');
                }
            });
        });
    }

    var handleForgotFormSubmit = function() {
        $('.forgot__form button').click(function(e) {
            e.preventDefault();

            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: form.attr('action'),
                success: function(response, status, xhr, $form) { 
                	// similate 2s delay
                	setTimeout(function() {
                		btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false); // remove
	                    form.clearForm(); // clear form
	                    form.validate().resetForm(); // reset validation states

                        if (response.success) {
                            // display signup form
                            displaySignInForm();
                            signin.clearForm();
                            signin.validate().resetForm();

                            showErrorMsg(signin, 'success', response.message);
                        } else {
                            forgot.clearForm();
                            forgot.validate().resetForm();
                            showErrorMsg(form, 'danger', response.message);
                        }
                	}, 500);
                }
            });
        });
    }

    // Public Functions
    return {
        // public functions
        init: function() {
            handleFormSwitch();
            handleSignInFormSubmit();
            handleForgotFormSubmit();
        }
    };
}();

$(function() {
    if ($('#template-login').length > 0) Login.init(); 
});