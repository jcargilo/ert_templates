<?php
    date_default_timezone_set('America/New_York');
    class CropAvatar
    {
        private $src;
        private $data;
        private $width;
        private $height;
        private $file;
        private $dst;
        private $type;
        private $extension;
        private $msg;
        private $base_url;

        function __construct($dst, $data, $file)
        {
            $this->setBaseUrl();
            $this->setSrc(is_array($file) ? $file['tmp_name'] : $this->base_url . $file);
            $this->setDst($dst);
            $this->setData($data);
            $this->setFile($file);
            $this->crop();
        }
        
        /* Private Methods */
        private function setBaseUrl()
        {
            switch ($_POST['avatar_base'])
            {
                case 'http://crm.greengoddesssupply.dev':
                    $this->base_url = '/home/vagrant/Code/greengoddesssupply/public';
                    break;
                case 'http://crm.greengoddesssupply.com':
                case 'https://crm.greengoddesssupply.com':
                    $this->base_url = '/home/forge/crm.greengoddesssupply.com/public';
                    break;
            }
        }

        private function setSrc($src)
        {
            if (!empty($src)) {
                $type = exif_imagetype($src);
                if ($type) {
                    $this->src       = $src;
                    $this->type      = $type;
                    $this->extension = image_type_to_extension($type);
                }
            }
        }

        private function setDst($dst)
        {
            $path = parse_url($dst, PHP_URL_PATH);
            $this->dst = $this->base_url . $path;
        }

        private function setData($data)
        {
            if (!empty($data)) {
                $this->data = json_decode(stripslashes($data));
            }

            // Set size.  If size not specified for cropper, use user-specified size.
            $this->width = $_POST['avatar_width'] ?: $this->data->width;
            $this->height = $_POST['avatar_height'] ?: $this->data->height;
        }
        
        private function setFile($file)
        {
            if (!is_array($file))
            {
                $this->file = $this->base_url . $file;
                $this->type = exif_imagetype($this->file);
                return;
            }

            $errorCode = $file['error'];

            if ($errorCode === UPLOAD_ERR_OK) {
                if ($this->type == IMAGETYPE_GIF || $this->type == IMAGETYPE_JPEG || $this->type == IMAGETYPE_PNG) {
                    
                    $filename = $file['name'];
                    $ext = explode(".", $filename);
                    $ext = end($ext);

                    $destination = $this->dst . '/' . $filename;

                    // Make sure directory exists.  If not, create it.
                    if (!file_exists($this->dst))
                        mkdir($this->dst, 0755, true);
                    
                    if (move_uploaded_file($file['tmp_name'], $destination))
                        $this->file = $destination;
                    else
                        $this->msg = 'Failed to save file';
                }
                else
                    $this->msg = 'Please upload image with the following types: JPG, PNG, GIF';
            }
            else
                $this->msg = $this->codeToMessage($errorCode);
        }
        
        private function crop()
        {
            if (!empty($this->file) && !empty($this->dst) && !empty($this->data)) {
                switch ($this->type) {
                    case IMAGETYPE_GIF:
                        $src_img = imagecreatefromgif($this->file);
                        break;
                    
                    case IMAGETYPE_JPEG:
                        $src_img = imagecreatefromjpeg($this->file);
                        break;
                    
                    case IMAGETYPE_PNG:
                        $src_img = imagecreatefrompng($this->file);
                        break;
                }
                
                if (!$src_img) {
                    $this->msg = "Failed to read the image file";
                    return;
                }
                
                $size   = getimagesize($this->file);
                $size_w = $size[0]; // natural width
                $size_h = $size[1]; // natural height
                
                $src_img_w = $size_w;
                $src_img_h = $size_h;
                
                $degrees = $this->data->rotate;
                
                // Rotate the source image
                if (is_numeric($degrees) && $degrees != 0) {
                    // PHP's degrees is opposite to CSS's degrees
                    $new_img = imagerotate($src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127));
                    
                    imagedestroy($src_img);
                    $src_img = $new_img;
                    
                    $deg = abs($degrees) % 180;
                    $arc = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
                    
                    $src_img_w = $size_w * cos($arc) + $size_h * sin($arc);
                    $src_img_h = $size_w * sin($arc) + $size_h * cos($arc);
                    
                    // Fix rotated image miss 1px issue when degrees < 0
                    $src_img_w -= 1;
                    $src_img_h -= 1;
                }
                
                $tmp_img_w = $this->data->width;
                $tmp_img_h = $this->data->height;
                $dst_img_w = $this->width;
                $dst_img_h = $this->height;
                
                $src_x = $this->data->x;
                $src_y = $this->data->y;
                
                if ($src_x <= -$tmp_img_w || $src_x > $src_img_w) {
                    $src_x = $src_w = $dst_x = $dst_w = 0;
                } else if ($src_x <= 0) {
                    $dst_x = -$src_x;
                    $src_x = 0;
                    $src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
                } else if ($src_x <= $src_img_w) {
                    $dst_x = 0;
                    $src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
                }
                
                if ($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h) {
                    $src_y = $src_h = $dst_y = $dst_h = 0;
                } else if ($src_y <= 0) {
                    $dst_y = -$src_y;
                    $src_y = 0;
                    $src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
                } else if ($src_y <= $src_img_h) {
                    $dst_y = 0;
                    $src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
                }
                
                // Scale to destination position and size
                $ratio = $tmp_img_w / $dst_img_w;
                $dst_x /= $ratio;
                $dst_y /= $ratio;
                $dst_w /= $ratio;
                $dst_h /= $ratio;
                
                $dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);
                
                // Add transparent background to destination image
                imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
                imagesavealpha($dst_img, true);
                
                $result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
                
                if ($result) {
                    if (!imagepng($dst_img, $this->file)) {
                        $this->msg = "Failed to save the cropped image file";
                    }
                } else {
                    $this->msg = "Failed to crop the image file";
                }
                
                imagedestroy($src_img);
                imagedestroy($dst_img);
            }
        }
        
        private function codeToMessage($code)
        {
            switch ($code) {
                case UPLOAD_ERR_INI_SIZE:
                    $message = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
                    break;
                
                case UPLOAD_ERR_FORM_SIZE:
                    $message = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
                    break;
                
                case UPLOAD_ERR_PARTIAL:
                    $message = 'The uploaded file was only partially uploaded.';
                    break;
                
                case UPLOAD_ERR_NO_FILE:
                    $message = 'No file was uploaded.';
                    break;
                
                case UPLOAD_ERR_NO_TMP_DIR:
                    $message = 'Missing a temporary folder.';
                    break;
                
                case UPLOAD_ERR_CANT_WRITE:
                    $message = 'Failed to write file to disk.';
                    break;
                
                case UPLOAD_ERR_EXTENSION:
                    $message = 'File upload stopped by extension.';
                    break;
                
                default:
                    $message = 'Unknown upload error.';
            }
            
            return $message;
        }
        
        public function getResult()
        {
            return !empty($this->data) ? str_replace($this->base_url, '', $this->file) : $this->src;
        }
        
        public function getMsg()
        {
            return $this->msg;
        }
    }

    $file = !empty($_FILES['avatar_file']['name']) ? $_FILES['avatar_file'] : $_POST['avatar_file'];
    $crop = new CropAvatar($_POST['avatar_dst'], $_POST['avatar_data'], $file);

    $response = array(
        'state' => 200,
        'message' => $crop->getMsg(),
        'result' => $crop->getResult()
    );

    echo json_encode($response);
?>