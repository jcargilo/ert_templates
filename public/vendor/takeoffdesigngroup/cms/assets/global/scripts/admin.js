var closeModal = true // prevents modal from being closed by filemanager selection

;(function ($, undefined) {
  '$:nomunge' // Used by YUI compressor.

  $.fn.serializeObject = function () {
    var obj = {}

    $.each(this.serializeArray(), function (i, o) {
      var n = o.name,
        v = o.value

      obj[n] = obj[n] === undefined ? v : $.isArray(obj[n]) ? obj[n].concat(v) : [obj[n], v]
    })

    return obj
  }

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
    debounce: function (fn, timeout, invokeAsap, ctx) {
      if (arguments.length == 3 && typeof invokeAsap != 'boolean') {
        ctx = invokeAsap
        invokeAsap = false
      }

      var timer

      return function () {
        var args = arguments
        ctx = ctx || this

        invokeAsap && !timer && fn.apply(ctx, args)

        clearTimeout(timer)

        timer = setTimeout(function () {
          !invokeAsap && fn.apply(ctx, args)
          timer = null
        }, timeout)
      }
    },

    throttle: function (fn, timeout, ctx) {
      var timer, args, needInvoke

      return function () {
        args = arguments
        needInvoke = true
        ctx = ctx || this

        if (!timer) {
          ;(function () {
            if (needInvoke) {
              fn.apply(ctx, args)
              needInvoke = false
              timer = setTimeout(arguments.callee, timeout)
            } else {
              timer = null
            }
          })()
        }
      }
    },
  })
})(jQuery)

$(function () {
  $('.fancybox').fancybox()
  $('.iframe-btn').fancybox({
    width: 900,
    minHeight: 400,
    type: 'iframe',
    autoSize: true,
  })

  if ($('.nav-tabs').length > 0) {
    // Javascript to enable link to tab
    var hash = document.location.hash
    hash = hash.replace('_=_', '')
    var prefix = ''
    if (hash) {
      $('.nav-tabs a[href=' + hash.replace(prefix, '') + ']').tab('show')
    }

    //activate latest tab, if it exists:
    var lastTab = localStorage.getItem('lastTab')
    if (lastTab && lastTab !== 'null' && lastTab !== '#' && hash === '') $('a[href=' + lastTab.replace(prefix, '') + ']').tab('show')
    else {
      if (hash !== '#' && hash !== '') {
        localStorage.setItem('lastTab', hash)
        $('a[href=' + hash.replace(prefix, '') + ']').tab('show')
      } else {
        // Set the first tab if cookie do not exist
        let tab = 'a[data-toggle="tab"]:first'
        if (typeof defaultTab !== 'undefined') tab = 'a[href="#' + defaultTab + '"]'

        $(tab).tab('show').attr('aria-expanded', 'true').closest('li').addClass('active')
      }
    }

    /*$('a[data-toggle="tab"]').not('.no-scroll').on('shown.bs.tab', function(e){
            localStorage.setItem('lastTab', e.target.hash);
            
            // Change hash for page-reload
            var scrollmem = $('html,body').scrollTop();
            window.location.hash = e.target.hash.replace("#", "#" + prefix);
            $('html,body').scrollTop(scrollmem);
        });*/
  }

  $('a[name=back]').on('click', function (e) {
    e.preventDefault()
    window.history.back()
  })

  $('.collapse').on('show.bs.collapse', function () {
    $('.collapse.in').collapse('hide')
  })

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
    error: function (event, request, options, error) {
      switch (event.status) {
        case 401:
          window.location.replace(window.location.href)
          break
        case 500:
          App.unblockUI('.modal form')
          App.unblockUI('.page-content')
          App.unblockUI()

          setTimeout(function () {
            $('.messages')
              .removeClass('hidden')
              .find('.msg .wrap')
              .text('An unexpected error has occurred.  If this persists, please contact an administrator.')
          }, 500)
          break
      }
    },
  })

  UIConfirmations.init()
})

function fmSetLink(id, $url) {
  document.getElementById(id).value = $url
  $.fancybox.close()
}

$(window).load(function () {
  if (typeof overrideLoader == 'undefined') stopLoading()
})

