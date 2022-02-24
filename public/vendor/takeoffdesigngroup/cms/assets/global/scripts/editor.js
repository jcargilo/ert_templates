debug = false

select = {
  window: $(window),
  sectionEditor: $('#sectionEditor'),
}
init = {
  initEverything: function () {
    if (select.sectionEditor.length > 0) sectionEditor.init()
  },
}
$(document).ready(function () {
  init.initEverything()
})
sectionEditor = {
  currentSection: null,
  init: function () {
    get_pages_base_url()
    show_submenu_options()

    $('input[name=featured]:radio').change(function () {
      if ($('input[name=featured]:checked').val() == 'image') {
        $('#slideshow').hide()
        $('#image').show()
      } else {
        $('#slideshow').show()
        $('#image').hide()
      }
    })

    $('#parent_category_id').change(function () {
      $('#parent').val($('#parent_category_id').children('option').filter(':selected').val())
      get_pages_base_url()
    })

    $('#redirect').change(function () {
      if ($(this).is(':checked')) {
        $('#page_content').hide()
        $('#redirect_options').fadeIn('slow')
      } else {
        if (!$('#page_content').is(':visible')) {
          $('#page_content').fadeToggle()
          $('#redirect_options').hide()
        }
      }
    })

    $('#redirect_page_id').change(function () {
      if ($(this).find('option:selected').val() == '0') $('#redirect_other').show()
      else {
        $('#redirect_other').hide()
      }
    })

    survey('#fieldID', function () {
      updateImage('#imagePreview', $('#fieldID').val())
    })

    survey('#background_image', function () {
      updateImage('#backgroundPreview', $('#background_image').val())
      $('#remove_image').removeClass('hidden')
    })

    for (let i = 0; i < 5; i++) {
      survey(`#column_bg_image_${i}`, function () {
        updateImage(`#column_bg_preview_${i}`, $(`#column_bg_image_${i}`).val())
        $(`#remove_bg_image_${i}`).removeClass('hidden')
      })
    }

    $('#remove_image').click(function () {
      var placeholder = '/vendor/takeoffdesigngroup/cms/images/noimage.gif'
      $('#background_image').val('')
      updateImage('#backgroundPreview', placeholder)
      $(this).addClass('hidden')
    })

    $('.layoutoption').click(function () {
      var id = $(this).attr('id')
      var layout = id.replace('columns-', '')
      var view = $(`[data-type="row"].active`).data('view')
      $(`#layout_${view}`).val(layout)
      showContentAreas()
    })

    // Trigger Responsive Controls
    $(`[data-attribute=padding]`).on('change', updateResponsiveClasses)
    $(`[data-attribute=margins]`).on('change', updateResponsiveClasses)
    $('[name=full_width]').on('change', updateResponsiveClasses)
    $('[name=vertical_alignment]').on('change', updateResponsiveClasses)

    $('.responsive-picker a').on('click', function (e) {
      var el = $(this)
      if (!el.hasClass('active')) {
        el.parent().find('a').toggleClass('active', false)
        el.toggleClass('active', true)

        // Retrieve current view
        var view = el.data('view')

        // Load layout content settings where applicable
        switch (el.data('type')) {
          case 'row':
            showContentAreas()
            showResponsiveSettings(e.currentTarget, el.data('type'), $(`[name="row_classes_${view}"]`).val())
            break
          case 'column':
            let activeTab = $('#content-tabs').find('li.active a').attr('href').replace('#col', '')
            showResponsiveSettings(e.currentTarget, el.data('type'), $(`[name="column_classes_${view}_${activeTab}"]`).val())
            break
        }
      }
    })

    $('input[name=published]:radio').change(function () {
      if ($('input[name=published]:checked').val() == 'Password Protected') {
        $('#password-select').show()
        $('#password').focus()
      } else $('#password-select').hide()
    })

    $('#sections tbody').sortable({
      items: 'tr:not(#norows)',
      update: function (event, ui) {
        var newOrder = $(this).sortable('toArray').toString()
        $.post('/' + $('#uri').val() + '/pages/sortSection', { order: newOrder })
      },
    })

    $('#background_min_height', '#background_max_height').keypress(function (e) {
      return numericOnly(e)
    })
    $('#background_position_x').keypress(function (e) {
      return numericOnly(e)
    })
    $('#background_position_y').keypress(function (e) {
      return numericOnly(e)
    })
  },
}

$('.content_options input:radio').change(function () {
  var id = $(this).attr('id')
  var parent = $(this).closest('.column')

  // split iterator and selected item (i.e. slideshow_1 becomes an array('slidshow','1'))
  var item = id.split('_')
  displayOption(parent, item[0])
})

