<?php
/**
 * Chunk
 * 
 * Reads a large file in as chunks for easier parsing.
 * 
 * The chunks returned are whole <$this->options['element']/>s found within file.
 * 
 * Each call to read() returns the whole element including start and end tags.
 * 
 * Tested with a 1.8MB file, extracted 500 elements in 0.11s
 * (with no work done, just extracting the elements)
 * 
 * Usage:
 * <code>
 *   // initialize the object
 *   $file = new Chunk('chunk-test.xml', array('element' => 'Chunk'));
 *   
 *   // loop through the file until all lines are read
 *   while ($xml = $file->read()) {
 *     // do whatever you want with the string
 *     $o = simplexml_load_string($xml);
 *   }
 * </code>
 * 
 * @package default
 * @author Dom Hastings
 */
class PMXI_Chunk {
  /**
   * options
   *
   * @var array Contains all major options
   * @access public
   */
  public $options = array(
    'path' => './',       // string The path to check for $file in
    'element' => '',      // string The XML element to return
    'chunkSize' => 1024,    // integer The amount of bytes to retrieve in each chunk
    'type' => 'upload'
  );
  
  /**
   * file
   *
   * @var string The filename being read
   * @access public
   */
  public $file = '';
  /**
   * pointer
   *
   * @var integer The current position the file is being read from
   * @access public
   */
  public $pointer = 0;

  public $cloud = array();

  public $is_validate = true;

  public $open_counter = 0;

  public $encoding = "";

  public $return_with_encoding = true;
  
  /**
   * handle
   *
   * @var resource The fopen() resource
   * @access private
   */
  private $handle = null;
  /**
   * reading
   *
   * @var boolean Whether the script is currently reading the file
   * @access private
   */
  private $reading = false;
  /**
   * readBuffer
   * 
   * @var string Used to make sure start tags aren't missed
   * @access private
   */
  private $readBuffer = '';
  
  /**
   * __construct
   * 
   * Builds the Chunk object
   *
   * @param string $file The filename to work with
   * @param array $options The options with which to parse the file
   * @author Dom Hastings
   * @access public
   */
  public function __construct($file, $options = array(), $pointer = 0, $return_with_encoding = true) {
    // merge the options together
    $this->options = array_merge($this->options, (is_array($options) ? $options : array()));
    $this->return_with_encoding = $return_with_encoding;
    // check that the path ends with a /
    if (substr($this->options['path'], -1) != '/') {
      $this->options['path'] .= '/';
    }
    
    // normalize the filename
    $file_base = basename($file);
    
    // make sure chunkSize is an int
    $this->options['chunkSize'] = intval($this->options['chunkSize']);
    
    // check it's valid
    if ($this->options['chunkSize'] < 64) {
      $this->options['chunkSize'] = 1024;
    }

    $this->options['chunkSize'] *= PMXI_Plugin::getInstance()->getOption('chunk_size');
    
    $this->pointer = $pointer;      

    // set the filename
    $this->file = $file;     
    
    // open the file
    $this->handle = @fopen($this->file, 'rb');      
    
  }
  
  /**
   * __destruct
   * 
   * Cleans up
   *
   * @return void
   * @author Dom Hastings
   * @access public
   */
  public function __destruct() {
    // close the file resource
    if ($this->handle) @fclose($this->handle);
  }
  