var UIConfirmations = (function () {
  var handleConfirmations = function () {
    $(document).on('confirmed.bs.confirmation', 'a[data-method=delete]', function () {
      var target = $(this).attr('data-href')
      var method = $(this).data('method')
      var token = $('meta[name=csrf-token]')
      var html = '<form action="' + target + '" method="POST">'
      html += '<input type="hidden" name="_method" value="' + method + '">'
      html += '<input type="hidden" name="_token" value="' + token.attr('content') + '">'
      html += '</form>'
      $(html).appendTo('body').submit()
    })
  }

  return {
    //main function to initiate the module
    init: function () {
      $(document).on('click', 'a[data-toggle=confirmation]', function () {
        $(this).confirmation({
          container: 'body',
          btnOkClass: 'btn btn-sm btn-success',
          btnCancelClass: 'btn btn-sm btn-danger',
        })
        $(this).confirmation('show')
      })

      handleConfirmations()
    },
  }
})()

var ComponentsBootstrapSelect = (function () {
  var handleBootstrapSelect = function () {
    $('.bs-select').selectpicker({
      iconBase: 'fa',
      tickIcon: 'fa-check',
    })
  }
  return {
    //main function to initiate the module
    init: function () {
      handleBootstrapSelect()
    },
  }
})()

var ComponentsColorPickers = (function () {
  var handleColorPicker = function () {
    if (!jQuery().colorpicker) {
      return
    }
    $('.colorpicker').colorpicker({
      format: 'hex',
    })
    $('.colorpicker-rgba').colorpicker()
  }

  var handleMiniColors = function () {
    $('.colorpicker').each(function () {
      $(this).minicolors({
        control: $(this).attr('data-control') || 'hue',
        defaultValue: $(this).attr('data-defaultValue') || '',
        inline: $(this).attr('data-inline') === 'true',
        letterCase: $(this).attr('data-letterCase') || 'lowercase',
        opacity: $(this).attr('data-opacity'),
        position: $(this).attr('data-position') || 'bottom left',
        theme: 'bootstrap',
      })
    })
  }

  return {
    //main function to initiate the module
    init: function () {
      handleMiniColors()
      handleColorPicker()
    },
  }
})()