$(document).on('click', '#new-section', function () {
  App.startPageLoading({ animate: true })
  sectionEditor.currentSection = null
  editSection()
})

$(document).on('click', '#sections tr .btn.view', function () {
  App.startPageLoading({ animate: true })
  sectionEditor.currentSection = $(this).parents('tr')
  editSection()
})

$('#sectionEditor').on('show.bs.modal', function () {
  if (tinyMCE != undefined && tinyMCE.activeEditor != undefined) {
    for (i in tinyMCE.editors) tinyMCE.editors[i].remove()
  }

  tinyMCE.settings = {
    selector: 'textarea.tinymce',
    plugins: [
      'advlist autolink lists link image charmap print preview hr anchor pagebreak',
      'searchreplace wordcount visualblocks visualchars code fullscreen',
      'insertdatetime media nonbreaking save table directionality',
      'emoticons template paste',
      window.shortcodes != undefined ? 'shortcodes' : '',
    ],
    // content_css: css,
    style_formats: [
      {
        title: 'Headings',
        items: [
          { title: 'Heading 1', block: 'h1' },
          { title: 'Heading 2', block: 'h2' },
          { title: 'Heading 3', block: 'h3' },
          { title: 'Heading 4', block: 'h4' },
          { title: 'Heading 5', block: 'h5' },
          { title: 'Heading (Banner)', block: 'div', classes: 'heading1' },
        ],
      },
      {
        title: 'Text Sizes',
        items: [
          { title: 'Extra Large', inline: 'span', classes: 'text-2xl' },
          { title: 'Large', inline: 'span', classes: 'text-xl' },
          { title: 'Medium', inline: 'span', classes: 'text-lg' },
          { title: 'Small', inline: 'span', classes: 'text-sm' },
          { title: 'Smaller', inline: 'span', classes: 'text-xs' },
        ],
      },
      { title: 'Colors', items: config.theme.colors },
      { title: 'Buttons', items: config.theme.buttons },
    ],
    templates: config.theme.templates,
    formats: {
      alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-left' },
      aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-center' },
      alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-right' },
    },
    toolbar1:
      'styleselect | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | hr | bullist numlist outdent indent',
    toolbar2: 'shortcodes | link unlink anchor | image media | forecolor backcolor | print preview code',
    height: '600',
    paste_as_text: true,
    image_advtab: true,
    browser_spellcheck: true,
    forced_root_block: 'p', // Set to false to disable.
    convert_urls: 0, // disables converting urls from absolute to relative
    extended_valid_elements: 'i[*],svg[*],defs[*],style[*],path[*]',
    /* fontselect
        font_formats : "Oswald=oswald;"+
            "Cambo=cambo",*/
    setup: function (editor) {
      editor.on('init', function (args) {
        editor = args.target
        editor.on('NodeChange', function (e) {
          if (e && e.element.nodeName.toLowerCase() == 'img') {
            tinyMCE.DOM.setAttribs(e.element, { width: null, height: null })
          }
        })
      })
    },

    file_picker_types: 'image',
    file_picker_callback(callback, value, meta) {
      console.log('callback')
      let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth
      let y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight

      tinymce.activeEditor.windowManager.openUrl({
        url: '/file-manager/tinymce5',
        title: 'Laravel File manager',
        width: x * 0.8,
        height: y * 0.8,
        onMessage: (api, message) => {
          callback(message.content, { text: message.text })
        },
      })
    },
  }

  tinyMCE.execCommand('mceAddEditor', false, 'content_1')
  tinyMCE.execCommand('mceAddEditor', false, 'content_2')
  tinyMCE.execCommand('mceAddEditor', false, 'content_3')
  tinyMCE.execCommand('mceAddEditor', false, 'content_4')
  tinyMCE.execCommand('mceAddEditor', false, 'content_5')

  window.scrollTo(0, 0)

  setTimeout(function () {
    getSection()
  }, 200)
})

$('#sectionEditor').on('shown.bs.modal', function () {
  $('#sectionTitle').focus()
})

$('#sectionEditor').on('hidden.bs.modal', function () {
  App.stopPageLoading()
})

$(document).on('confirmed.bs.confirmation', '#sections tr .btn.delete', function () {
  deleteSection(this)
})

$('#sectionEditor .btn.green').on('click', function () {
  App.startPageLoading({ animate: true })
  updateSection()
})

function numericOnly(e) {
  if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) return false
}