  /**
   * read
   * 
   * Reads the first available occurence of the XML element $this->options['element']
   *
   * @return string The XML string from $this->file
   * @author Dom Hastings
   * @access public
   */
  public function read() {
    // check we have an element specified
    if (!empty($this->options['element'])) {
      // trim it
      $element = trim($this->options['element']);
      
    } else {
      $element = '';
    }
    
    // initialize the buffer
    $buffer = false;      

    // if the element is empty, then start auto detect root element tag name
    if (empty($element)) {
      // let the script know we're reading
      $this->reading = true;
      $founded_tags = array();
      // read in the whole doc, cos we don't know what's wanted          
      while ($this->reading and (count($founded_tags) < 500)) {
        $c = @fread($this->handle, $this->options['chunkSize']);                
        
        $c = $this->removeColonsFromRSS($c);

        if ($this->is_validate) {
          if (stripos($c, "xmlns") !== false){ 
            $this->is_validate = false;                        
          }
        }                

        if ( preg_match_all("/<\\w+\\s*[^<|^\n]*\\s*\/?>/i", $c, $matches, PREG_PATTERN_ORDER) ){          
          foreach ($matches[0] as $tag) {
            $tag = explode(" ", trim(str_replace(array('<','>','/'), '', $tag)));
            array_push($founded_tags, $tag[0]);
          }          
        }
        $this->reading = (!@feof($this->handle));
      }
      
    // we must be looking for a specific element
    } 
    
    if (empty($this->encoding)) {      
      fseek($this->handle, 0);
      $this->reading = true;    
      // read in the whole doc, cos we don't know what's wanted      
      while ($this->reading) {
        $c = @fread($this->handle, $this->options['chunkSize']);          
        $enc = preg_match("/<\?xml.*\?>/i", $c, $enc_matches);
        if ($enc)
          $this->encoding = $enc_matches[0];                  
        $this->reading = false;
      }      
    }      

    if (empty($this->encoding)) $this->encoding = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";

    if (empty($element) and !empty($founded_tags)) {      
      
      $element_counts = array_count_values($founded_tags);                  
      
      if (!empty($element_counts)){

        //$this->cloud = array_slice($element_counts, 0, 2);

        foreach ($element_counts as $tag => $count) {    
          if (strpos($tag, ":") === false){
            if ($count > 1 and empty($this->options['element'])) {
              $this->options['element'] = $element = $tag;            
            }          
            else{
              $this->cloud[$tag] = $count;    
            }
          }
        }
      }
            
    }    
    
    // return it all if element doesn't founded
    if (empty($element)){
      if (!empty($element_counts)){
        $this->options['element'] = $element = array_shift(array_keys($element_counts));
      }
      else return false;
    }                    

    // we must be looking for a specific element    
    
    // initialize the buffer
    $buffer = false;

    // set up the strings to find
    $open = '<'.$element;
    $close = '</'.$element.'>';
    
    // let the script know we're reading
    $this->reading = true;
    
    // reset the global buffer
    $this->readBuffer = '';
    
    // this is used to ensure all data is read, and to make sure we don't send the start data again by mistake
    $store = false;   

    $checkOpen = false;        

    // seek to the position we need in the file
    fseek($this->handle, $this->pointer);

    // start reading
    while ($this->reading && !@feof($this->handle)) {      

      // store the chunk in a temporary variable                        
      $tmp = @fread($this->handle, $this->options['chunkSize']);
      
      $tmp = $this->removeColonsFromRSS($tmp);

      // update the global buffer
      $this->readBuffer .= $tmp;
      
      if ($checkOpen === false){

        $checkOpen = preg_match_all("/".$open."[ |>]{1}/i", $tmp, $checkOpenmatches, PREG_OFFSET_CAPTURE);

        if (!empty($checkOpenmatches[0])){          

          $checkOpen = $checkOpenmatches[0][0][1];

        }
        else $checkOpen = false;

        // check for the open string
        /*$checkOpen = stripos($tmp, $open." "); 
        if ($checkOpen === false) stripos($tmp, $open.">");                */
        
        // if it wasn't in the new buffer
        if ($checkOpen === false && !($store)) {
          // check the full buffer (in case it was only half in this buffer)

          $checkOpen = preg_match_all("/".$open."[ |>]{1}/i", $this->readBuffer, $checkOpenmatches, PREG_OFFSET_CAPTURE);

          if (!empty($checkOpenmatches[0])){          

            $checkOpen = $checkOpenmatches[0][0][1];

          }
          else $checkOpen = false;

          /*$checkOpen = strpos($this->readBuffer, $open." ");
          if ($checkOpen === false) $checkOpen = strpos($this->readBuffer, $open.">");*/

          // if it was in there
          if ($checkOpen !== false) {
            // set it to the remainder
            $checkOpen = $checkOpen % $this->options['chunkSize'];
          }          
        }
      }      

      // check for the close string
      $checkClose = preg_match_all("/<\/".$element.">/i", $tmp, $closematches, PREG_OFFSET_CAPTURE);
      $withoutcloseelement = (preg_match("%<".$element."\s{1,}[^<]*\/>%i", $tmp, $matches)) ? strpos($tmp, $matches[0]) : false;

      if ($withoutcloseelement and $checkClose and $closematches[0][0][1] > $withoutcloseelement) $checkClose = false;       

      if (!$checkClose){ 
        $checkClose = (preg_match("%<".$element."\s{1,}[^<]*\/>%i", $tmp, $matches)) ? strpos($tmp, $matches[0]) : false;                
        
        if ($checkClose !== false) 
          $withoutcloseelement = true;
        else{
          $checkClose = (preg_match_all("%<".$element."\s{1,}[^<]*\/>%i", $this->readBuffer, $matches)) ? strpos($this->readBuffer, $matches[0][count($matches[0]) - 1]) : false;
          if ($checkClose !== false) {
            $withoutcloseelement = true;
            $matches[0] = $matches[0][count($matches[0]) - 1];
          }
        }
      }      
      else{                
        
        $close_finded = false;
        $length = $closematches[0][0][1] - $checkOpen;
        
        $checkDuplicateOpen = preg_match_all("/".$open."[ |>]{1}/i", substr($this->readBuffer, $checkOpen, $length), $matches, PREG_OFFSET_CAPTURE);
        
        while (!$close_finded){                                        
          if ($checkDuplicateOpen > 1 and !empty($closematches[0][$checkDuplicateOpen - 1])){
            $secondcheckDuplicateOpen = preg_match_all("/".$open."[ |>]{1}/i", substr($this->readBuffer, $checkOpen, $closematches[0][$checkDuplicateOpen - 1][1] - $checkOpen), $matches, PREG_OFFSET_CAPTURE);            
            if ($secondcheckDuplicateOpen == $checkDuplicateOpen){
              $checkClose = $closematches[0][$checkDuplicateOpen - 1][1];
              $close_finded = true;              
            }
            else{
              $checkClose = false;
              $checkDuplicateOpen = $secondcheckDuplicateOpen;
            }
          }          
          elseif ($checkDuplicateOpen > 1){
            $checkClose = false;
            $close_finded = true;
            $store = true;
          }
          else{
            $checkClose = $closematches[0][0][1];
            $close_finded = true;
          }          
        }        
      } 

      if ($checkClose !== false) {
        // add the length of the close string itself
        if ( ! $withoutcloseelement)
          $checkClose += strlen($close);
        else
          $checkClose += strlen($matches[0]); // "/>" symbols

      }      
      
      // if we've found the opening string and we're not already reading another element
      if ($checkOpen !== false && !($store)) {
        // if we're found the end element too
        if ($checkClose !== false) {
          // append the string only between the start and end element
          $buffer .= substr($tmp, $checkOpen, ($checkClose - $checkOpen));
          
          // update the pointer
          $this->pointer += $checkClose;
          
          // let the script know we're done
          $this->reading = false;
          
        } else {
          // append the data we know to be part of this element
          $buffer .= substr($tmp, $checkOpen);
          
          // update the pointer
          $this->pointer += $this->options['chunkSize'];
          
          // let the script know we're gonna be storing all the data until we find the close element          
          $store = true;                    

        }
        
      // if we've found the closing element
      } elseif ($checkClose !== false) {
        // update the buffer with the data upto and including the close tag
        $buffer .= substr($tmp, 0, $checkClose);
        
        // update the pointer
        $this->pointer += $checkClose;
        
        // let the script know we're done
        $this->reading = false;
        
      // if we've found the closing element, but half in the previous chunk
      } elseif ($store) {
        // update the buffer
        $buffer .= $tmp;        
        
        // and the pointer
        $this->pointer += $this->options['chunkSize'];
      }
      
    }   
   
    // return the element (or the whole file if we're not looking for elements)
    return $buffer;
  }  

  function removeColonsFromRSS($feed) {
      // pull out colons from start tags
      // (<\w+):(\w+>)
      $pattern = '/(<\w+):(\w+[ |>]{1})/i';
      $replacement = '$1_$2';
      $feed = preg_replace($pattern, $replacement, $feed);
      // pull out colons from end tags
      // (<\/\w+):(\w+>)
      $pattern = '/(<\/\w+):(\w+>)/i';
      $replacement = '$1_$2';
      $feed = preg_replace($pattern, $replacement, $feed);
      return $feed;
  }
}
