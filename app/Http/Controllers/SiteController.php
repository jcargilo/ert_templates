<?php namespace App\Http\Controllers;
 
use TakeoffDesignGroup\CMS\Models\Pages\Page;
use TakeoffDesignGroup\CMS\Models\Logs\EmailLog;
use App\Models\Slideshows\Slideshow;
use TakeoffDesignGroup\CMS\Models\Testimonials\Testimonial;
use TakeoffDesignGroup\CMS\Models\Widgets\Category;
use TakeoffDesignGroup\CMS\Models\Widgets\Widget;
use Illuminate\Http\Request;
use Response, Mail, Redirect, URL, Carbon\Carbon;

class SiteController extends SiteBaseController
{
	public function index(Request $request, $slug = '')
    {
        // Check for coming soon page setting.
        if ($this->site->show_coming_soon && 
            ($slug === '/' || $slug === '' || $slug === 'coming-soon'))
            return \View::make('site._layouts.coming_soon');

        else if ($slug === '/' || $slug === '')
            $slug = 'home';

        return $this->loadView($slug);
    }

    public function contact(Request $request)
    {
        // Get form input, add additional keys to array.
        $data = $request->all();
        
        if ($data['email'] === '')
        {
            // Setup reply to.
            $replyTo = array(
                'email' => $data['cemail'],
                'name'  => $data['first_name'].' '.$data['last_name'],
            );

            $this->sendEmailToAdmin('site.emails.contact', 'Contact Request', $data, $replyTo);
        }
        return Redirect::back()->with('success', 'Your contact request has been submitted successfully to '.$this->site->title.'.');
    }

    /**********************************************************************************************
     *
     *  Private Functions
     *
     **********************************************************************************************/
    private function loadView($slug)
    {
        $this->page = Page::findBySlug($slug, $this->site->id)->first();
        
        // Make sure page exists.
        if (empty($this->page))
            return Response::view('cms::frontend.404', array(), 404);
        else if ($this->page->redirect)
            return Redirect::to($this->page->redirect_page_id > 0 ? Helpers::getParentFolder($this->page->redirect_page_id) : $this->page->redirect_page_other);
        else
            $this->page->sections = $this->setupSections($this->page->sections);

        // Check for password protection.
        if ($this->page !== null && $this->page->published == 'Password Protected')
        {
            $loggedIn = FALSE;
            
            // Determine if user has already successfully provided a password for this page in the session.
            if (Session::has($this->page->id))
            {
                if(Session::get($this->page->id) === $this->page->password)
                   $loggedIn = TRUE;
            }

            // Show login page where necessary.
            if (!$loggedIn)
                $view = \View::make('cms::frontend._templates.password');
        }

        if (empty($view)) {
            $view = \View::make('cms::frontend.index');
        }

        // Add page & seo data to all views/nested subviews.
        \View::share('page', $this->page);
        \View::share('seo', $this->setupSEO());

        return $view;
    }

    private function setupSections($sections)
    {
        $sections = Page::setupSectionCSS($sections);
        $sections = self::setupCustomColumns($sections);

        return $sections;
    }

    private function setupCustomColumns($sections)
    {
        foreach ($sections as $key => $section)
        {
            // Retrieve custom layouts.
            if (strpos($section->layout, '-') !== false)
            {
                $layout = explode('-', $section->layout);
                $section->layout = $layout;
            }

            // Cycle columns for custom features.
            foreach($section->columns as $column)
            {
                // Setup slideshow, where applicable.
                if ($column->slideshow_id != NULL)
                {
                    $slideshow = Slideshow::published($column->slideshow_id)->first();
                    $column->slideshow = $slideshow;
                }

                // Setup template, where applicable.
                if ($column->template_id != NULL)
                {
                    // Load custom functionality
                    switch($column->template->url)
                    {
                        default:
                            $template = view()->exists('site._templates.'.$column->template->url)
                                ? view()->make('site._templates.'.$column->template->url)
                                : NULL;
                            break;
                    }

                    $column->template = $template;
                }
            }
        }

        return $sections;
    }

