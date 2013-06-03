<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/**
 * ***************************
 *	Bar Generator
 * 	By Mathew Collins
 * 	mathew.collins@gmail.com	http://onovia.com
 * 
 *     This class is available free of charge for personal or non-profit works. If
 *     you are using it in a commercial setting, please contact the author for license
 *     information.
 *     This class is provided as is, without guarantee. You are free to modify
 *     and redistribute this code, provided that the original copyright remain in-tact.
 * 
 *******************************/
 
/*
  This code has been modified for ezRPG.
*/


/*
  Class: ImageBar
  Generate a simple bar image used to represent player stats graphically.
  
  Example Usage:
  > $bar = new ImageBar(); // Load the class
  > $bar->setWidth(150); // Set the width
  > $bar->makeBar(); // Start the bar
  > $bar->setFillColor('blue'); //Blue bar
  > $bar->setData($player->max_exp, $player->exp); // Give the bar some values
  > $bar->setTitle('EXP: '); //Set title
  > $bar->generateBar(); //Display the image
  
  See bar.php for a complete, working example.
*/
class ImageBar
{
    /*
      Integer: $bar_w
      Width of the bar image.
    */
    protected $bar_w = 100;
    
    /*
      Integer: $bar_h
      Height of the bar image.
    */
    protected $bar_h = 15;
    
    /*
      Integer: $fontsize
      Font size of the text.
    */
    protected $fontsize = 3;
    
    /*
      String: $fileType
      The type of image file to generate. GIF, PNG or JPG
    */
    protected $fileType = 'GIF';
    
    /*
      Variable: $fillcolor
      A colour identifier representing the fill colour composed of RGB components.
    */
    protected $fillcolor;
    
    /*
      Variable: $backcolor
      A colour identifier representing the background colour composed of RGB components.
    */
    protected $backcolor;
    
    /*
      Integer: $dataPercent
      The percent of data.
    */
    protected $dataPercent;
    
    /*
      Integer: $barPercent
      The percent of the width bar to be filled in, in pixels.
    */
    protected $barPercent;
    
    /*
      String: $text
      The text to be displayed on the bar.
    */
    protected $text;
    
    /*
      Variable: $bar
      The image identified of the bar.
    */
    protected $bar;
    
    /*
      Function: setWidth
      Sets the value of <$bar_w>.
      
      Parameters:
      $value - The new value to set.
    */
    public function setWidth($value)
    {
        $this->bar_w = $value;
    }
    
    /*
      Function: setHeight
      Sets the value of <$bar_h>.
      
      Parameters:
      $value - The new value to set.
    */
    public function setHeight($value)
    {
        $this->bar_h = $value;
    }
    
    /*
      Function: setFontSize
      Sets the value of <$fontsize>.
      
      Parameters:
      $value - The new value to set.
    */
    public function setFontSize($value)
    {
        $this->fontsize = $value;
    }
    
    /*
      Function: setFileType
      Sets the value of <$fileType>.
      
      Parameters:
      $value - The new value to set. Can be 'GIF', 'PNG' or 'JPG'.
    */
    public function setFileType($value)
    {
        $this->fileType = $value;
    }
    
    /*
      Function: setFillColor
      Sets the value of <$fillcolor>.
      
      Parameters:
      $value - The new value to set. Can be 'green', 'red', 'yellow' or 'blue'.
    */
    public function setFillColor($value)
    {
        // Bar Colors
        switch ($value)
        {
          case 'green':
              $this->fillcolor = imagecolorallocate($this->bar, 50, 170, 0);
              break;
          case 'red':
              $this->fillcolor = imagecolorallocate($this->bar, 170, 0, 0);
              break;
          case 'yellow':
              $this->fillcolor = imagecolorallocate($this->bar, 220, 220, 0);
              break;
          case 'blue':
              $this->fillcolor = imagecolorallocate($this->bar, 0, 0, 200);
              break;
          default:
              $this->fillcolor = imagecolorallocate($this->bar, 170, 170, 170);
        }
    }
	
    /*
      Function: setBackColor
      Sets the value of <$backcolor>.
	
      Parameters:
      $value - The new value to set.
    */
    public function setBackColor()
    {
        $this->backcolor = imagecolorallocate($this->bar, 200, 0, 0);
    }
	
    /*
      Function: setData
      Sets the value of <$dataPercent> and <$text>.
	
      Parameters:
      $max - The maximum value of the data.
      $value - The current value of the data.
    */
    public function setData($max, $value)
    {
        $this->dataPercent = intval(($value / $max) * 100);
        $this->text = $value . " / " . $max;
    }
	
    /*
      Function: setTitle
      Adds a prefix to the text data.
	
      Parameters:
      $prefix - The new value to set.
    */
    public function setTitle($prefix)
    {
        $this->text = $prefix . $this->text;
    }
	
    /*
      Function: makeBar
      Creates an image in <$bar> then calls <setBackColor>.
	
      See Also:
      - <setBackColor>
    */
    public function makeBar()
    {
        $this->bar = imagecreate($this->bar_w, $this->bar_h);
        $this->setBackColor();
    }
	
    /*
      Function: generateBar
      Generates the image file, outputs it, then destroys the image.
    */
    public function generateBar()
    {		
        $white 	= imagecolorallocate($this->bar, 255, 255, 255);
        $grey 	= imagecolorallocate($this->bar, 180, 180, 180);
        $black 	= imagecolorallocate($this->bar, 0, 0, 0);
		
        // Background
        imagefill($this->bar, 0, 0, $this->backcolor);
        // Fill
        $this->barPercent = $this->bar_w / 100 * $this->dataPercent;
        imagefilledrectangle($this->bar, 0, 0, $this->barPercent, $this->bar_h, $this->fillcolor);
        // Border
        imagerectangle($this->bar, 0, 0, $this->bar_w - 1, $this->bar_h - 1, $grey);
        // Text
        imagestring($this->bar, $this->fontsize, round(($this->bar_w/2)-((strlen($this->text)*imagefontwidth($this->fontsize))/2), 1), round(($this->bar_h/2)-(imagefontheight($this->fontsize)/2)), $this->text, $white);
		
		
        // Output, check for various image type support
        if ((imagetypes() & IMG_PNG) && $this->fileType == "PNG")
        {
            header('Content-type: image/png');
            imagepng($this->bar, "", 9);
        }
        else if ((imagetypes() & IMG_GIF) && $this->fileType == "GIF")
        {
            header ("Content-type: image/gif");
            imagegif($this->bar);
        }
        else if ((imagetypes() & IMG_JPG) && $this->fileType == "JPG")
        {
            header("Content-type: image/jpeg");
            imagejpeg($this->bar);
        }
        else
        {
            return 0;
        }
	
        imagedestroy($this->bar);
    }
}
?>