function displayOption(parent, option) {
  // Hide all controls.
  parent.find('.content_editor').hide()
  parent.find('.slideshow_options').hide()
  parent.find('.template_options').hide()

  switch (option) {
    case 'editor':
      parent.find('.content_editor').show()
      break
    case 'slideshow':
      parent.find('.slideshow_options').show()
      break
    case 'template':
      parent.find('.template_options').show()
      break
  }
}

function updateSection() {
  App.blockUI({
    target: '#page_content',
    boxed: true,
  })

  tinyMCE.get('content_1').save()
  tinyMCE.get('content_2').save()
  tinyMCE.get('content_3').save()
  tinyMCE.get('content_4').save()
  tinyMCE.get('content_5').save()

  $.ajax({
    url: '/' + $('#uri').val() + '/pages/updateSection',
    type: 'POST',
    data: $('#newSection').serializeArray(),
    dataType: 'json',
    success: function (response) {
      if (response.error) {
        // Clear previous messages
        $('#message').remove()

        $('#newSection').prepend('<div id="message" class="error"><p>' + response.error.message + '</p></div>')
        $('#sectionTitle').focus()
      } else {
        // Clear previous messages
        $('#message').remove()

        updateSectionsGrid()
        $('#sectionEditor').modal('hide')

        // show update message
        var messages = $('form.page > .messages')
        messages.removeClass('hidden')
        messages.find('.alert-danger').addClass('hidden').find('.wrap').html('')
        messages.find('.alert-success').removeClass('hidden').find('.wrap').html(response.message)
      }

      $('html,body').scrollTop(0)
    },
    error: function (response) {},
  })
}

function editSection() {
  resetSectionForm()
  $('#sectionEditor').modal('show')
}

function deleteSection(e) {
  App.blockUI({
    target: '#page_content',
    boxed: true,
  })

  var section = $(e).closest('tr')

  $.ajax({
    url: '/' + $('#uri').val() + '/pages/deleteSection',
    type: 'POST',
    data: 'id=' + section.attr('data-id'),
    dataType: 'json',
    success: function (response) {
      if (response.error != undefined) {
        var messages = $('form.page > .messages')
        messages.removeClass('hidden')
        messages.find('.alert-danger').removeClass('hidden').find('.wrap').html(response.error.message)
        messages.find('.alert-success').addClass('hidden').find('.wrap').html('')
      } else if (response.errors != undefined) FormValidation.displayErrors(form, response.errors)
      else {
        // show update message
        var messages = $('form.page > .messages')
        messages.removeClass('hidden')
        messages.find('.alert-danger').addClass('hidden').find('.wrap').html('')
        messages.find('.alert-success').removeClass('hidden').find('.wrap').html(response.message)

        // remove row
        section.fadeOut()
      }

      App.unblockUI('#page_content')
    },
    error: function ($response) {},
  })
}