    private function setupSectionCSS($sections)
    {
        foreach ($sections as $key => $section)
        {
            $class = 'content-block';
            $style = '';
            $pageStyle = '';
            $borderStyle = '';
            $overlayClass = '';
            $overlayStyle = '';

            // If this section is the first section, add the .first class for custom processing.
            if ($key == 0) 
                $class .= ' first';

            if ($section->section_background_color !== '') 
                $style .= sprintf('background-color: %s;', $section->section_background_color);

            if ($section->page_background_color !== '')
                $pageStyle .= sprintf('background-color: %s;', $section->page_background_color);

            // Strip domain from background image path, where applicable
            if ($section->background_image !== '')
            {
                $style .= sprintf('background-image: url(%s);', URL::asset($section->background_image));
                $class .= $section->background_stretch ? ' cover' : '';

                if (!Agent::isMobile() && $section->background_parallax) 
                {
                    $class .= ' parallax bg-fixed';

                    // Stretch the image to fill the section if specified.
                    if ($section->background_stretch)
                    {
                        // Apply IE8 Hack for background-size.
                        $style .= 'background-size: 100%;';
                        $style .= sprintf('filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=%s,sizingMethod=\'scale\';)', $section->background_image);
                        $style .= sprintf('-ms-filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=%s,sizingMethod=\'scale\');', $section->background_image);
                    }
                    
                    // Set height (mandatory for parallax effect - default of 300px)
                    $style .= sprintf('height: %spx;', $section->background_size !== '' ? $section->background_size : '300');

                    // Apply background positioning if specified
                    if ($section->background_position_x !== 0 || $section->background_position_y !== 0)
                    {
                        $x = $section->background_position_x == '' ? 0 : $section->background_position_x;
                        $y = $section->background_position_y == '' ? 0 : $section->background_position_y;
                        $style .= 'background-position: '.$x.'% '.$y.'%;';
                    }
                    else
                        $style .= 'background-position: 50% 50%;';
                }
                else 
                {
                    // Don't apply custom background effects for mobile
                    if (Agent::isMobile() && !Agent::isTablet())
                    {
                        $style .= 'background-size: cover;';
                        $style .= $section->background_size === '' ? 'min-height: 150px;' : '';

                        // Apply background positioning if specified
                        if ($section->background_position_x !== 0 || $section->background_position_y !== 0)
                        {
                            $x = $section->background_position_x == '' ? 0 : $section->background_position_x;
                            $y = $section->background_position_y == '' ? 0 : $section->background_position_y;
                            $style .= 'background-position: '.$x.'% '.$y.'%;';
                        }
                        else
                            $style .= 'background-position: 50% 50%;';
                    }
                    else
                    {
                        // Apply fixed styling if specified.
                        if ($section->background_fixed) {
                            $class .= ' bg-fixed';
                        }

                        // Apply background repeating properties.
                        $repeatStyle = 'no-repeat';
                        if ($section->background_repeat_x && $section->background_repeat_y)
                            $repeatStyle = 'repeat';
                        else if ($section->background_repeat_x)
                            $repeatStyle = 'repeat-x';
                        else if ($section->background_repeat_y)
                            $repeatStyle = 'repeat-y';
                        $style .= sprintf('background-repeat: %s;', $repeatStyle);

                        if ($repeatStyle === 'no-repeat')
                        {
                            // If no minimum height is specified, make sure that certain styles are overriden to keep the image 100% width.
                            $override = $section->background_size === '' ? '; min-height: 150px;' : ';';

                            // Stretch the image to fill the section if specified.
                            if ($section->background_stretch)
                            {
                                $domain = str_replace('www.', '', $this->site->domain); // remove www. from domain (i.e http://www.sample.com -> http://sample.com)
                                $path = str_replace($domain, '', $section->background_image); // remove domain from image url (i.e. http://sample.com/image.png -> /image.png)
                                $backgroundImage = public_path().$path;
                                if (file_exists($backgroundImage))
                                {
                                    // Determine aspect ratio of image
                                    list($width, $height) = getimagesize($backgroundImage);
                                    $ratio = $height / $width * 100;
                                    $style .= 'height: 0;padding: 0;background-size: 100%'.$override;
                                    $style .= 'padding-bottom: '.($ratio - 0.1).'%;'; // 0.1 will trim off the occasional 1-2 pixels of overlap

                                    // Apply IE8 Hack for background-size.
                                    $style .= sprintf('filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=%s,sizingMethod=\'scale\';)', $section->background_image);
                                    $style .= sprintf('-ms-filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=%s,sizingMethod=\'scale\');', $section->background_image);
                                }
                            }
                        }

                        // Apply background positioning if specified
                        if ($section->background_position_x !== '0' || $section->background_position_y !== '0')
                        {
                            $x = $section->background_position_x == '' ? 0 : $section->background_position_x;
                            $y = $section->background_position_y == '' ? 0 : $section->background_position_y;
                            $style .= 'background-position: '.$x.'% '.$y.'%;';
                        }
                    }
                }
            }

            if ($section->background_size !== '')
            {
                $style .= sprintf('min-height: %spx%s;', 
                    $section->background_size,
                    $section->background_image === '' ? ' !important' : '');

                // Apply minimum height to page style if color specified but no background image was specified.
                if ($section->background_image === '' && $section->page_background_color !== '')
                    $pageStyle .= sprintf('min-height: %spx;', $section->background_size);
            }

            // Add the padding classes, where applicable
            if ($section->spacing_above !== '')
                $class .= ' '.$section->spacing_above;

            if ($section->spacing_below !== '')
                $class .= ' '.$section->spacing_below;

            // Setup border, where appicable
            if ($section->border_color !== '' && $section->border_style !== '' && 
                ($section->border_top_width > 0 || $section->border_right_width > 0 || $section->border_bottom_width > 0 || $section->border_left_width > 0))
            {
                $borderStyle .= sprintf('border-color: %s;', $section->border_color);
                $borderStyle .= sprintf('border-style: %s;', $section->border_style);
                $borderStyle .= sprintf('border-width: %spx %spx %spx %spx;', $section->border_top_width, $section->border_right_width, $section->border_bottom_width, $section->border_left_width);

                if ($section->padding_top > 0 || $section->padding_right > 0 || $section->padding_bottom > 0 || $section->padding_left > 0)
                    $borderStyle .= sprintf('padding: %spx %spx %spx %spx;', $section->padding_top, $section->padding_right, $section->padding_bottom, $section->padding_left);
            }

            // Setup overlay, where applicable
            if ($section->overlay)
            {
                $overlayClass = ' overlay';
                $overlayClass .= !empty($section->overlay_class) ? ' '.$section->overlay_class : '';
                $overlayStyle = sprintf(' style="%s: %spx"', 
                    $section->overlay_position === '' ? 'top' : $section->overlay_position,
                    $section->overlay_distance === '' ? '0' : $section->overlay_distance);
            }

            // Add custom css classes
            $class .= $class !== '' ? ' ' . $section->classes : $section->classes;

            $sections[$key]->class = $class;
            $sections[$key]->style = $style;
            $sections[$key]->pageStyle = $pageStyle;
            $sections[$key]->borderStyle = $borderStyle;
            $sections[$key]->overlayClass = $overlayClass;
            $sections[$key]->overlayStyle = $overlayStyle;
        }

        return $sections;
    }
    
