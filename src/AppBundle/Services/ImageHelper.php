<?php

namespace AppBundle\Services;
use Symfony\Component\Filesystem\Filesystem;

class ImageHelper {
  
    /*
     * string $sourceFile (The full image path with filename and extension)
     */
    public $sourceFile;
    
    /*
     * string $newPath (The new path to where the image needs to be stored)
     */
    public $newFile;
    
    /*
     * int $width (The new width to resize the image to)
     */
    public $width = 0;
    
    /*
     * $height (The new height to resize the image to)
     */
    public $height = 0;
    
    public $quality = 80;
    public $imageData;
    
    public function __construct() {
      if(!file_exists($this->sourceFile)) {
        $this->imageData =  array('newPath' => '', 'image_name' => '');
      }
    }
  
    /**
     * Resize an image
     * @return bool
     */
    public function resize() {
        
        // Get current dimensions
        $ImageDetails = $this->getImageDetails($this->sourceFile);
        $name = $ImageDetails->name;
        $height_orig = $ImageDetails->height;
        $width_orig = $ImageDetails->width;
        $fileExtention = $ImageDetails->extension;
        $ratio = $ImageDetails->ratio;
        $jpegQuality = 80;

        //Resize dimensions are bigger than original image, stop processing
        if ($this->width > $width_orig && $this->height > $height_orig){
            return false;
        }

        /*if($this->height > 0){
            $this->width = $this->height * $ratio;
        } else if($this->width > 0){
            $this->height = $width / $ratio;
        }*/
        $this->width = round($this->width);
        $this->height = round($this->height);

        $gd_image_dest = imagecreatetruecolor($this->width, $this->height);
        $gd_image_src = null;
        switch( $fileExtention ){
            case 'png' :
                $gd_image_src = @imagecreatefrompng($this->sourceFile);
                @imagealphablending( $gd_image_dest, false );
                @imagesavealpha( $gd_image_dest, true );
                break;
            case 'jpeg': case 'jpg': $gd_image_src = @imagecreatefromjpeg($this->sourceFile);
            break;
            case 'gif' : $gd_image_src = @imagecreatefromgif($this->sourceFile);
                break;
            default: break;
        }

        @imagecopyresampled($gd_image_dest, $gd_image_src, 0, 0, 0, 0, $this->width, $this->height, $width_orig, $height_orig);

        $filesystem = new Filesystem();
        $filesystem->mkdir($this->newFile, 0744);
        $newFileName = $this->newFile . $name . "." . $fileExtention;

        $image_name = $name . "." . $fileExtention;

        switch( $fileExtention ){
            case 'png' : imagepng($gd_image_dest, $newFileName); break;
            case 'jpeg' : case 'jpg' : imagejpeg($gd_image_dest, $newFileName, $jpegQuality); break;
            case 'gif' : imagegif($gd_image_dest, $newFileName); break;
            default: break;
        }

        $this->imageData =  array('newPath' => $this->newFile, 'image_name' => $image_name);
    }
  
    /**
     * Crop image
     */
    public function crop() {
        $ImageDetails = $this->getImageDetails($this->sourceFile);
        $name = $ImageDetails->name;
        $fileExtention = $ImageDetails->extension;
        $imgsize = getimagesize($this->sourceFile);
        $width = $imgsize[0];
        $height = $imgsize[1];
        $mime = $imgsize['mime'];
        
        switch($mime){
            case 'image/gif':
                $image_create = "imagecreatefromgif";
                $image = "imagegif";
                break;
            
            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image = "imagepng";
                $this->quality = 7;
                break;
            
            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $this->quality = 80;
                break;
            
            default:
                return false;
                break;
        }
        
        $dst_img = imagecreatetruecolor($this->width, $this->height);
        $src_img = $image_create($this->sourceFile);
        
        $width_new = $height * $this->width / $this->height;
        $height_new = $width * $this->height / $this->width;
        //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
        if($width_new > $width) {
          //cut point by height
          $h_point = (($height - $height_new) / 2);
          //copy image
          imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $this->width, $this->height, $width, $height_new);
        } else {
          //cut point by width
          $w_point = (($width - $width_new) / 2);
          imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $this->width, $this->height, $width_new, $height);
        }
    
        $filesystem = new Filesystem();
        $filesystem->mkdir($this->newFile, 0775);
        $newFileName = $this->newFile . $name . "." . $fileExtention;
        $image_name = $name . "." . $fileExtention;
      
        switch( $fileExtention ){
            case 'png' : imagepng($dst_img, $newFileName); break;
            case 'jpeg':
            case 'jpg' : imagejpeg($dst_img, $newFileName, $this->quality); break;
            case 'gif' : imagegif($dst_img, $newFileName); break;
            default:
              break;
        }
  
        $this->imageData =  array('newPath' => $this->newFile, 'image_name' => $image_name);
    }

    /**
     *
     * Gets image details such as the extension, sizes and filename and returns them as a standard object.
     *
     * @param $imageWithPath
     * @return \stdClass
     */
    private function getImageDetails($imageWithPath) {
      $size = getimagesize($imageWithPath);

      $imgParts = explode("/",$imageWithPath);
      $lastPart = $imgParts[count($imgParts)-1];

      if(stristr("?",$lastPart)){
          $lastPart = substr($lastPart,0,stripos("?",$lastPart));
      }
      if(stristr("#",$lastPart)){
          $lastPart = substr($lastPart,0,stripos("#",$lastPart));
      }

      $dotPos     = stripos($lastPart,".");
      $name         = substr($lastPart,0,$dotPos);
      $extension     = substr($lastPart,$dotPos+1);

      $Details = new \stdClass();
      $Details->height    = $size[1];
      $Details->width        = $size[0];
      $Details->ratio        = $size[0] / $size[1];
      $Details->extension = $extension;
      $Details->name         = $name;

      return $Details;
  }
  
    /**
     * Get array data of image after it' has styled
     * @return array
     */
    public function getData() {
        return $this->imageData;
    }
}