var FormValidation = {
  activeValidator: null,
  product: {
    validate: function () {
      var form = $('form')
      FormValidation.activeValidator = form.validate({
        rules: {
          title: { required: true, maxlength: 256 },
          sku: { required: true, maxlength: 256 },
          image: { required: true },
          occasions: { required: true },
          alt_text: { required: true },
          price_1_dozen: { required: true },
        },
      })
    },
  },
  page: {
    validate: function () {
      var form = $('#newSection')
      FormValidation.activeValidator = form.validate({
        rules: {
          sectionTitle: { maxlength: 256, required: true },
        },
      })
    },
  },
  galleries: {
    gallery: {
      validate: function () {
        var form = $('form')
        FormValidation.activeValidator = form.validate({
          rules: {
            title: { maxlength: 256, required: true },
          },
        })
      },
    },
    album: {
      validate: function () {
        var form = $('form')
        FormValidation.activeValidator = form.validate({
          rules: {
            title: { maxlength: 256, required: true },
            permalink: {
              required: {
                depends: function (element) {
                  return $(element).length > 0
                },
              },
            },
            slug: {
              required: {
                depends: function (element) {
                  return $('#slug').length > 0
                },
              },
            },
          },
        })
      },
    },
    photo: {
      validate: function () {
        var form = $('form')
        FormValidation.activeValidator = form.validate({
          rules: {
            title: { maxlength: 256, required: true },
            image: { required: true },
          },
        })
      },
    },
  },
  event: {
    validate: function () {
      var form = $('#event')
      FormValidation.activeValidator = form.validate({
        rules: {
          title: { maxlength: 256, required: true },
          location: { maxlength: 256, required: true },
          start_date: { required: true },
        },
      })
    },
  },
  testimonial: {
    validate: function () {
      var form = $('form')
      FormValidation.activeValidator = form.validate({
        rules: {
          body: { required: true },
          customer: { maxlength: 256, required: true },
          line2: { maxlength: 256 },
          line3: { maxlength: 256 },
        },
      })
    },
  },
  user: {
    validate: function () {
      var form = $('form')
      FormValidation.activeValidator = form.validate({
        rules: {
          email: { maxlength: 256, required: true },
          password: {
            required: {
              depends: function (element) {
                return $('#create').length > 0
              },
            },
            minlength: 6,
            password: false,
          },
          password_confirmation: {
            required: {
              depends: function (element) {
                return $('#create').length > 0 || $('#password').val() != ''
              },
            },
            minlength: 6,
            password: false,
            equalTo: '#password',
          },
          'roles[]': {
            required: {
              depends: function (element) {
                return $('.roles').length == 0
              },
            },
          },
        },
        messages: {
          'roles[]': { required: 'Please choose at least one.' },
        },
      })
    },
  },
  site: {
    validate: function () {
      var form = $('form')
      FormValidation.activeValidator = form.validate({
        rules: {
          title: { maxlength: 256, required: true },
          domain: { maxlength: 256, required: true },
          email: { maxlength: 256, required: true },
        },
      })
    },
  },
  product: {
    validate: function () {
      var form = $('#productForm')
      FormValidation.activeValidator = form.validate({
        rules: {
          upc_code: { maxlength: 256, required: true },
          item_number: { required: true, number: true },
          title: { maxlength: 256, required: true },
          category_id: { required: true },
        },
      })
    },
    category: {
      validate: function () {
        var form = $('#categoryForm')
        FormValidation.activeValidator = form.validate({
          rules: {
            title: { maxlength: 256, required: true },
          },
        })
      },
    },
    reorder: {
      validate: function () {
        var form = $('#reorderForm')
        FormValidation.activeValidator = form.validate({
          rules: {
            product_id: { required: true },
            quantity: { required: true, number: true },
            unit_cost: { required: true, currency: ['$', false] },
            deposit_amount: { currency: ['$', false] },
            balance_amount: { currency: ['$', false] },
          },
        })
      },
    },
  },
  init: function () {
    $.validator.setDefaults({
      errorElement: 'span',
      errorClass: 'help-block help-block-error',
      focusInvalid: false,
      ignore: ':hidden:not(.bs-select,.validate)',

      invalidHandler: function (form, validator) {
        var messages = $(form.currentTarget).find('.messages')
        var errors = validator.numberOfInvalids()

        if (errors) {
          messages
            .removeClass('hidden')
            .find('.text-error > span')
            .html(errors == 1 ? 'was an error' : 'were some errors')
        } else {
          messages.addClass('hidden')
        }
        App.scrollTo(messages, -200)
      },

      errorPlacement: function (error, element) {
        var container = $(element).closest('.form-group')

        if (element.hasClass('bs-select')) error.insertAfter(container.find('.bootstrap-select'))
        else if (element.hasClass('toggle')) error.insertAfter(container.find('.btn-group'))
        else if (element.hasClass('multi-select')) error.insertAfter(container.find('.ms-container'))
        else if (element.attr('type') === 'file') container.find('.validate').append(error)
        else if (container.has('.input-group-addon').length) error.insertAfter(container.find('.input-group'))
        else error.insertAfter(element)
      },

      highlight: function (element) {
        var container = $(element).closest('.form-group')
        container.removeClass('has-success').addClass('has-error')
        container.find('i.form-control-feedback').remove()

        if (!$(element).is(':radio, :checkbox, .btn.btn-default, .multi-select, input:file, :hidden:not(.bs-select)'))
          $('<i class="form-control-feedback fa fa-stack fa-warning"></i>').insertAfter($(element))
        else if ($(element).attr('type') === 'file')
          container.find('.form-control').append('<i class="form-control-feedback fa fa-stack fa-warning"></i>')
      },

      unhighlight: function (element) {
        var container = $(element).closest('.form-group')
        container.removeClass('has-error').addClass('has-success')
        container.find('i.form-control-feedback').remove()
        container.find('.help-block').remove()

        if ($(element).hasClass('btn', 'active')) element = null

        if (!$(element).is(':radio, :checkbox, .btn.btn-default, .multi-select, input:file, :hidden:not(.bs-select)'))
          $('<i class="form-control-feedback fa fa-stack fa-check"></i>').insertAfter($(element))
        else if ($(element).attr('type') === 'file')
          container.find('.form-control').append('<i class="form-control-feedback fa fa-stack fa-check"></i>')
        else if ($(element).hasClass('toggle'))
          $('<i class="form-control-feedback fa fa-stack fa-check"></i>').insertAfter(container.find('.btn-group'))
      },

      onfocusout: function (element) {
        $(element).valid()
      },
    })

    jQuery.validator.addMethod(
      'zipcode',
      function (value, element) {
        return this.optional(element) || /^\d{5}(?:-\d{4})?$/.test(value)
      },
      'Please provide a valid zipcode.',
    )

    jQuery.validator.addMethod(
      'password',
      function (value, element) {
        return this.optional(element) || /^.*((?=.*\d)(?=.*[A-Z])(?=.*\W).{8,256}).*$/.test(value)
      },
      'Your password must be at least 8 characters long, contain at least 1 uppercase letter, a number, and a special character (!@#$%^&*-)',
    )

    jQuery.validator.addMethod(
      'percent',
      function (value, element) {
        return this.optional(element) || /^(\d+(\.\d+)?%)$/.test(value)
      },
      'Please enter a valid number.',
    )

    jQuery.validator.addMethod(
      'money',
      function (value, element) {
        var isValidMoney = /^\d{0,4}(\.\d{0,2})?$/.test(value)
        return this.optional(element) || isValidMoney
      },
      'Please enter a valid amount.',
    )

    $('body').on('changed.bs.select', 'select.bs-select:not(.ignore)', function (e) {
      $(e.currentTarget).valid()
    })

    $('.multi-select').on('change', function () {
      $(this).valid()
    })

    $('.form-group .btn-group .btn:not(.dropdown-toggle):not(.add)').on('click', function () {
      $(this).valid()
    })

    $('.form-group .btn-group-horizontal .btn:not(.dropdown-toggle):not(.add)').on('click', function () {
      $(this).valid()
    })
  },
  displayErrors: function (form, errors) {
    // debug
    //console.log(errors);

    form.find('.messages').removeClass('hidden')
    var messages = form.find('.messages')
    messages.removeClass('hidden')
    messages
      .find('.alert-danger')
      .removeClass('hidden')
      .find('.wrap')
      .html(
        'Whoops! There <span>were some errors</span> in your form.  Please review your form below and make corrections where necessary.',
      )
    messages.find('.alert-success').addClass('hidden').find('.wrap').html('')

    $.each(errors, function (key, error) {
      var element = form.find('#' + key)
      var field = element.closest('.form-group')
      var error = $('<span class="help-block">' + error + '</span>')
      field.addClass('has-error')
      field.find('i.form-control-feedback').remove()
      field.find('.help-block').remove()
      if (!element.is(':radio, :checkbox, .multi-select, .control-label'))
        $('<i class="form-control-feedback fa fa-stack fa-warning"></i>').insertAfter(element)

      if (element.hasClass('bs-select')) error.insertAfter(field.find('.bootstrap-select'))
      else if (element.is(':radio, :checkbox')) field.append(error)
      else if (element.hasClass('toggle')) error.insertAfter(element.closest('.btn-group'))
      else if (element.hasClass('multi-select')) error.insertAfter(element.next('.ms-container'))
      else if (element.is('.control-label')) field.append(error.addClass('text-center'))
      else error.insertAfter(element)
    })
  },
  reset: function () {
    FormValidation.activeValidator.resetForm()
  },
}