function getSection() {
  if (sectionEditor.currentSection !== null)
    $.ajax({
      url: '/' + $('#uri').val() + '/pages/getSection',
      type: 'GET',
      data: 'sectionId=' + $(sectionEditor.currentSection).attr('data-id'),
      dataType: 'json',
      success: function (data) {
        $('#sectionId').val($(sectionEditor.currentSection).attr('data-id'))
        $('#sectionTitle').val(data.title)
        $('#custom_row_classes').val(data.metadata ? data.metadata.custom_css : '')
        $('[name="row_classes_mobile"]').val(data.metadata ? JSON.stringify(data.metadata.mobile) : '')
        $('[name="row_classes_tablet"]').val(data.metadata ? JSON.stringify(data.metadata.tablet) : '')
        $('[name="row_classes_tablet_landscape"]').val(data.metadata ? JSON.stringify(data.metadata.tablet_landscape) : '')
        $('[name="row_classes_desktop"]').val(data.metadata ? JSON.stringify(data.metadata.desktop) : '')
        $('[name="row_classes_desktop_large"]').val(data.metadata ? JSON.stringify(data.metadata.desktop_large) : '')

        $(`[name="layout_mobile"]`).val(data.layout_mobile)
        $(`[name="layout_tablet"]`).val(data.layout_tablet)
        $(`[name="layout"]`).val(data.layout)
        showContentAreas()
        let activeViewToggle = document.querySelector('a[data-type="row"][data-view="mobile"]')
        let activeView = $(`[name="row_classes_mobile"]`)
        showResponsiveSettings(activeViewToggle, 'row', activeView.val())

        if (data.border_color != '') $('#border_color').minicolors('value', data.border_color)
        $('#border_style').val(data.border_style)
        $('#border_top_width').val(data.border_top_width)
        $('#border_right_width').val(data.border_right_width)
        $('#border_bottom_width').val(data.border_bottom_width)
        $('#border_left_width').val(data.border_left_width)

        if (data.section_background_color != '') $('#section_background_color').minicolors('value', data.section_background_color)
        if (data.page_background_color != '') $('#page_background_color').minicolors('value', data.page_background_color)
        if (data.background_fixed == 1) $('#background_fixed').bootstrapSwitch('toggleState')
        if (data.background_parallax == 1) $('#background_parallax').bootstrapSwitch('toggleState')
        if (data.background_stretch == 1) $('#background_stretch').bootstrapSwitch('toggleState')
        if (data.background_repeat_x == 1) $('#background_repeat_x').bootstrapSwitch('toggleState')
        if (data.background_repeat_y == 1) $('#background_repeat_y').bootstrapSwitch('toggleState')
        $('#background_min_height').val(data.background_min_height)
        $('#background_max_height').val(data.background_max_height)
        $('#background_position_x').val(data.background_position_x)
        $('#background_position_y').val(data.background_position_y)
        $('#background_image').val(data.background_image)
        $('#remove_image').toggleClass('hidden', data.background_image == '')
        if (data.overlay == 1) $('#overlay').bootstrapSwitch('toggleState')
        $('#overlay_class').val(data.overlay_class)
        $('#overlay_position').val(data.overlay_position)
        $('#overlay_distance').val(data.overlay_distance)

        for (var i = 0; i < data.columns.length; i++) {
          var column = data.columns[i],
            parent = $('#col' + column.column),
            option = ''

          $('#col' + column.column)
            .find('.toggle_buttons > label')
            .removeClass('active')
            .children()
            .removeAttr('checked')
          $('input[name=classes_' + column.column + ']').val(column.classes)

          if (column.template_id != null) {
            $('#template_' + column.column)
              .prop('checked', true)
              .parent('label')
              .toggleClass('active', true)
            $('#template_id_' + column.column).val(column.template_id.toString())
            option = 'template'
          } else if (column.slideshow_id != null) {
            $('#slideshow_' + column.column)
              .prop('checked', true)
              .parent('label')
              .toggleClass('active', true)
            $('#slideshow_id_' + column.column).val(column.slideshow_id.toString())
            option = 'slideshow'
          } else {
            $('#editor_' + column.column)
              .prop('checked', true)
              .parent('label')
              .toggleClass('active', true)
            tinyMCE.get('content_' + column.column).setContent(column.content)
            option = 'editor'
          }

          if (column.metadata) {
            $(`[name="column_classes_mobile_${column.column}"]`).val(JSON.stringify(column.metadata.mobile))
            $(`[name="column_classes_tablet_${column.column}"]`).val(JSON.stringify(column.metadata.tablet))
            $(`[name="column_classes_tablet_landscape_${column.column}"]`).val(JSON.stringify(column.metadata.tablet_landscape))
            $(`[name="column_classes_desktop_${column.column}"]`).val(JSON.stringify(column.metadata.desktop))
            $(`[name="column_classes_desktop_large_${column.column}"]`).val(JSON.stringify(column.metadata.desktop_large))
            var container = document.querySelector(`#column_options_${column.column}`)
            let activeViewToggle = container.querySelector(`a[data-type="column"][data-view="mobile"]`)
            showResponsiveSettings(activeViewToggle, 'column', JSON.stringify(column.metadata.mobile))

            if (column.metadata.background) {
              $(`#column_bg_image_${i + 1}`).val(column.metadata.background.image)
              $(`#column_remove_image_${i + 1}`).toggleClass('hidden', column.metadata.background.image == '')
              if (column.metadata.background.color) $(`#column_bg_color_${i + 1}`).minicolors('value', column.metadata.background.color)
            }
          }

          // Apply background color in the event the user switches options.
          if (data.page_background_color != '')
            tinyMCE.get('content_' + column.column).getBody().style.backgroundColor = data.page_background_color
          else if (data.section_background_color != '')
            tinyMCE.get('content_' + column.column).getBody().style.backgroundColor = data.section_background_color

          // Display option content
          displayOption(parent, option)
        }

        $('.bs-select').selectpicker('render')

        App.stopPageLoading()
      },
      error: function ($response) {},
    })
  else App.stopPageLoading()
}

