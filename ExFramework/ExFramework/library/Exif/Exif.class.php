<?php

/**
 * @class Exif
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 */
class Exif implements IExif {

	/**
	 * Konstruktor
	 * 
	 * @access public
	 * 
	 */		
	public function __construct() {
		//
	}
	
	/**
	 * Zwraca typ obrazu lub rozszerzenie na podstawie typu
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @param bool Wlaczenie/wylaczenie pobierania rozszerzenia OPTIONAL
	 * @return mixed
	 * 
	 */		
	public static function getImageType($path, $extension=true) {
		if (!file_exists($path)) {
			throw new ExifException('Plik: ' . $path . ' nie istnieje');
		}
		if (!$imgType = exif_imagetype($path)) {
			return null;
		}
		if ($extension === false) {
			return $imgType;
		}
		else {
			switch($imgType) {
				case IMAGETYPE_GIF:
					$ext = 'gif';
					break;
				case IMAGETYPE_JPEG:
					$ext = 'jpg';
					break;
				case IMAGETYPE_PNG:
					$ext = 'png';
					break;
				case IMAGETYPE_SWF:
					$ext = 'swf';
					break;
				case IMAGETYPE_PSD:
					$ext = 'psd';
					break;
				case IMAGETYPE_BMP:
					$ext = 'bmp';
					break;
				case IMAGETYPE_TIFF_II:
				case IMAGETYPE_TIFF_MM:
					$ext = 'tif';
					break;
				case IMAGETYPE_JPC:
					$ext = 'jpc';
					break;
				case IMAGETYPE_JP2:
					$ext = 'jp2';
					break;
				case IMAGETYPE_JPX:
					$ext = 'jpx';
					break;
				case IMAGETYPE_JB2:
					$ext = 'jb2';
					break;
				case IMAGETYPE_SWC:
					$ext = 'swc';
					break;
				case IMAGETYPE_IFF:
					$ext = 'iff';
					break;
				case IMAGETYPE_WBMP:
					$ext = 'wbmp';
					break;
				case IMAGETYPE_XBM:
					$ext = 'xbm';
					break;
			}
			return $ext;
		}
	}
	
	/**
	 * Zwraca tablice informacji o obrazie
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @return array
	 * 
	 */		
	public static function getImageInfo($path) {
		if (!file_exists($path)) {
			throw new ExifException('Plik: ' . $path . ' nie istnieje');
		}
		if (!$imgInfo = exif_read_data($path)) {
			return null;
		}
		return $imgInfo;
	}
	
	/**
	 * Zwraca tablice podstawowych informacji o obrazie
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @return array
	 * 
	 */		
	public static function getBasicImageInfo($path) {
		$imgInfo = self::getImageInfo($path);
		$basicInfo = array();
		$basicInfo['FocalLength'] = isset($imgInfo['FocalLength']) ? $imgInfo['FocalLength'] : '';
		$basicInfo['FNumber'] = isset($imgInfo['FNumber']) ? $imgInfo['FNumber'] : '';
		$basicInfo['Make'] = isset($imgInfo['Make']) ? $imgInfo['Make'] : '';
		$basicInfo['Model'] = isset($imgInfo['Model']) ? $imgInfo['Model'] : '';
		$basicInfo['ExposureTime'] = isset($imgInfo['ExposureTime']) ? $imgInfo['ExposureTime'] : '';
		$basicInfo['ExposureProgram'] = isset($imgInfo['ExposureProgram']) ? $imgInfo['ExposureProgram'] : '';
		$basicInfo['ISOSpeedRatings'] = isset($imgInfo['ISOSpeedRatings']) ? $imgInfo['ISOSpeedRatings'] : '';
		$basicInfo['FileDateTime'] = isset($imgInfo['FileDateTime']) ? $imgInfo['FileDateTime'] : '';
		$basicInfo['DateTime'] = isset($imgInfo['DateTime']) ? $imgInfo['DateTime'] : '';
		$basicInfo['MimeType'] = isset($imgInfo['MimeType']) ? $imgInfo['MimeType'] : '';
		$basicInfo['FileType'] = isset($imgInfo['FileType']) ? $imgInfo['FileType'] : '';
		$basicInfo['FileSize'] = isset($imgInfo['FileSize']) ? $imgInfo['FileSize'] : '';
		$basicInfo['ExifImageWidth'] = isset($imgInfo['ExifImageWidth']) ? $imgInfo['ExifImageWidth'] : '';
		$basicInfo['ExifImageLength'] = isset($imgInfo['ExifImageLength']) ? $imgInfo['ExifImageLength'] : '';
		$basicInfo['FileName'] = isset($imgInfo['FileName']) ? $imgInfo['FileName'] : '';
		$basicInfo['Orientation'] = isset($imgInfo['Orientation']) ? $imgInfo['Orientation'] : '';
		return $basicInfo;
	}
	
	/**
	 * Zwraca ciag znakow, ktory mozna wyslac do przegladarki ustawiajac odpowiedni typ MIME poprzez funkcje header
	 * 
	 * @access public
	 * @param string Sciezka do pliku
	 * @param ref Szerokosc zdjecia OPTIONAL
	 * @param ref Wysokosc zdjecia OPTIONAL
	 * @param ref Typ zdjecia OPTIONAL
	 * @return string
	 * 
	 */		
	public static function getThumbnail($path, &$width=null, &$height=null, &$type=null) {
		if (!file_exists($path)) {
			throw new ExifException('Plik: ' . $path . ' nie istnieje');
		}
		if(!$img = exif_thumbnail($path, $width, $height, $type)) {
			return null;	
		}	
		return $img;
	}
	
}

?>
