<?php

namespace FileUpload\FileNameGenerator {
use FileUpload\FileSystem\FileSystem;
use FileUpload\PathResolver\PathResolver;
use FileUpload\Util;

class Booya implements FileNameGenerator {
    /**
     * Pathresolver
     * @var PathResolver
     */
    private $pathresolver;

    /**
     * Filesystem
     * @var FileSystem
     */
    private $filesystem;

    /**
     * Get file_name
     * @param  string       $source_name
     * @param  string       $type
     * @param  string       $tmp_name
     * @param  integer      $index
     * @param  string       $content_range
     * @param  Pathresolver $pathresolver
     * @param  Filesystem   $filesystem
     * @return string
     */
    public function getFileName($source_name, $type, $tmp_name, $index, $content_range, $pathresolver, $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->pathresolver = $pathresolver;
        $strippedName = preg_replace('/\s+/', '', $source_name);
        $strippedName = str_replace(',', '', $strippedName);
        return($this->getUniqueFilename(rawurlencode($strippedName), $type, $index, $content_range));
    }

    /**
     * Get unique but consistent name
     * @param  string  $name
     * @param  string  $type
     * @param  integer $index
     * @param  array   $content_range
     * @return string
     */
    protected function getUniqueFilename($name, $type, $index, $content_range) {
        while($this->filesystem->isDir($this->pathresolver->getUploadPath($name))) {
            $name = $this->pathresolver->upcountName($name);
        }
        $uploaded_bytes = Util::fixIntegerOverflow(intval($content_range[1]));
        while($this->filesystem->isFile($this->pathresolver->getUploadPath($name))) {
            if($uploaded_bytes == $this->filesystem->getFilesize($this->pathresolver->getUploadPath($name))) {
                break;
            }
            $name = $this->pathresolver->upcountName($name);
        }
        return $name;
    }
}

} // END namespace FileUpload\FileNameGenerator



namespace FileUpload\PathResolver {

class Booya implements PathResolver {
  /**
   * Main path
   * @var string
   */
  protected $main_path;

  /**
   * A construct to remember
   * @param string $main_path Where files should be stored
   */
  public function __construct($main_path) {
    if (!file_exists($main_path)) {
        mkdir($main_path, 0755, true);
    }
    $this->main_path = $main_path;
  }

  /**
   * @see PathResolver
   */
  public function getUploadPath($name = null) {
    return $this->main_path . '/' . $name;
  }

  /**
   * @see PathResolver
   */
  public function upcountName($name) {
    return preg_replace_callback('/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/', function ($matches) {
      $index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
      $ext   = isset($matches[2]) ? $matches[2] : '';
      return '-'.$index.$ext;
    }, $name, 1);
  }
}

} // END namespace FileUpload\PathResolver


namespace FileUpload {

class Booya extends FileUpload {

    public function getError(){
        return $this->upload['error'][0];
    }

    public function getErrorMessage() {
        return $this->messages[$this->upload['error'][0]];
    }

}
} // END namespace FileUpload
