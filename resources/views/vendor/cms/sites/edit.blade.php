@php view()->share('loadEditor', true); @endphp

@extends('cms::master')

@section('title')
    <ul class="page-breadcrumb">
        @if (config('cms.multiple_sites_enabled'))
        <li>
            <a href="{{ route('sites.index') }}">Sites</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>{{ isset($site) ? 'Edit ' . $site->title : 'New' }}</span>
        </li>
        @else
        <li>
            <span>Settings</span>
        </li>
        @endif
    </ul>
    <div class="page-toolbar">
        <div class="btn-group pull-right">
            @if (config('cms.multiple_sites_enabled'))
            <a name="back" class="btn btn-secondary-outline btn-sm" href="{{ route('sites.index') }}">
                <i class="fa fa-angle-left"></i> Back</a>
            @endif
        </div>
    </div>
@stop

@section('content')
    <!-- END PAGE HEADER-->
    @if (!isset($site))
        {{ Form::open(['route' => 'sites.store', 'class' => 'form-row-seperated']) }}
    @else
        {{ Form::model($site, ['method' => 'put', 'route' => ['sites.update', $site->id], 'class' => 'form-row-seperated edit']) }}
        {{ Form::hidden('id') }}
    @endif
    {{ Form::hidden('action') }}

    <div class="row mt-8">
        <!-- BEGIN PROFILE SIDEBAR -->
        <div class="col-lg-4">
            <!-- PORTLET MAIN -->
            <div class="portlet light profile-sidebar-portlet">
                <!-- SIDEBAR LOGO -->
                <div class="profile-sidebar-logo">
                    <!-- BEGIN LOGO -->
                    <img class="img-responsive" src="{{ $site->logo ? url()->asset($site->logo) : '/vendor/takeoffdesigngroup/cms/assets/global/img/noimage.gif' }}" alt="">
                    <!-- END LOGO -->
                </div>
                <!-- END SIDEBAR USERPIC -->
                <!-- SIDEBAR USER TITLE -->
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name">{{ $site->title }} </div>
                    <div class="small"><a href="{{ $site->domain }}" target="_blank">{{ $site->domain }}</a></div>
                </div>
                <!-- END SIDEBAR USER TITLE -->
                <!-- SIDEBAR MENU -->
                <div class="profile-usermenu pb-0">
                    <ul class="nav">
                        <li class="active"><a data-title="General" data-url="/account/site/settings/general" href="#tab_details" data-toggle="tab"><i class="fa fa-cogs"></i>General</a></li>
                        @if (auth()->user()->hasRole('administrator'))
                            <li><a data-title="Theme" href="#tab_theme" data-toggle="tab"><i class="fas fa-sliders-h"></i>Theme</a></li>
                        @endif
                        @if (isset($site) && Laratrust::hasRole('developer'))
                            <li><a data-title="Menu" data-url="/account/site/settings/menu" href="#tab_menu" data-toggle="tab"><i class="fa fa-list"></i>Menu</a></li>
                        @endif
                        <li><a data-title="SEO" data-url="/account/site/settings/seo" href="#tab_seo" data-toggle="tab"><i class="fa fa-tag"></i>SEO</a></li>
                        <li><a data-title="Images" data-url="/account/site/settings/images" href="#tab_images" data-toggle="tab"><i class="fa fa-picture-o"></i>Images</a></li>
                        <li><a data-title="Social" data-url="/account/site/settings/social" href="#tab_social" data-toggle="tab"><i class="fa fa-facebook"></i>Social</a></li>
                    </ul>
                </div>
                <!-- END MENU -->
            </div>
            <!-- END PORTLET MAIN -->
        </div>
        <!-- END BEGIN PROFILE SIDEBAR -->

        <!-- BEGIN PROFILE CONTENT -->
        <div class="col-lg-8">
            <div class="tab-content">
                <!-- SITE DETAILS TAB -->
                <div class="tab-pane active" id="tab_details">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption caption-md">
                                <span class="caption-subject"> General Site Settings</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="title" class="control-label">Site Title:<span class="required"> * </span></label>
                                <div class="validate">{{ Form::text('title', NULL, array('class' => 'form-control', 'autofocus' => 'autofocus')) }}</div>
                            </div>

                            <div class="form-group">
                                <label for="domain" class="control-label">Domain:<span class="required"> * </span></label>
                                <div class="validate">{{ Form::text('domain', NULL, array('class' => 'form-control', 'placeholder' => 'http://www.mysite.com')) }}</div>
                            </div>

                            <div class="form-group">
                                <label for="attributes[biz_domain]" class="control-label">Biz-Diagnostic Domain:<span class="required"> * </span></label>
                                <div class="validate">{{ Form::text('attributes[biz_domain]', NULL, array('class' => 'form-control', 'placeholder' => 'https://mysite.biz-diagnostic.com')) }}</div>
                            </div>

                            <div class="form-group">
                                <label for="attributes[events_api_link]" class="control-label">Elite Marketing Events API Link:<span class="required"> * </span></label>
                                <div class="validate">{{ Form::text('attributes[events_api_link]', NULL, array('class' => 'form-control', 'placeholder' => 'https://app.elitemarketingplatform.com/v1/events')) }}</div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="control-label">Email Address(es):<span class="required"> * </span></label>
                                <div class="validate">{{ Form::text('email', NULL, array('class' => 'form-control', 'placeholder' => 'http://www.mysite.com')) }}</div>
                                <span class="text-info small"> To add multiple email addresses, separate each address with a comma. </span>
                            </div>

                            <div class="form-group">
                                <label for="disclaimer" class="control-label">Disclaimer / Footer Text (Site-wide):</label>
                                <div class="validate">{{ Form::textarea('attributes[disclaimer]', NULL, ['class' => 'form-control tinymce', 'rows' => '4']) }}</div>
                            </div>

                            <div class="mt-10 space-y-6">
                                <div class="flex items-center justify-between">
                                    <label for="show_coming_soon">Show Coming Soon Page</label>
                                    <div class="validate">
                                        {{ Form::hidden('show_coming_soon', 0) }}
                                        {{ Form::checkbox('show_coming_soon', 1, NULL, [
                                            'id' => 'show_coming_soon',
                                            'class' => 'make-switch',
                                            'data-on-text' => 'Yes',
                                            'data-on-color' => 'success',
                                            'data-off-text' => 'No',
                                            'data-size' => 'small'
                                            ]) }}
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="block mb-0" for="cache_data">Cache API Data for 1 hour</label>
                                        <span class="text-info small">When enabled, the site will only refresh the API-fetched data once per hour.</span>
                                    </div>
                                    <div class="validate">
                                        {{ Form::hidden('cache_data', 0) }}
                                        {{ Form::checkbox('cache_data', 1, NULL, [
                                            'id' => 'cache_data',
                                            'class' => 'make-switch',
                                            'data-on-text' => 'Yes',
                                            'data-on-color' => 'success',
                                            'data-off-text' => 'No',
                                            'data-size' => 'small'
                                            ]) }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 mt-8 border-t border-gray-200 pt-4">
                                <a href="{{ route('sites.index') }}" class="btn btn-secondary-outline default" name="reset" value="reset">Cancel</a>
                                <button class="btn btn-success" data-action="continue"><i class="fa fa-check"></i> Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SITE DETAILS TAB -->

                @if (auth()->user()->hasRole('administrator'))
                <!-- MENU TAB -->
                <div class="tab-pane" id="tab_theme">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption caption-md">
                                <span class="caption-subject"> Theme Settings</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="mb-6 border-b border-gray-200">
                                <div class="mb-6 space-y-3">
                                    <div class="gap-6 items-center justify-between sm:flex sm:w-1/2">
                                        <label for="primary_color" class="flex-1 control-label">Primary Color:</label>
                                        <div class="w-48 relative">
                                            {{ Form::text('attributes[theme][primary_color]', $site->attributes['theme']['primary_color'] ?? '#555555', [
                                                "id" => "primary_color",
                                                "class" => "form-control colorpicker",
                                            ]) }}
                                        </div>
                                    </div>

                                    <div class="gap-6 items-center justify-between sm:flex sm:w-1/2">
                                        <label for="secondary_color" class="flex-1 control-label">Secondary Color:</label>
                                        <div class="w-48 relative">
                                            {{ Form::text('attributes[theme][secondary_color]', $site->attributes['theme']['secondary_color'] ?? '#888888', [
                                                "id" => "secondary_color",
                                                "class" => "form-control colorpicker",
                                            ]) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="sm:flex sm:gap-10">
                                    <div class="sm:w-1/2">
                                        <div class="portlet">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    Links
                                                </div>
                                            </div>
                                            <div class="portlet-body space-y-3">
                                                <div class="gap-6 items-center justify-between sm:flex">
                                                    <label for="link_color" class="flex-1 control-label">Link Color:</label>
                                                    <div class="w-48 relative">
                                                        {{ Form::text('attributes[theme][link_color]', $site->attributes['theme']['link_color'] ?? ($site->attributes['theme']['primary_color'] ?? '#555555'), [
                                                            "id" => "link_color",
                                                            "class" => "form-control colorpicker",
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="gap-6 items-center justify-between sm:flex">
                                                    <label for="link_hover_color" class="flex-1 control-label">Link Hover Color:</label>
                                                    <div class="w-48 relative">
                                                        {{ Form::text('attributes[theme][link_hover_color]', $site->attributes['theme']['link_hover_color'] ?? ($site->attributes['theme']['secondary_color'] ?? '#888888'), [
                                                            "id" => "link_hover_color",
                                                            "class" => "form-control colorpicker",
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="gap-6 items-center justify-between sm:flex">
                                                    <label for="link_active_color" class="flex-1 control-label">Link Active Color:</label>
                                                    <div class="w-48 relative">
                                                        {{ Form::text('attributes[theme][link_active_color]', $site->attributes['theme']['link_active_color'] ?? ($site->attributes['theme']['primary_color'] ?? '#555555'), [
                                                            "id" => "link_active_color",
                                                            "class" => "form-control colorpicker",
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sm:w-1/2">
                                        <div class="portlet">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    Buttons
                                                </div>
                                            </div>
                                            <div class="portlet-body space-y-3">
                                                <div class="gap-6 items-center justify-between sm:flex">
                                                    <label for="button_bg_color" class="flex-1 control-label">Background Color:</label>
                                                    <div class="w-48 relative">
                                                        {{ Form::text('attributes[theme][button_bg_color]', $site->attributes['theme']['button_bg_color'] ?? ($site->attributes['theme']['primary_color'] ?? '#888888'), [
                                                            "id" => "button_bg_color",
                                                            "class" => "form-control colorpicker",
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="gap-6 items-center justify-between sm:flex">
                                                    <label for="button_text_color" class="flex-1 control-label">Text Color:</label>
                                                    <div class="w-48 relative">
                                                        {{ Form::text('attributes[theme][button_text_color]', $site->attributes['theme']['button_text_color'] ?? '#ffffff', [
                                                            "id" => "button_text_color",
                                                            "class" => "form-control colorpicker",
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="gap-6 items-center justify-between sm:flex">
                                                    <label for="button_hover_bg_color" class="flex-1 control-label">Hover Background Color:</label>
                                                    <div class="w-48 relative">
                                                        {{ Form::text('attributes[theme][button_hover_bg_color]', $site->attributes['theme']['button_hover_bg_color'] ?? ($site->attributes['theme']['secondary_color'] ?? '#555555'), [
                                                            "id" => "button_hover_bg_color",
                                                            "class" => "form-control colorpicker",
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="gap-6 items-center justify-between sm:flex">
                                                    <label for="button_hover_text_color" class="flex-1 control-label">Hover Text Color:</label>
                                                    <div class="w-48 relative">
                                                        {{ Form::text('attributes[theme][button_hover_text_color]', $site->attributes['theme']['button_hover_text_color'] ?? '#ffffff', [
                                                            "id" => "button_hover_text_color",
                                                            "class" => "form-control colorpicker",
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="gap-6 items-center justify-between sm:flex">
                                                    <label for="button_active_bg_color" class="flex-1 control-label">Active Background Color:</label>
                                                    <div class="w-48 relative">
                                                        {{ Form::text('attributes[theme][button_active_bg_color]', $site->attributes['theme']['button_active_bg_color'] ?? ($site->attributes['theme']['secondary_color'] ?? '#555555'), [
                                                            "id" => "button_active_bg_color",
                                                            "class" => "form-control colorpicker",
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="gap-6 items-center justify-between sm:flex">
                                                    <label for="button_active_text_color" class="flex-1 control-label">Active Text Color:</label>
                                                    <div class="w-48 relative">
                                                        {{ Form::text('attributes[theme][button_active_text_color]', $site->attributes['theme']['button_active_text_color'] ?? '#ffffff', [
                                                            "id" => "button_active_text_color",
                                                            "class" => "form-control colorpicker",
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="gap-6 items-center justify-between sm:flex">
                                                    <label for="button_focus_ring_color" class="flex-1 control-label">Focus Ring Color:</label>
                                                    <div class="w-48 relative">
                                                        {{ Form::text('attributes[theme][button_focus_ring_color]', $site->attributes['theme']['button_focus_ring_color'] ?? ($site->attributes['theme']['secondary_color'] ?? '#555555'), [
                                                            "id" => "button_focus_ring_color",
                                                            "class" => "form-control colorpicker",
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-success" data-action="continue"><i class="fa fa-check"></i> Save Changes</button>
                            <a href="{{ route('sites.index') }}" class="btn btn-secondary-outline default" name="reset" value="reset">Cancel</a>
                        </div>
                    </div>
                </div>
                <!-- END MENU TAB -->
                @endif

                @if (isset($site) && Laratrust::hasRole('developer'))
                <!-- MENU TAB -->
                <div class="tab-pane" id="tab_menu">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption caption-md">
                                <span class="caption-subject"> Menu Settings</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="mb-6 border-b border-gray-200 gap-6 pb-6">
                                @foreach ($site->menu as $item)
                                    <div class="row form-group vcenter-sm vcenter-lg">
                                        <label for="{{ $item->plugin }}" class="col-sm-6 col-md-12 col-sm-6 text-right-sm">{{ $item->title }}</label>
                                        <div class="col-sm-6 col-md-12 col-sm-6 text-right-lg">
                                            <input type="checkbox" name="menu[]" class="make-switch" value="{{ $item->id }}" data-size="small" data-on-color="info" data-on-text="Enabled" data-off-text="Disabled"{{ $item->enabled ? ' checked="checked"' : '' }} />
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <hr>

                            <button class="btn btn-success" data-action="continue"><i class="fa fa-check"></i> Save Changes</button>
                            <a href="{{ route('sites.index') }}" class="btn btn-secondary-outline default" name="reset" value="reset">Cancel</a>
                        </div>
                    </div>
                </div>
                <!-- END MENU TAB -->
                @endif

                <!-- BEGIN SEO TAB -->
                <div class="tab-pane" id="tab_seo">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption caption-md">
                                <span class="caption-subject"> SEO Settings</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="mb-6 border-b border-gray-200 gap-6 pb-6">
                                <div class="form-group">
                                    <label for="seo_title" class="control-label">Meta Title:</label>
                                    <div class="validate">{{ Form::text('seo_title', NULL, ['class' => 'form-control maxlength-handler', 'maxlength' => '100']) }}</div>
                                    <span class="text-info small"> max 100 chars </span>
                                </div>
                                <div class="form-group">
                                    <label for="seo_keywords" class="control-label">Meta Keywords:</label>
                                    <div class="validate">{{ Form::textarea('seo_keywords', NULL, ['class' => 'form-control maxlength-handler', 'maxlength' => '255', 'rows' => '5']) }}</div>
                                    <span class="text-info small"> max 255 chars </span>
                                </div>
                                <div class="form-group">
                                    <label for="seo_description" class="control-label">Meta Description:</label>
                                    <div class="validate">{{ Form::textarea('seo_description', NULL, ['class' => 'form-control maxlength-handler', 'maxlength' => '255', 'rows' => '5']) }}</div>
                                    <span class="text-info small"> max 255 chars </span>
                                </div>

                                <div class="form-group">
                                    <label for="meta_tags" class="control-label">Custom Meta Tags:</label>
                                    <div class="validate">{{ Form::textarea('meta_tags', NULL, ['class' => 'form-control', 'rows' => '8']) }}</div>
                                </div>

                                <div class="form-group">
                                    <label for="scripts" class="control-label">Custom Scripts:</label>
                                    <div class="validate">{{ Form::textarea('scripts', NULL, ['class' => 'form-control', 'rows' => '8']) }}</div>
                                </div>
                            </div>

                            <hr>

                            <button class="btn btn-success" data-action="continue"><i class="fa fa-check"></i> Save Changes</button>
                            <a href="{{ route('sites.index') }}" class="btn btn-secondary-outline default" name="reset" value="reset">Cancel</a>
                        </div>
                    </div>
                </div>
                <!-- END SEO TAB -->

                <!-- BEGIN IMAGES TAB -->
                <div class="tab-pane" id="tab_images">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption caption-md">
                                <span class="caption-subject"> Image Settings</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="row mb-6 border-b border-gray-200 gap-6 pb-6">
                                <div class="col-md-6">
                                    <div class="portlet">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-file-image-o"></i>Favicon
                                            </div>
                                            <div class="actions">
                                                {{ Form::hidden('favicon', null, array('id' => 'favicon')) }}
                                                <a href="/file-manager/fm-button?id=favicon" class="iframe-btn btn btn-primary">
                                                    <i class="fa fa-share"></i> Change
                                                </a>
                                                @if ($site->favicon)
                                                <a href="{{ route('sites.removeImage', [$site->id, 'favicon']) }}" class="btn default btn-sm">
                                                    <i class="fa fa-times"></i> Remove
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <p class="text-info">Supply an image exactly 16 x 16 pixels for optimal results.</p>
                                            <div class="text-center">
                                                <div class="fileinput">
                                                    <div class="thumbnail">
                                                        <img id="faviconPreview" src="{{ url()->asset('/vendor/takeoffdesigngroup/cms/assets/global/img/noimage.gif') }}" style="width: 16px;height: 16px">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="portlet">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-file-image-o"></i>Logo </div>
                                            <div class="actions">
                                                {{ Form::hidden('logo', null, array('id' => 'logo')) }}
                                                <a href="/file-manager/fm-button?id=logo" class="iframe-btn btn btn-primary">
                                                    <i class="fa fa-share"></i> Change
                                                </a>
                                                @if ($site->logo)
                                                <a href="{{ route('sites.removeImage', [$site->id, 'logo']) }}" class="btn default btn-sm">
                                                    <i class="fa fa-times"></i> Remove
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <p class="text-info">
                                                Supply a logo for your site.  Depending on template, this logo may appear in the header section of your site.  It will
                                                also appear on the login screen for this admin.  For optimal results on retina devices, provide an image twice the resolution of the maximum desired size.
                                            </p>
                                            <div class="text-center">
                                                <div class="fileinput">
                                                    <div class="thumbnail">
                                                        <img id="logoPreview" class="img-responsive" src="{{ url()->asset('/vendor/takeoffdesigngroup/cms/assets/global/img/noimage.gif') }}" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <button class="btn btn-success" data-action="continue"><i class="fa fa-check"></i> Save Changes</button>
                            <a href="{{ route('sites.index') }}" class="btn btn-secondary-outline default" name="reset" value="reset">Cancel</a>
                        </div>
                    </div>
                </div>
                <!-- END IMAGES TAB -->

                <!-- BEGIN SOCIAL TAB -->
                <div class="tab-pane" id="tab_social">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption caption-md">
                                <span class="caption-subject"> Social Settings</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="mb-6 border-b border-gray-200 gap-6 pb-6">
                                <div class="form-group vcenter">
                                    <label for="facebook" class="col-xs-2 control-label padding-top-0">
                                        <span class="socicon-btn socicon-btn-circle socicon-sm socicon-solid bg-blue font-white socicon-facebook"></span>
                                    </label>
                                    <div class="col-xs-10 input-group">
                                        <span class="input-group-addon hidden-xs" id="facebook_url">http://www.facebook.com/</span>
                                        {{ Form::text('facebook', NULL, ['id' => 'facebook', 'class' => 'form-control', 'aria-describedby' => 'facebook_url', 'placeholder' => 'username']) }}
                                    </div>
                                </div>

                                <div class="form-group vcenter">
                                    <label for="twitter" class="col-xs-2 control-label padding-top-0">
                                        <span class="socicon-btn socicon-btn-circle socicon-sm socicon-solid bg-green font-white socicon-twitter"></span>
                                    </label>
                                    <div class="col-xs-10 input-group">
                                        <span class="input-group-addon hidden-xs" id="twitter_url">http://www.twitter.com/</span>
                                        {{ Form::text('twitter', NULL, ['id' => 'twitter', 'class' => 'form-control', 'aria-describedby' => 'twitter_url', 'placeholder' => 'username']) }}
                                    </div>
                                </div>

                                <div class="form-group vcenter">
                                    <label for="googleplus" class="col-xs-2 control-label padding-top-0">
                                        <span class="socicon-btn socicon-btn-circle socicon-sm socicon-solid bg-yellow font-white socicon-google"></span>
                                    </label>
                                    <div class="col-xs-10 input-group">
                                        <span class="input-group-addon hidden-xs" id="googleplus_url">http://plus.google.com/</span>
                                        {{ Form::text('googleplus', NULL, ['id' => 'googleplus', 'class' => 'form-control', 'aria-describedby' => 'googleplus_url', 'placeholder' => 'username']) }}
                                    </div>
                                </div>

                                <div class="form-group vcenter">
                                    <label for="instagram" class="col-xs-2 control-label padding-top-0">
                                        <span class="socicon-btn socicon-btn-circle socicon-sm socicon-solid bg-red font-white socicon-instagram"></span>
                                    </label>
                                    <div class="col-xs-10 input-group">
                                        <span class="input-group-addon hidden-xs" id="instagram_url">http://www.instagram.com/</span>
                                        {{ Form::text('instagram', NULL, ['id' => 'instagram', 'class' => 'form-control', 'aria-describedby' => 'instagram_url', 'placeholder' => 'username']) }}
                                    </div>
                                </div>

                                <div class="form-group vcenter">
                                    <label for="flickr" class="col-xs-2 control-label padding-top-0">
                                        <span class="socicon-btn socicon-btn-circle socicon-sm socicon-solid bg-red font-white socicon-flickr"></span>
                                    </label>
                                    <div class="col-xs-10 input-group">
                                        <span class="input-group-addon hidden-xs" id="flickr_url">http://www.flickr.com/</span>
                                        {{ Form::text('flickr', NULL, ['id' => 'flickr', 'class' => 'form-control', 'aria-describedby' => 'flickr_url', 'placeholder' => 'username']) }}
                                    </div>
                                </div>

                                <div class="form-group vcenter">
                                    <label for="pinterest" class="col-xs-2 control-label padding-top-0">
                                        <span class="socicon-btn socicon-btn-circle socicon-sm socicon-solid bg-dark font-white socicon-pinterest"></span>
                                    </label>
                                    <div class="col-xs-10 input-group">
                                        <span class="input-group-addon hidden-xs" id="pinterest_url">http://www.pinterest.com/</span>
                                        {{ Form::text('pinterest', NULL, ['id' => 'pinterest', 'class' => 'form-control', 'aria-describedby' => 'pinterest_url', 'placeholder' => 'username']) }}
                                    </div>
                                </div>

                                <div class="form-group vcenter">
                                    <label for="linkedin" class="col-xs-2 control-label padding-top-0">
                                        <span class="socicon-btn socicon-btn-circle socicon-sm socicon-solid bg-green font-white socicon-linkedin"></span>
                                    </label>
                                    <div class="col-xs-10 input-group">
                                        <span class="input-group-addon hidden-xs" id="linkedin_url">http://www.linkedin.com/</span>
                                        {{ Form::text('linkedin', NULL, ['id' => 'linkedin', 'class' => 'form-control', 'aria-describedby' => 'linkedin_url', 'placeholder' => 'username']) }}
                                    </div>
                                </div>

                                <div class="form-group vcenter">
                                    <label for="youtube" class="col-xs-2 control-label padding-top-0">
                                        <span class="socicon-btn socicon-btn-circle socicon-sm socicon-solid bg-green font-white socicon-youtube"></span>
                                    </label>
                                    <div class="col-xs-10 input-group">
                                        <span class="input-group-addon hidden-xs" id="youtube_url">http://www.youtube.com/</span>
                                        {{ Form::text('youtube', NULL, ['id' => 'youtube', 'class' => 'form-control', 'aria-describedby' => 'youtube_url', 'placeholder' => 'username']) }}
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <button class="btn btn-success" data-action="continue"><i class="fa fa-check"></i> Save Changes</button>
                            <a href="{{ route('sites.index') }}" class="btn btn-secondary-outline default" name="reset" value="reset">Cancel</a>
                        </div>
                    </div>
                </div>
                <!-- BEGIN SOCIAL TAB -->
            </div>
        </div>
        <!-- END PROFILE CONTENT -->
    </div>
    {{ Form::close() }}
@stop

@section('head')
    <link href="{{ url()->asset('/vendor/takeoffdesigngroup/cms/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url()->asset('/vendor/takeoffdesigngroup/cms/assets/global/plugins/socicon/socicon.css') }}" rel="stylesheet" type="text/css" />
    <link href="{!! url()->asset('/vendor/takeoffdesigngroup/cms/assets/global/plugins/jquery-minicolors/jquery.minicolors.css') !!}" rel="stylesheet" type="text/css" />
@stop

@section('scripts')
    <script src="{{ url()->asset('/vendor/takeoffdesigngroup/cms/assets/global/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js') }}" type="text/javascript"></script>
    <script src="{{ url()->asset('/vendor/takeoffdesigngroup/cms/assets/global/plugins/holder.js') }}" type="text/javascript"></script>
    <script src="{{ url()->asset('/vendor/takeoffdesigngroup/cms/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
    <script src="{!! url('/vendor/takeoffdesigngroup/cms/assets/global/plugins/jquery-validation/js/jquery.validate.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('/vendor/takeoffdesigngroup/cms/assets/global/plugins/jquery-validation/js/additional-methods.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url()->asset('/vendor/takeoffdesigngroup/cms/assets/global/plugins/jquery-minicolors/jquery.minicolors.min.js') !!}" type="text/javascript"></script>
    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>

    <script>
        $(function() {
            @if ($currentSite->id > 1)
                var crossDomain = true;
                var fileManagerPath = "{!! $currentSite->domain !!}/vendor/takeoffdesigngroup/cms/assets/global/plugins/filemanager/";
                initTinyMCE(crossDomain, fileManagerPath);
            @else
                initTinyMCE();
            @endif

            FormValidation.init();
            FormValidation.site.validate();
            ComponentsColorPickers.init();

            @if ($errors->any())
                var form = $('form');
                var errors = {!! $errors->toJson() !!};
                FormValidation.displayErrors(form, errors);
            @endif
        });

        survey('#favicon', function() {
            updateImage('#faviconPreview', $('#favicon').val());
        });
        survey('#logo', function() {
            updateImage('#logoPreview', $('#logo').val())
        });
    </script>
@stop
