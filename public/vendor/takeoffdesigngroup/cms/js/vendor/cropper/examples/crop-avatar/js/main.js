function CropAvatar($element) {
  this.$container = $element;
  this.$loading = this.$container.find('.loading');
  this.$avatarModal = this.$container.find('#avatar-modal');
  this.$avatarWrapper = this.$avatarModal.find('.avatar-wrapper');
  this.$avatarPreview = this.$avatarModal.find('.avatar-preview');
  this.$avatarForm = this.$avatarModal.find('.avatar-form');
  this.$avatarUpload = this.$avatarForm.find('.avatar-upload');
  this.$avatarInput = this.$avatarForm.find('input[type="file"].avatar-input');
  this.$avatarSave = this.$avatarForm.find('.avatar-save');
  this.$avatarBtns = this.$avatarForm.find('.avatar-btns');
  this.$avatarData = this.$avatarForm.find('.avatar-data');

  this.init();
}

function getRatio(ratio) {
  if (ratio.length > 0)
  {
    switch (ratio.val())
    {
      case 'square':
        return 1;
      case 'banner':
        return 16 / 9;
      case 'none':
        return null;
      default:
        return ratio.val();
    }
  }
  else 
    return 1;
}

CropAvatar.prototype = {
  constructor: CropAvatar,

  support: {
    fileList: !!$('<input type="file">').prop('files'),
    blobURLs: !!window.URL && URL.createObjectURL,
    formData: !!window.FormData
  },

  init: function () {
    this.support.datauri = this.support.fileList && this.support.blobURLs;

    if (!this.support.formData) {
      this.initIframe();
    }

    //this.initTooltip();
    this.initModal();
    this.addListener();
  },

  addListener: function () {
    this.$avatarModal.on('shown.bs.modal', $.proxy(this.modalShown, this));
    this.$avatarInput.on('change', $.proxy(this.change, this));
    this.$avatarForm.on('submit', $.proxy(this.submit, this));
    this.$avatarBtns.on('click', $.proxy(this.rotate, this));
    this.$avatarBtns.find('.cancel').on('click', $.proxy(this.cancel, this));
  },

  modalShown: function() {
    this.$avatarField = this.$avatarModal.find('.avatar-field');
    //this.$avatarView = $('.avatar-view[data-type='+this.$avatarField.val()+']');
    this.$avatar = this.$avatarWrapper.find('img');
    this.$avatarBaseUrl = this.$avatarForm.find('.avatar-base');

    // options
    this.$avatarOptions = {
      ratio: getRatio(this.$avatarForm.find('.avatar-options-ratio')),
      minCropBoxWidth: this.$avatarForm.find('.avatar-options-minCropBoxWidth').length > 0 ? this.$avatarForm.find('.avatar-options-minCropBoxWidth').val() : 0,
      minCropBoxHeight: this.$avatarForm.find('.avatar-options-minCropBoxHeight').length > 0 ? this.$avatarForm.find('.avatar-options-minCropBoxHeight').val() : 0,
    };

    if (this.$avatarWrapper.is('.edit'))
    {
        this.url = this.$avatarWrapper.find('img').attr('src');
        this.startCropper();      
    }
  },

  initTooltip: function () {
    this.$avatarView.tooltip({
      placement: 'bottom'
    });
  },

  initModal: function () {
    this.$avatarModal.modal({
      show: false
    });
  },

  initPreview: function () {
    var url = this.$avatar.attr('src');

    this.$avatarPreview.empty().html('<img src="' + url + '">');
  },

  initIframe: function () {
    var target = 'upload-iframe-' + (new Date()).getTime(),
        $iframe = $('<iframe>').attr({
          name: target,
          src: ''
        }),
        _this = this;

    // Ready ifrmae
    $iframe.one('load', function () {

      // respond response
      $iframe.on('load', function () {
        var data;

        try {
          data = $(this).contents().find('body').text();
        } catch (e) {
          console.log(e.message);
        }

        if (data) {
          try {
            data = $.parseJSON(data);
          } catch (e) {
            console.log(e.message);
          }

          _this.submitDone(data);
        } else {
          _this.submitFail('Image upload failed!');
        }

        _this.submitEnd();

      });
    });

    this.$iframe = $iframe;
    this.$avatarForm.attr('target', target).after($iframe.hide());
  },

  click: function () {
    this.$avatarModal.modal('show');
    this.initPreview();
  },

  change: function () {
    var files,
        file;

    if (this.support.datauri) {
      files = this.$avatarInput.prop('files');

      if (files.length > 0) {
        file = files[0];

        if (this.isImageFile(file)) {
          if (this.url) {
            URL.revokeObjectURL(this.url); // Revoke the old one
          }

          this.url = URL.createObjectURL(file);
          this.startCropper();
        }
      }
    } else {
      file = this.$avatarInput.val();

      if (this.isImageFile(file)) {
        this.syncUpload();
      }
    }
  },

  submit: function () {
    if (!this.$avatarInput.val()) {
        if (this.$avatar.attr('src') !== '') {
            $('input[type=hidden].avatar-input').val(this.$avatar.attr('src'));
            this.ajaxUpload();
        }
        else
            return false;
    }

    if (this.support.formData) {
      this.ajaxUpload();
      return false;
    }
  },

  rotate: function (e) {
    var data;

    if (this.active) {
      data = $(e.target).data();

      if (data.method) {
        this.$img.cropper(data.method, data.option);
      }
    }
  },

  cancel: function () {
    this.$avatarForm.get(0).reset();
    this.$avatarForm.find('.avatar-preview').removeAttr('style');
    this.stopCropper();
    this.$avatarModal.modal('hide');
  },

  isImageFile: function (file) {
    if (file.type) {
      return /^image\/\w+$/.test(file.type);
    } else {
      return /\.(jpg|jpeg|png|gif)$/.test(file);
    }
  },

  startCropper: function () {
    var _this = this;

    if (this.active) {
      this.$img.cropper('replace', this.url);
    } else {
      this.$img = $('<img src="' + this.url + '">');
      this.$avatarWrapper.empty().html(this.$img).removeClass('default').removeClass('edit');
      this.$img.cropper({
        aspectRatio: this.$avatarOptions.ratio,
        preview: this.$avatarPreview.selector,
        dragCrop: false,
        movable: true,
        resizable: true,
        minCropBoxWidth: this.$avatarOptions.minCropBoxWidth,
        minCropBoxHeight: this.$avatarOptions.minCropBoxHeight,
        strict: true,
        crop: function (data) {
          var json = [
                '{"x":' + data.x,
                '"y":' + data.y,
                '"height":' + data.height,
                '"width":' + data.width,
                '"rotate":' + data.rotate + '}'
              ].join();

          _this.$avatarData.val(json);
        }
      });

      this.active = true;
      this.$avatarBtns.find('.btn-primary').prop('disabled', false);
      this.$avatarBtns.find('.avatar-save').prop('disabled', false);
    }
  },

  stopCropper: function () {
    if (this.active) {
      this.$img.cropper('destroy');
      this.$img.remove();
      this.$avatarWrapper.addClass('default').append('<img src="/packages/jcargilo/administrator/assets/global/img/noimage.gif" alt="" />');
      this.active = false;
      this.$avatarBtns.find('.btn-primary').prop('disabled', true);
      this.$avatarBtns.find('.avatar-save').prop('disabled', true);
    }
  },

  ajaxUpload: function () {
    var url = this.$avatarForm.attr('action'),
        data = new FormData(this.$avatarForm[0]),
        _this = this;

    $.ajax(url, {
      type: 'post',
      data: data,
      dataType: 'json',
      processData: false,
      contentType: false,

      beforeSend: function () {
        _this.submitStart();
      },

      success: function (data) {
        _this.submitDone(data);
      },

      error: function (XMLHttpRequest, textStatus, errorThrown) {
        _this.submitFail(textStatus || errorThrown);
      },

      complete: function () {
        _this.submitEnd();
      }
    });
  },

  syncUpload: function () {
    this.$avatarSave.click();
  },

  submitStart: function () {
    this.$loading.fadeIn();
  },

  submitDone: function (data) {
    //console.log(data);
    $('.alert').remove();

    if ($.isPlainObject(data) && data.state === 200) {
      if (data.result) {
        this.url = data.result;

        if (this.support.datauri || this.uploaded) {
          this.uploaded = false;
          this.cropDone();
        } else {
          this.uploaded = true;
          this.$avatarSrc.val(this.url);
          this.startCropper();
        }

        this.$avatarInput.val('');
      } else if (data.message) {
        this.alert(data.message);
      }
    } else {
      this.alert('Failed to response');
    }
  },

  submitFail: function (msg) {
    this.alert(msg);
  },

  submitEnd: function () {
    this.$loading.fadeOut();
  },

  cropDone: function () {
    this.$avatarForm.get(0).reset();
    this.$avatar.attr('src', this.$avatarBaseUrl.val() + this.url + '?' + Math.random());
    $('#'+this.$avatarField.val()).val(this.url).trigger('change');
    this.stopCropper();
    this.$avatarForm.find('.avatar-preview').removeAttr('style');
    this.$avatarModal.modal('hide');
  },

  alert: function (msg) {
    $('.alert').remove();
    var $alert = [
          '<div class="alert alert-danger avatar-alert alert-dismissable">',
            '<button type="button" class="close" data-dismiss="alert">&times;</button>',
            msg,
          '</div>'
        ].join('');

    this.$avatarWrapper.before($alert);
  }
};

$(function(){
    return new CropAvatar($('#crop-avatar'));
})