    private function sendEmailToUser($template, $subject, $data, $to)
    {
        $settings = $this->site;
        $data['root'] = env('APP_URL');
        $data['site'] = $settings->title;

        // Add logo (where available).
        if ($settings->logo != '')
            $data['logo'] = URL::asset($settings->logo);

        // Remove all spaces, and retrieve list of emails to send to.
        $admins = str_replace(' ', '', $settings->email);
        $admins = explode(',', $admins);
        $replyTo = $admins[0];

        // Pre-render template
        $body = \View::make($template, $data)->render();
        $data['body'] = $body;

        // Log email to database.
        $log                    = new EmailLog;
        $log->from_email        = 'hopescookiespa@gmail.com';
        $log->from_name         = $settings->title;
        $log->reply_to_email    = $replyTo;
        $log->to                = $to;
        $log->subject           = $subject;
        $log->body              = $body;
        $log->save();

        // Send email
        Mail::send('site.emails.body', $data, function($message) use ($replyTo, $settings, $to, $subject)
        {
            $message->replyTo($replyTo);
            $message->to($to)
                    ->subject($subject);
        });
    }

    private function sendEmailToAdmin($template, $subject, $data, $replyTo = NULL)
    {
        $settings = $this->site;
        $data['root'] = env('APP_URL');
        $data['site'] = $settings->title;

        // Add logo (where available).
        if ($settings->logo != '')
            $data['logo'] = URL::asset($settings->logo);

        if (isset($data['to']))
            $recipients[] = $data['to'];
        else 
        {
            // Remove all spaces, and retrieve list of emails to send to.
            $recipients = str_replace(' ', '', $settings->email);
            $recipients = explode(',', $recipients);
        }

        // Pre-render template
        $body = \View::make($template, $data)->render();
        $data['body'] = $body;

        // Send email to all recipients.
        foreach($recipients as $to)
        {
            // Log email to database.
            $log                    = new EmailLog;
            $log->from_email        = 'comments@hopescookies.com';
            $log->from_name         = $settings->title;
            $log->reply_to_email    = $replyTo == NULL ? '' : $replyTo['email'];
            $log->reply_to_name     = $replyTo == NULL ? '' : $replyTo['name'];
            $log->to                = $to;
            $log->subject           = $subject;
            $log->body              = $body;
            $log->save();

            // Send mail to admin
            Mail::send('site.emails.body', $data, function($message) use ($settings, $replyTo, $to, $subject)
            {
                if ($replyTo != NULL)
                    $message->replyTo($replyTo['email'], $replyTo['name']);

                $message->to($to)->subject($subject);
            });
        }
    }

    private function getWidgets($id)
    {
        // Retrieve widgets
        $category = Category::with('widgets')->where('id', '=', $id)->get()->first();

        // Parse content
        $widgets = array();
        if (!empty($category))
        {
            foreach($category->widgets as $widget)
                $widget = Widget::parseContent($widget);

            $widgets = $category->widgets;
        }

        return $widgets;
    }

    private function setupSEO()
    {
        $seo = new \stdClass;

        // Build page title in the following availability order: Page SEO Title -> Page Title, a hyphen, and then Site SEO Title -> Site Title.
        $seo->title = ucfirst($this->page->seo_title === '' ? $this->page->title : $this->page->seo_title);
        $seo->title .= ' - ';
        $seo->title .= $this->site->seo_title === '' ? $this->site->title : $this->site->seo_title;

        $seo->keywords      = $this->page->seo_keywords === '' ? $this->site->seo_keywords : $this->page->seo_keywords;
        $seo->description   = $this->page->seo_description === '' ? $this->site->seo_description : $this->page->seo_description;
        return $seo;
    }
} 