var Cropper = {
  init: function (baseUrl, destination) {
    $('#avatar-modal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget)
      var modal = $(this)
      modal.find('.avatar-field').val('headshot')
      modal.find('.avatar-options-ratio').val('none')
      modal.find('.avatar-base').val(baseUrl)
      modal.find('.avatar-dst').val(destination)
      $('.avatar-body').find('.main').removeClass('col-md-12').addClass('col-md-9')
      $('.avatar-body').find('.previews').show()
      $('.avatar-body').find('.previews').find('.avatar-preview').removeClass('headshot')
      $('.avatar-btns').find('.col-md-12').removeClass('col-md-12').addClass('col-md-9')
    })
  },
}

$('form').each(function () {
  var thisform = $(this)
  thisform.prepend(
    thisform.find('button.default').clone().css({
      position: 'absolute',
      left: '-999px',
      top: '-999px',
      height: 0,
      width: 0,
    }),
  )
})

$('#filter').on(
  'keyup',
  $.debounce(function (e) {
    if (e.which !== 0 && !e.ctrlKey && !e.metaKey && !e.altKey) {
      return newSearch()
    }
  }, 300),
)

$(document).on('keyup', 'form button.submit', function (e) {
  e.preventDefault()
  if (e.keyCode == 32 || e.which == 32) {
    $(this).closest('form').submit()
  }
})