function updateSectionsGrid() {
  $.ajax({
    url: '/' + $('#uri').val() + '/pages/getSections',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      if (data.length != null && data.length > 0) {
        $('#sections tbody > tr').remove()
        for (var i = 0; i < data.length; i++) {
          $('#sections > tbody:last').append(
            '<tr id="' +
              i +
              '" data-id="' +
              data[i].id +
              '"><td>' +
              data[i].title +
              '</td>' +
              '<td class="text-center hidden-xs">' +
              data[i].real_layout +
              '</td>' +
              '<td class="text-right">' +
              '<a class="btn btn-outline btn-sm blue view"><i class="fa fa-edit"></i> <span class="hidden-xs">edit</span></a>' +
              '<a class="btn btn-outline dark btn-sm red delete" data-toggle="confirmation" data-placement="left" data-singleton="true" data-onConfirm="deleteSection"><i class="fa fa-trash-o"></i></a>' +
              '</td>' +
              '</tr>',
          )
        }
        $('#sections tbody').sortable('refresh')
        $('#new-section').toggleClass('hidden', false)
        $('#page_content').toggleClass('disabled', false)
      } else {
        $('#sections tbody > tr').remove()
        $('#sections > tbody').append(
          '<tr id="norows"><td colspan="3" class="text-info small" align="center"><i>No sections have been added to this page yet.</i></td></tr>',
        )
      }
    },
  })
  App.unblockUI('#page_content')
}

function resetSectionForm() {
  $('#options .in:not(#panel-layout)').collapse('hide')
  ;['row', 'column'].map((type) => {
    $(`a[data-type="${type}"]`).toggleClass('active', false)
    $(`a[data-type="${type}"][data-view="mobile"]`).toggleClass('active', true)
  })
  $('.tab-pane.active .nav.nav-pills > li:first a').tab('show')
  $('#panel-layout').collapse('show')
  $('#sectionId').val('')
  $('#sectionTitle').val('')
  $('#classes').val('')
  showContentAreas()
  $('.picker').removeAttr('style').val('')
  if (tinyMCE != undefined && tinyMCE.activeEditor != undefined) {
    tinyMCE.get('content_1').setContent('')
    tinyMCE.get('content_2').setContent('')
    tinyMCE.get('content_3').setContent('')
    tinyMCE.get('content_4').setContent('')
    tinyMCE.get('content_5').setContent('')
    $('#background_image').val('')
    $('#section_background_color').val('')
  }
  $('#background_min_height').val('')
  $('#background_max_height').val('')
  if ($('#background_fixed').bootstrapSwitch('state')) $('#background_fixed').bootstrapSwitch('toggleState')
  if ($('#background_parallax').bootstrapSwitch('state')) $('#background_parallax').bootstrapSwitch('toggleState')
  if ($('#background_stretch').bootstrapSwitch('state')) $('#background_stretch').bootstrapSwitch('toggleState')
  if ($('#background_repeat_x').bootstrapSwitch('state')) $('#background_repeat_x').bootstrapSwitch('toggleState')
  if ($('#background_repeat_y').bootstrapSwitch('state')) $('#background_repeat_y').bootstrapSwitch('toggleState')
  $('#background_position_x').val('')
  $('#background_position_y').val('')

  if ($('#overlay').bootstrapSwitch('state')) $('#overlay').bootstrapSwitch('toggleState')
  $('#overlay_class').val('')
  $('#overlay_position').val('')
  $('#overlay_distance').val('')

  $('.toggle_buttons label').removeClass('active').children().removeAttr('checked')
  $('[name="full_width"][value="false"]').prop('checked', true).parent('label').toggleClass('active', true)
  $('[name="vertical_alignment"][value="items-start"]').prop('checked', true).parent('label').toggleClass('active', true)
  $('#editor_1').prop('checked', true).parent('label').toggleClass('active', true)
  $('#editor_2').prop('checked', true).parent('label').toggleClass('active', true)
  $('#editor_3').prop('checked', true).parent('label').toggleClass('active', true)
  $('#editor_4').prop('checked', true).parent('label').toggleClass('active', true)
  $('#editor_5').prop('checked', true).parent('label').toggleClass('active', true)
  $('[data-attribute="padding"]').val('')
  $('[data-attribute="margins"]').val('')
  $('.column_classes_mobile').val('')
  $('.column_classes_tablet').val('')
  $('.column_classes_tablet_landscape').val('')
  $('.column_classes_desktop').val('')
  $('.column_classes_desktop_large').val('')
  $('.column').find('input[type="text"]').val('')
  $('.mce-tinymce').attr('style', 'visibility: hidden !important; height: 0')
  $('.slideshow_options').hide()
  $('.template_options').hide()
  $('.slideshow_options select').val('')
  $('.template_options select').val('')
  $('.bs-select').selectpicker('render')
}

