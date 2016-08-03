<?php

/**
 * Created by PhpStorm.
 * User: David
 * Date: 03.08.2016
 * Time: 17:28
 */
class FileHandler
{
    private $url = "";
    private $mimeType = "";
    private $fileExt = "";
    private $fileSize = 0;
    private $type = "";

    public $error = null;

    private static $allowedFiles = array("application/pdf", "application/msword", "image/jpeg", "image/bmp", "image/x-windows-bmp", "image/png", "image/tiff");
    private static $allowedImages = array("image/jpeg", "image/png", "image/bmp", "image/x-windows-bmp");

    public function __construct($url, $type = "")
    {
        // Does the file exist?
        if (!file_exists($url)) {
            $this->error = "Die Datei ".$url." konnte nicht gefunden werden.";
        }
        // What kind of file do we have?
        else {
            $this->url = $url;
            $this->type = $type;
            $this->mimeType = self::getMimeTypeFromExt();
            $this->fileSize = filesize($url);
            $pathinfo = pathinfo($url);
            $this->fileExt = $pathinfo['extension'];

            switch ($this->type) {
                case "file":
                    if (!in_array($this->mimeType, self::$allowedFiles)) {
                        $this->error = "Der Dateityp \"".$this->mimeType."\" ist nicht zum Upload erlaubt. Sollten Sie diese Meldung erhalten, als Sie versucht haben, eine Datei herunterzuladen, kontaktieren Sie bitte umgehend den Seiteninhaber oder den Administrator, es könnte sich um unerlaubte, externe Dateimanipulationen handeln!";
                    }
                    break;
                case "image":
                    if (!in_array($this->mimeType, self::$allowedImages)) {
                        $this->error = "Der Dateityp \"".$this->mimeType."\" ist für Bilddateien nicht erlaubt. Sollten Sie diese Meldung erhalten, als Sie versucht haben, eine Datei herunterzuladen, kontaktieren Sie bitte umgehend den Seiteninhaber oder den Administrator, es könnte sich um unerlaubte, externe Dateimanipulationen handeln!";
                    }
                    break;
            }
        }
    }

    public function getBinaryContent() {
        if (empty($this->error)) {
            return file_get_contents($this->url);
        }
        return "";
    }

    public function getImgResource() {
        if (empty($this->error)) {
            if ($this->type != "image") {
                $this->error = "Es ist nicht möglich, ein Image-Objekt aus einer Datei zu erzeugen, die nicht als Bilddatei ausgezeichnet ist!";
                return null;
            }
            switch ($this->mimeType) {
                case "image/jpeg":
                    return imagecreatefromjpeg($this->url);
                case "image/png":
                    return imagecreatefrompng($this->url);
                default :
                    $this->error = "Die Erzeugung eines Image-Objekts vom Typ ".$this->mimeType." ist aus technischen Gründen nicht möglich.";
                    return null;

            }
        }
        return null;
    }

    public function getFileSizeReadable($decimals = 2) {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($this->fileSize) - 1) / 3);
        return sprintf("%.{$decimals}f", $this->fileSize / pow(1024, $factor)) . @$size[$factor];
    }

    public function getMimeTypeFromExt() {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet'
        );

        $ext = strtolower(array_pop(explode('.',$this->url)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $this->url);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function getFileExt()
    {
        return $this->fileExt;
    }

    public function getFileSize()
    {
        return $this->fileSize;
    }
}