$(document).on('click', 'form button.submit', function (e) {
  $(this).closest('form').submit()
})

$('button[data-action=save],button[data-action=continue]').on('click', function (e) {
  // set button action as hidden input field.
  $button = $(e.target)
  $('input[name=action]').val($button.data('action'))

  // Disable buttons to prevent double post.
  $(this).find('button').addClass('disabled').prop('disabled', true)
  $(this).find('input[type="submit"]').addClass('disabled').prop('disabled', true)
})

$('select#site').change(function () {
  var id = $(this).find('option:selected').val()
  var domain = $(this).attr('data-uri')
  if (id == 'add') window.location.href = '/' + domain + '/sites/create'
  else if (id > 0) window.location.href = '/' + domain + '/sites/' + id + '/change'
})

$('#category_id').change(function () {
  $('#category').val($(this).children('option').filter(':selected').text())
})

$('#title').friendurl({ id: 'permalink' }) // Format URL
$('#title').keyup(function () {
  // Retrieve formatted URL from form element for display
  $('#permalink').html($('#permalink').val())
})

// In the event of a validation error, make sure permalink is set to current slug value.
$('#permalink-friendly').html($('#permalink').val())

// Prevent jQuery UI dialog from blocking focusin
$(document).on('focusin', function (e) {
  if ($(e.target).closest('.mce-window').length) {
    e.stopImmediatePropagation()
  }
})

function survey(selector, callback) {
  var input = $(selector)
  var oldvalue = input.val()
  setInterval(function () {
    if (input.val() != oldvalue) {
      oldvalue = input.val()
      callback()
    }
  }, 100)
}

function startLoading() {
  $('.page-loader').show()
  $('body').addClass('loading')
}

function stopLoading() {
  $('.page-loader').fadeOut('slow', function () {
    $('.page-body').fadeTo('slow', 1)
    $('body').removeClass('loading')
  })
}

//
// Handles message from ResponsiveFilemanager (for cross domain)
//
function OnMessage(e) {
  var event = e.originalEvent
  // Make sure the sender of the event is trusted
  if (event.data.sender === 'responsivefilemanager') {
    if (event.data.field_id) {
      var fieldID = event.data.field_id
      var url = event.data.url
      $('#' + fieldID)
        .val(url)
        .trigger('change')
      $.fancybox.close()

      // Delete handler of the message from ResponsiveFilemanager
      $(window).off('message', OnMessage)
    }
  }
}

// Handler for a message from ResponsiveFilemanager
$('.iframe-btn').on('click', function () {
  $(window).on('message', OnMessage)
})

function responsive_filemanager_callback(field_id) {
  // make sure filemanager doesn't auto-close active modal on selection.
  closeModal = false
}

function updateImage(selector, value) {
  var image = $(selector)
  if (value != '') {
    image.fadeOut('fast', function () {
      image.attr('src', value)
      image.fadeIn('slow')
    })
  }
}

// Open Dynamic Modal Dialog
function openModalDiv(divname) {
  $('#' + divname).dialog({
    resizable: false,
    bgiframe: true,
    modal: true,
    width: 500,
    autoOpen: false,
    buttons: {
      Ok: function () {
        $(this).dialog('close')
      },
    },
  })
  $('#' + divname).dialog('open')
}

// Close Dynamic Modal Dialog
function closeModalDiv(divname) {
  $('#' + divname).dialog('close')
}