function showContentAreas() {
  // Deselect any highlighted options
  $('.layoutoption').removeClass('selected')

  // Highlight current layout option
  let activeView = $('[data-type="row"].active').data('view')
  let selectedLayout = $(`#layout_${activeView}`).val()
  $(`#columns-${selectedLayout}`).addClass('selected')

  // Determine actual columns & display content tabs.
  var columns = getRealColumns(String($('#layout_desktop').val()))

  $('#panel-content')
    .find('.nav-tabs')
    .toggleClass('hidden', columns == 1)
  for (i = 0; i < 5; i++) {
    if (i < columns) {
      $('#content-tabs li').eq(i).removeClass('hidden')
      $('#col' + (i + 1)).removeClass('hidden')
    } else {
      $('#content-tabs li').eq(i).addClass('hidden')
      $('#col' + (i + 1)).addClass('hidden')
    }
  }

  // Select first tab.
  $('#content-tabs a:first').tab('show')
}

function showResponsiveSettings(trigger, type, data) {
  // Retrieve existing values for view
  let classes = isJsonString(data) ? JSON.parse(data) : ''

  // Reset all controls in active group
  let container = trigger.closest('.responsive-container').querySelector('.responsive__enabled')
  let group = container.querySelectorAll('select,input')
  resetGroup(group)

  if (classes) {
    // Loop through attributes
    for (const [attribute, value] of Object.entries(classes)) {
      // padding, margins, vertical_alignment, full_width
      if (typeof value === 'object') {
        for (const [setting, v] of Object.entries(value)) {
          // top, left, bottom, right
          let control = $(container.querySelector(`[name="${type}_${attribute}_${setting}"]`))
          updateResponsiveControlValues(control, v)
        }
      } else {
        let control = $(container.querySelectorAll(`[name="${attribute}"]`))
        updateResponsiveControlValues(control, value)
      }
    }
  }

  // Refresh bootstrap-select
  $('.bs-select').selectpicker('refresh')
}

function resetGroup(group) {
  ;[...group].forEach((control) => {
    control = $(control)
    if (control.is('select')) control.val('')
    else if (control.is(':radio')) {
      if (control.val() === '') control.prop('checked', true).parent().addClass('active')
      else control.prop('checked', false)
    }
  })
}

function updateResponsiveControlValues(control, value) {
  if (control.is('select')) {
    let match = control.find(`option[value="${value}"]`)
    if (match.length > 0) {
      match.prop('selected', true)
      control.selectpicker('refresh')
      return true
    }
  } else if (control.is(':radio') || control.is(':checkbox')) {
    let match = control.filter(`[value="${value}"]`)
    if (match.length > 0) {
      control
        .filter(`[name="${match.attr('name')}"]`)
        .removeAttr('checked')
        .parent()
        .removeClass('active')
      match.attr('checked', 'checked').parent().addClass('active')
      return true
    } else {
      control.removeAttr('checked').parent().removeClass('active')
    }
  }
  return false
}

function updateResponsiveClasses(e) {
  let target = $(e.target)

  // determine what attribute to update (data-attribute | name)
  let attribute = target.data('attribute') || target.attr('name') // i.e. padding, margins, full_width, vertical_alignment

  // determine what key to update (data-key | none)
  let key = target.data('key') || undefined // i.e. top,right,bottom,left || none

  // Retrieve the current view's classes
  let container = target.parents('.responsive-container')
  let activeView = container.find('.responsive-picker a.active')
  let values = container.find(`.${activeView.data('type')}_classes_${activeView.data('view')}`)
  let classes = isJsonString(values.val()) ? JSON.parse(values.val()) : values.val()

  // i.e. {...values[padding], {top:"pt-5"}}
  // i.e. {...values, [full_width]: true}

  // Add/Replace the classes where appropriate
  let value = target.val()
  if (key !== undefined) {
    if (value === '' && classes[attribute][key]) delete classes[attribute][key]
    else value = { ...classes[attribute], [key]: value }
  } else if (value === '') delete classes[attribute]

  if (value !== '') classes = { ...classes, [attribute]: value }

  // Save the updates to the current view
  // i.e. row_classes_mobile
  values.val(JSON.stringify(classes))
}

function getRealColumns(layout) {
  layout = String(layout).split('-')
  if (layout.length > 1) return layout.length
  else return layout
}

function show_submenu_options() {
  if ($('#page_id').val() == 0) $('#submenu-options').hide()
  else {
    $.ajax({
      url: '/' + $('#uri').val() + '/pages/hasSubpages',
      type: 'GET',
      data: $('#page_id').serialize(),
      dataType: 'json',
      success: function (data) {
        if (data) $('#submenu-options').show()
        else $('#submenu-options').hide()
      },
      error: function ($response) {},
    })
  }
}

function get_pages_base_url() {
  if ($('#page_id').val() > 0)
    $.ajax({
      url: '/' + $('#uri').val() + '/pages/getPageBaseUrl',
      type: 'GET',
      data: $('#page_id').serialize(),
      dataType: 'json',
      success: function (data) {
        $('#base-url').html(data.baseUrl)
        $('#permalink-view').attr('href', data.baseUrl + data.slug)
      },
    })
}

function loadTemplates(url) {
  $.get(`${url}/shortcodes`, null, function (response) {
    window.shortcodes = response
  })
}

function initTinyMCE(crossDomain, baseUrl) {
  // shortcodes plugin
  if (window.shortcodes != undefined)
    tinymce.PluginManager.add('shortcodes', function (editor) {
      var menuItems = []
      tinymce.each(window.shortcodes, function (template) {
        menuItems.push({
          text: template.title,
          onclick: function () {
            var link = `<div class="template-shortcode">[template-${template.url}]</div>`
            editor.insertContent(link)
          },
        })
      })

      editor.addButton('shortcodes', {
        type: 'menubutton',
        text: 'Shortcodes',
        icon: 'media',
        menu: menuItems,
      })

      editor.addMenuItem('shortcodesDropDownMenu', {
        icon: 'media',
        text: 'Shortcodes',
        menu: menuItems,
        context: 'insert',
        prependToContext: true,
      })
    })

  // simple
  tinymce.init({
    selector: 'textarea.tinymce-simple',
    // content_css: css,
    style_formats: [
      {
        title: 'Text Sizes',
        items: [
          { title: 'Extra Large', inline: 'span', classes: 'text-xlarge' },
          { title: 'Large', inline: 'span', classes: 'text-large' },
          { title: 'Medium', inline: 'span', classes: 'text-medium' },
          { title: 'Small', inline: 'span', classes: 'text-small' },
          { title: 'Smaller', inline: 'span', classes: 'text-tiny' },
        ],
      },
      { title: 'Colors', items: config.theme.colors },
    ],
    height: '100',
    menubar: false,
    statusbar: false,
    plugins: ['paste'],
    toolbar1: 'styleselect | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify',
    paste_as_text: true,
    browser_spellcheck: true,
    forced_root_block: 'p', // Set to false to disable.
    convert_urls: 0, // disables converting urls from absolute to relative
  })

  tinymce.init({
    selector: 'textarea.tinymce-biography',
    // content_css: css,
    height: '250',
    menubar: false,
    statusbar: false,
    plugins: ['paste'],
    toolbar1: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify',
    paste_as_text: true,
    browser_spellcheck: true,
    forced_root_block: 'p', // Set to false to disable.
    convert_urls: 0, // disables converting urls from absolute to relative
  })

  // profile
  tinymce.init({
    selector: 'textarea.tinymce-profile',
    // content_css: css,
    menubar: false,
    statusbar: false,
    plugins: ['lists link hr paste media image'],
    toolbar1:
      'removeformat | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink | hr | media image',
    paste_as_text: true,
    browser_spellcheck: true,
    forced_root_block: 'p', // Set to false to disable.
    convert_urls: 0, // disables converting urls from absolute to relative

    file_picker_types: 'image',
    file_picker_callback(callback, value, meta) {
      console.log('callback')
      let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth
      let y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight

      tinymce.activeEditor.windowManager.openUrl({
        url: '/file-manager/tinymce5',
        title: 'Laravel File manager',
        width: x * 0.8,
        height: y * 0.8,
        onMessage: (api, message) => {
          callback(message.content, { text: message.text })
        },
      })
    },
  })

  // note
  tinymce.init({
    selector: 'textarea.tinymce-note',
    // content_css: css,
    height: '250',
    menubar: false,
    statusbar: false,
    plugins: ['lists link hr paste'],
    toolbar1:
      'removeformat | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink | hr',
    paste_as_text: true,
    browser_spellcheck: true,
    forced_root_block: 'p', // Set to false to disable.
    convert_urls: 0, // disables converting urls from absolute to relative

    // update validation status on change
    setup: function (editor) {
      editor.on('change', function (args) {
        tinyMCE.triggerSave()
        $('#' + editor.id).valid()
      })
    },
  })

  tinymce.init({
    selector: 'textarea.tinymce-blast',
    plugins: [
      'advlist autolink lists link image charmap print preview hr anchor pagebreak',
      'searchreplace wordcount visualblocks visualchars code fullscreen',
      'insertdatetime media nonbreaking save table directionality',
      'emoticons template paste',
    ],
    style_formats: [
      {
        title: 'Headings',
        items: [
          { title: 'Heading 1', block: 'h1' },
          { title: 'Heading 2', block: 'h2' },
          { title: 'Heading 3', block: 'h3' },
          { title: 'Heading 4', block: 'h3' },
          { title: 'Heading 5', block: 'h3' },
          { title: 'Heading 6', block: 'h3' },
        ],
      },
      {
        title: 'Image Alignment',
        items: [
          { title: 'Image Left', selector: 'img', styles: { float: 'left', margin: '0 10px 0 10px' } },
          { title: 'Image Right', selector: 'img', styles: { float: 'right', margin: '0 0 10px 10px' } },
        ],
      },
    ],
    height: '400',
    menubar: false,
    statusbar: false,
    toolbar1:
      'styleselect | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | hr',
    toolbar2: 'link unlink | image media | forecolor backcolor | fontselect fontsizeselect | print preview code',
    paste_as_text: true,
    browser_spellcheck: true,
    forced_root_block: 'p', // Set to false to disable.
    convert_urls: 0, // disables converting urls from absolute to relative

    // update validation status on change
    setup: function (editor) {
      editor.on('change', function (args) {
        tinyMCE.triggerSave()
        $('#' + editor.id).valid()
      })
    },

    file_picker_types: 'image',
    file_picker_callback(callback, value, meta) {
      console.log('callback')
      let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth
      let y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight

      tinymce.activeEditor.windowManager.openUrl({
        url: '/file-manager/tinymce5',
        title: 'Laravel File manager',
        width: x * 0.8,
        height: y * 0.8,
        onMessage: (api, message) => {
          callback(message.content, { text: message.text })
        },
      })
    },
  })

  tinymce.init({
    selector: 'textarea.tinymce',
    plugins: [
      'advlist autolink lists link image charmap print preview hr anchor pagebreak',
      'searchreplace wordcount visualblocks visualchars code fullscreen',
      'insertdatetime media nonbreaking save table directionality',
      'emoticons template paste',
      window.shortcodes != undefined ? 'shortcodes' : '',
    ],
    // content_css: css,
    style_formats: [
      {
        title: 'Headings',
        items: [
          { title: 'Heading 1', block: 'h1' },
          { title: 'Heading 2', block: 'h2' },
          { title: 'Heading 3', block: 'h3' },
          { title: 'Heading 4', block: 'h4' },
          { title: 'Heading 5', block: 'h5' },
          { title: 'Heading (Banner)', block: 'div', classes: 'heading1' },
        ],
      },
      {
        title: 'Text Sizes',
        items: [
          { title: 'Extra Large', inline: 'span', classes: 'text-xlarge' },
          { title: 'Large', inline: 'span', classes: 'text-large' },
          { title: 'Medium', inline: 'span', classes: 'text-medium' },
          { title: 'Small', inline: 'span', classes: 'text-small' },
          { title: 'Smaller', inline: 'span', classes: 'text-tiny' },
        ],
      },
      { title: 'Colors', items: config.theme.colors },
      { title: 'Buttons', items: config.theme.buttons },
    ],
    templates: config.theme.templates,
    formats: {
      alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-left' },
      aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-center' },
      alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-right' },
    },
    toolbar1:
      'styleselect | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | hr | bullist numlist outdent indent',
    toolbar2: 'shortcodes | link unlink anchor | image media | forecolor backcolor | print preview code',
    height: '600',
    paste_as_text: true,
    image_advtab: true,
    browser_spellcheck: true,
    forced_root_block: 'p', // Set to false to disable.
    convert_urls: 0, // disables converting urls from absolute to relative
    extended_valid_elements: 'i[*],svg[*],defs[*],style[*],path[*]',
    /* fontselect
        font_formats : "Oswald=oswald;"+
            "Cambo=cambo",*/
    setup: function (editor) {
      editor.on('init', function (args) {
        editor = args.target
        editor.on('NodeChange', function (e) {
          if (e && e.element.nodeName.toLowerCase() == 'img') {
            tinyMCE.DOM.setAttribs(e.element, { width: null, height: null })
          }
        })
      })
    },

    file_picker_types: 'image',
    file_picker_callback(callback, value, meta) {
      console.log('callback')
      let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth
      let y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight

      tinymce.activeEditor.windowManager.openUrl({
        url: '/file-manager/tinymce5',
        title: 'Laravel File manager',
        width: x * 0.8,
        height: y * 0.8,
        onMessage: (api, message) => {
          callback(message.content, { text: message.text })
        },
      })
    },
  })
}

function isJsonString(str) {
  try {
    return JSON.parse(str) && !!str
  } catch (e) {
    return false
  }
}
