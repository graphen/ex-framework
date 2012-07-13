<?php

/**
 * @class I18n
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class I18n implements II18n {
	
	/**
	 * Tablica kodow jezykow
	 *
	 * @var array
	 * 
	 */		
	protected $_iso639Codes = array('aa' => 'Afar',
                              'ab' => 'Abkhazian',
                              'af' => 'Afrikaans',
                              'am' => 'Amharic',
                              'ar' => 'Arabic',
                              'as' => 'Assamese',
                              'ay' => 'Aymara',
                              'az' => 'Azerbaijani',
                              'ba' => 'Bashkir',
                              'be' => 'Byelorussian',
                              'bg' => 'Bulgarian',
                              'bh' => 'Bihari',
                              'bi' => 'Bislama',
                              'bn' => 'Bengali',
                              'bo' => 'Tibetan',
                              'br' => 'Breton',
                              'ca' => 'Catalan',
                              'co' => 'Corsican',
                              'cs' => 'Czech',
                              'cy' => 'Welsh',
                              'da' => 'Danish',
                              'de' => 'German',
                              'dz' => 'Bhutani',
                              'el' => 'Greek',
                              'en' => 'English',
                              'eo' => 'Esperanto',
                              'es' => 'Spanish',
                              'et' => 'Estonian',
                              'eu' => 'Basque',
                              'fa' => 'Persian',
                              'fi' => 'Finnish',
                              'fj' => 'Fiji',
                              'fo' => 'Faeroese',
                              'fr' => 'French',
                              'fy' => 'Frisian',
                              'ga' => 'Irish',
                              'gd' => 'Gaelic',
                              'gl' => 'Galician',
                              'gn' => 'Guarani',
                              'gu' => 'Gujarati',
                              'ha' => 'Hausa',
                              'hi' => 'Hindi',
                              'hr' => 'Croatian',
                              'hu' => 'Hungarian',
                              'hy' => 'Armenian',
                              'ia' => 'Interlingua',
                              'ie' => 'Interlingue',
                              'ik' => 'Inupiak',
                              'in' => 'Indonesian',
                              'is' => 'Icelandic',
                              'it' => 'Italian',
                              'iw' => 'Hebrew',
                              'ja' => 'Japanese',
                              'ji' => 'Yiddish',
                              'jw' => 'Javanese',
                              'ka' => 'Georgian',
                              'kk' => 'Kazakh',
                              'kl' => 'Greenlandic',
                              'km' => 'Cambodian',
                              'kn' => 'Kannada',
                              'ko' => 'Korean',
                              'ks' => 'Kashmiri',
                              'ku' => 'Kurdish',
                              'ky' => 'Kirghiz',
                              'la' => 'Latin',
                              'ln' => 'Lingala',
                              'lo' => 'Laothian',
                              'lt' => 'Lithuanian',
                              'lv' => 'Latvian',
                              'mg' => 'Malagasy',
                              'mi' => 'Maori',
                              'mk' => 'Macedonian',
                              'ml' => 'Malayalam',
                              'mn' => 'Mongolian',
                              'mo' => 'Moldavian',
                              'mr' => 'Marathi',
                              'ms' => 'Malay',
                              'mt' => 'Maltese',
                              'my' => 'Burmese',
                              'na' => 'Nauru',
                              'ne' => 'Nepali',
                              'nl' => 'Dutch',
                              'no' => 'Norwegian',
                              'oc' => 'Occitan',
                              'om' => 'Oromo',
                              'or' => 'Oriya',
                              'pa' => 'Punjabi',
                              'pl' => 'Polish',
                              'ps' => 'Pashto',
                              'pt' => 'Portuguese',
                              'qu' => 'Quechua',
                              'rm' => 'Rhaeto-Romance',
                              'rn' => 'Kirundi',
                              'ro' => 'Romanian',
                              'ru' => 'Russian',
                              'rw' => 'Kinyarwanda',
                              'sa' => 'Sanskrit',
                              'sd' => 'Sindhi',
                              'sg' => 'Sangro',
                              'sh' => 'Serbo-Croatian',
                              'si' => 'Singhalese',
                              'sk' => 'Slovak',
                              'sl' => 'Slovenian',
                              'sm' => 'Samoan',
                              'sn' => 'Shona',
                              'so' => 'Somali',
                              'sq' => 'Albanian',
                              'sr' => 'Serbian',
                              'ss' => 'Siswati',
                              'st' => 'Sesotho',
                              'su' => 'Sudanese',
                              'sv' => 'Swedish',
                              'sw' => 'Swahili',
                              'ta' => 'Tamil',
                              'te' => 'Tegulu',
                              'tg' => 'Tajik',
                              'th' => 'Thai',
                              'ti' => 'Tigtinya',
                              'tk' => 'Turkmen',
                              'tl' => 'Tagalog',
                              'tn' => 'Setswana',
                              'to' => 'Tonga',
                              'tr' => 'Turkish',
                              'ts' => 'Tsonga',
                              'tt' => 'Tatar',
                              'tw' => 'Twi',
                              'uk' => 'Ukrainian',
                              'ur' => 'Urdu',
                              'uz' => 'Uzbek',
                              'vi' => 'Vietnamese',
                              'vo' => 'Volapuk',
                              'wo' => 'Wolof',
                              'xh' => 'Xhosa',
                              'yo' => 'Yoruba',
                              'zh' => 'Chinese',
                              'zu' => 'Zulu'
	);
	
	/**
	 * Tablica kodow krajow
	 *
	 * @var array
	 * 
	 */		
	protected $_iso3166Codes = array('af' => 'Afghanistan',
                              'al' => 'Albania',
                              'dz' => 'Algeria',
                              'as' => 'American Samoa',
                              'ad' => 'Andorra',
                              'ao' => 'Angola',
                              'ai' => 'Anguilla',
                              'aq' => 'Antarctica',
                              'ag' => 'Antigua/Barbuda',
                              'ar' => 'Argentina',
                              'am' => 'Armenia',
                              'aw' => 'Aruba',
                              'au' => 'Australia',
                              'at' => 'Austria',
                              'az' => 'Azerbaijan',
                              'bs' => 'Bahamas',
                              'bh' => 'Bahrain',
                              'bd' => 'Bangladesh',
                              'bb' => 'Barbados',
                              'by' => 'Belarus',
                              'be' => 'Belgium',
                              'bz' => 'Belize',
                              'bj' => 'Benin',
                              'bm' => 'Bermuda',
                              'bt' => 'Bhutan',
                              'bo' => 'Bolivia',
                              'ba' => 'Bosnia/Herzegowina',
                              'bw' => 'Botswana',
                              'bv' => 'Bouvet Island',
                              'br' => 'Brazil',
                              'io' => 'British Indian Ocean Territory',
                              'bn' => 'Brunei Darussalam',
                              'bg' => 'Bulgaria',
                              'bf' => 'Burkina Faso',
                              'bi' => 'Burundi',
                              'kh' => 'Cambodia',
                              'cm' => 'Cameroon',
                              'ca' => 'Canada',
                              'cv' => 'Cape verde',
                              'ky' => 'Cayman Islands',
                              'cf' => 'Central African Republic',
                              'td' => 'Chad',
                              'cl' => 'Chile',
                              'cn' => 'China',
                              'cx' => 'Christmas Island',
                              'cc' => 'Cocos -Keeling- Islands',
                              'co' => 'Colombia',
                              'km' => 'Comoros',
                              'cd' => 'Congo, Democratic Republic of/Zaire',
                              'cg' => 'Congo, Peoples Republic of',
                              'ck' => 'Cook Islands',
                              'cr' => 'Costa Rica',
                              'ci' => 'Cote D\'ivoire',
                              'hr' => 'Croatia/Hrvatska',
                              'cu' => 'Cuba',
                              'cy' => 'Cyprus',
                              'cz' => 'Czech Republic',
                              'dk' => 'Denmark',
                              'dj' => 'Djibouti',
                              'dm' => 'Dominica',
                              'do' => 'Dominica Republic',
                              'tl' => 'East Timor',
                              'ec' => 'Ecuador',
                              'eg' => 'Egypt',
                              'sv' => 'El Salvador',
                              'gq' => 'Equatorial Guinea',
                              'er' => 'Eritrea',
                              'ee' => 'Estonia',
                              'et' => 'Ethiopia',
                              'fk' => 'Falkland Islands/Malvinas',
                              'fo' => 'Faroe Islands',
                              'fj' => 'Fiji',
                              'fi' => 'Finland',
                              'fr' => 'France',
                              'fx' => 'France, Metropolitan',
                              'gf' => 'French Guinea',
                              'pf' => 'French Polynesia',
                              'tf' => 'French Southern Territories',
                              'ga' => 'Gabon',
                              'gm' => 'Gambia',
                              'ge' => 'Georgia',
                              'de' => 'Germany',
                              'gh' => 'Ghana',
                              'gi' => 'Gibraltar',
                              'gr' => 'Greece',
                              'gl' => 'Greenland',
                              'gd' => 'Grenada',
                              'gp' => 'Guadeloupe',
                              'gu' => 'Guam',
                              'gt' => 'Guatemala',
                              'gn' => 'Guinea',
                              'gw' => 'Guinea-Bissau',
                              'gy' => 'Guyana',
                              'ht' => 'Haiti',
                              'hm' => 'Heard and Mc Donald Islands',
                              'hn' => 'Honduras',
                              'hk' => 'Hong Kong',
                              'hu' => 'Hungary',
                              'is' => 'Iceland',
                              'in' => 'India',
                              'id' => 'Indonesia',
                              'ir' => 'Iran, Islamic Republic of',
                              'iq' => 'Iraq',
                              'ie' => 'Ireland',
                              'il' => 'Israel',
                              'it' => 'Italy',
                              'jm' => 'Jamaica',
                              'jp' => 'Japan',
                              'jo' => 'Jordan',
                              'kz' => 'Kazakhstan',
                              'ke' => 'Kenya',
                              'ki' => 'Kiribati',
                              'kp' => 'Korea, Democratic Peoples Republic of',
                              'kr' => 'Korea, Republic of',
                              'kw' => 'Kuwait',
                              'kg' => 'Kyrgyzstan',
                              'la' => 'Lao Peples Democratic Republic',
                              'lv' => 'Latvia',
                              'lb' => 'Lebanon',
                              'ls' => 'Lesotho',
                              'lr' => 'Liberia',
                              'ly' => 'Libyan Arab Jamahiriya',
                              'li' => 'Liechtenstein',
                              'lt' => 'Lithuania',
                              'lu' => 'Luxembourg',
                              'mo' => 'Macau',
                              'mk' => 'Macedonia, The Former Yugoslav Republic Of',
                              'mg' => 'Madagascar',
                              'mw' => 'Malawi',
                              'my' => 'Malaysia',
                              'mv' => 'Maldives',
                              'ml' => 'Mali',
                              'mt' => 'Malta',
                              'mh' => 'Marshall Islands',
                              'mq' => 'Martinique',
                              'mr' => 'Mauritania',
                              'mu' => 'Mauritius',
                              'yt' => 'Mayotte',
                              'mx' => 'Mexico',
                              'fm' => 'Micronesia, Federated States Of',
                              'md' => 'Moldova, Republic Of',
                              'mc' => 'Monaco',
                              'mn' => 'Mongolita',
                              'ms' => 'Montserrat',
                              'ma' => 'Morocco',
                              'mz' => 'Mozambique',
                              'mm' => 'Myanmar',
                              'na' => 'Nambia',
                              'nr' => 'Nauru',
                              'np' => 'Nepal',
                              'nl' => 'Netherlands',
                              'an' => 'Netherlands Antilles',
                              'nc' => 'New Caledonia',
                              'nz' => 'New Zealand',
                              'ni' => 'Nicaragua',
                              'ne' => 'Niger',
                              'ng' => 'Nigeria',
                              'nu' => 'Niue',
                              'nf' => 'Norfolk Islands',
                              'mp' => 'Northern Mariana Islands',
                              'no' => 'Norway',
                              'om' => 'Oman',
                              'pk' => 'Pakistan',
                              'pw' => 'Palau',
                              'ps' => 'Palestinian Territory, Occupied',
                              'pa' => 'Panama',
                              'pg' => 'Papua New Guinea',
                              'py' => 'Paraguay',
                              'pe' => 'Peru',
                              'ph' => 'Philippines',
                              'pn' => 'Pitcairn',
                              'pl' => 'Poland',
                              'pt' => 'Portugal',
                              'pr' => 'Puerto Rico',
                              'qa' => 'Qatar',
                              're' => 'Reunion',
                              'ro' => 'Romania',
                              'ru' => 'Russian Federation',
                              'rw' => 'Rwanda',
                              'kn' => 'Saint Kitts/Nevis',
                              'lc' => 'Saint Lucia',
                              'vc' => 'Saint Vincent/Grenadines',
                              'ws' => 'Samoa',
                              'sm' => 'San Marino',
                              'st' => 'Sao Tome/Principe',
                              'sa' => 'Saudi Arabia',
                              'sn' => 'Senegal',
                              'sc' => 'Seychelles',
                              'sl' => 'Sierra Leone',
                              'sg' => 'Singapore',
                              'sk' => 'Slovakia/Slovak Republic',
                              'si' => 'Slovenia',
                              'sb' => 'Solomon Islands',
                              'so' => 'Somalia',
                              'za' => 'South Africa',
                              'gs' => 'South Georgia/South Sandwich Islands',
                              'es' => 'Spain',
                              'lk' => 'Sri Lanka',
                              'sh' => 'Santa Helena',
                              'pm' => 'Santa Pierre/Miquelon',
                              'sd' => 'Sudan',
                              'sr' => 'Suriname',
                              'sj' => 'Svalbard/Jan Mayen Islands',
                              'sz' => 'Swaziland',
                              'se' => 'Sweden',
                              'ch' => 'Switzerland',
                              'sy' => 'Syrian Arab Republic',
                              'tw' => 'Taiwan',
                              'tj' => 'Tajikistan',
                              'tz' => 'Tanzania, United Republic Of',
                              'th' => 'Thailand',
                              'tg' => 'Togo',
                              'tk' => 'Tokelau',
                              'to' => 'Tonga',
                              'tt' => 'Trinidad/Tobago',
                              'tn' => 'Tunisia',
                              'tr' => 'Turkey',
                              'tm' => 'Turkmenistan',
                              'tc' => 'Turks/Caicos Islands',
                              'tv' => 'Tuvalu',
                              'ug' => 'Uganda',
                              'ua' => 'Ukraine',
                              'ae' => 'United Arab Emirates',
                              'gb' => 'United Kingdom',
                              'us' => 'United States',
                              'um' => 'United States Minor Outlying Islands',
                              'uy' => 'Uruguay',
                              'uz' => 'Uzbekistan',
                              'vu' => 'Vanuatu',
                              'va' => 'Vatican City State -Holy See-',
                              've' => 'Venezuela',
                              'vn' => 'Viet Nam',
                              'vg' => 'Virgin Islands, British',
                              'vi' => 'Virgin Islands, U.S.',
                              'wf' => 'Wallis/Futuna Islands',
                              'eh' => 'Western Sahara',
                              'ye' => 'Yemen',
                              'yu' => 'Yougoslavia',
                              'zm' => 'Zambia',
                              'zw' => 'Zimbabwe'
	);			
	
	/**
	 * Instancja obiektu zadania
	 *
	 * @var object
	 * 
	 */		
	protected $_request = null;
	
	/**
	 * Instancja obiektu sesji
	 *
	 * @var object
	 * 
	 */		
	protected $_session = null;
	
	/**
	 * Instancja obiektu translatora
	 *
	 * @var object
	 * 
	 */		
	protected $_i18nTranslator = null;
	
	/**
	 * Domyslna wartosc zmiennej LOCALE
	 *
	 * @var string
	 * 
	 */		
	protected $_defaultLocale = null;
	
	/**
	 * Domyslna wartosc kodu jezyka
	 *
	 * @var string
	 * 
	 */		
	protected $_defaultLanguageCode = null;
	
	/**
	 * Domyslna wartosc kodu kraju
	 *
	 * @var string
	 * 
	 */		
	protected $_defaultCountryCode = null;
	
	/**
	 * Sciezka do katalogu z plikami tlumaczen
	 *
	 * @var string
	 * 
	 */		
	protected $_localeDir = null;
	
	/**
	 * Rozszerzenie plikow z tlumaczeniami
	 *
	 * @var string
	 * 
	 */			
	protected $_localeFileExt = null;
	
	/**
	 * Wartosc zmiennej LOCALE
	 *
	 * @var string
	 * 
	 */			
	protected $_locale = null;
	
	/**
	 * Wartosc kodu jezyka
	 *
	 * @var string
	 * 
	 */			
	protected $_languageCode = null;
	
	/**
	 * Wartosc kodu kraju
	 *
	 * @var string
	 * 
	 */		
	protected $_countryCode = null;
	
	/**
	 * Konstruktor
	 * 
	 * @access private
	 * @param object Obiekt zadania
	 * @param object Obiekt sesji
	 * @param object Obiekt translatora
	 * @param string Domysla wartosc zmiennej LOCALE
	 * @param string Domysla wartosc kodu jezyka
	 * @param string Domyslna wartosc kodu kraju
	 * @param string Sciezka do katalogu z tlumaczeniami
	 * @param string Rozszerzenie dla plikow z tlumaczeniami
	 *  
	 */	
	public function __construct(IRequest $request, ISession $session, I18nTranslator $i18nTranslator, $defaultLocale, $localeDir, $localeFileExt, $defaultLanguageCode=null, $defaultCountryCode=null) {
		$this->_request = $request;
		$this->_session = $session;
		$this->_i18nTranslator = $i18nTranslator;
		
		$this->_defaultLocale = $defaultLocale;
		$this->_defaultCountryCode = $defaultCountryCode;
		$this->_defaultLanguageCode = $defaultLanguageCode;
		$this->_localeDir = $localeDir;
		$this->_localeFileExt = $localeFileExt;
		
		if(empty($this->_defaultLocale) || empty($this->_localeDir) || empty($this->_localeFileExt)) {
			throw new I18nException('Obiekt nie zostal poprawnie zainicjalizowany');
		}
		
		if(empty($this->_defaultLanguageCode) || empty($this->_defaultCountryCode)) {
			if(strstr($this->_defaultLocale, '_')) {
				$tmpArr = explode('_', $this->_defaultLocale);
				$this->_defaultLanguageCode = $tmpArr[0];
				$this->_defaultCountryCode = strtoupper($tmpArr[1]);
			}
			else {
				$this->_defaultLanguageCode = $this->_defaultLocale;
				$this->_defaultCountryCode = strtoupper($this->_defaultLocale);				
			}
		}
		
		$this->fetchLocale();
		$this->addLanguageFile();
	}
	
	/**
	 * Poszukuje wartosc zmiennej LOCALE
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function fetchLocale() {
		$locale = '';
		if(isset($this->_session->locale) && (!empty($this->_session->locale))) {
			$getLocale = $this->_request->get('locale');
			if(!empty($getLocale)) {
				if($getLocale != $this->_session->locale) {
					//@todo: Ustawic cookie z aktualna wartoscia locale
					setcookie('locale', $getLocale, time()+2419200); // 28 dni
					$this->_session->locale = $getLocale;
					$locale = $getLocale;
				}
			}
			else {		
				$locale = $this->_session->locale;
			}
		}
		else {
			$cookieLocale = $this->_request->cookie('locale');
			if(!empty($cookieLocale)) {
				$locale = $cookieLocale;
			}
			else {
				$serverLocale = $this->fetchLocaleFromBrowser();
				//@todo: Ustawic cookie z aktualna wartoscia locale
				setcookie('locale', $serverLocale, time()+2419200); // 28 dni
				$this->_session->locale = $serverLocale;
				$locale = $serverLocale;
			}
		}
		$this->_locale = $locale;
		$tmpArr = explode('_', $this->_locale);
		$this->_languageCode = $tmpArr[0];
		$this->_countryCode = strtoupper($tmpArr[1]);
	}
	
	/**
	 * Uzyskuje LOCALE z przegladarki
	 * 
	 * @access protected
	 * @return string
	 * 
	 */		
	protected function fetchLocaleFromBrowser() {
		$langFromBrowser = $this->_request->server('HTTP_ACCEPT_LANGUAGE');
		//jesli wynik z przegladarki to dostaniemy kilka jezykow
		$languages = array();
		preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $langFromBrowser, $parsedLanguages);
		if(count($parsedLanguages[1])) {
			$languages = array_combine($parsedLanguages[1], $parsedLanguages[4]);//utworzenie listy 'pl'=>0.9
			foreach($languages as $lang => $factorValue) { //jesli nie ma czynnika q to ustawienie waznosci jezyka na 1
				if($factorValue == '') {
					$languages[$lang] = 1;
				}
			}
		}
        arsort($languages, SORT_NUMERIC); //sortowanie po wartosciach
        reset($languages);
        $lg = key($languages); //wybieramy pierwszy jezyk z gory
        $locale = $this->_defaultLanguageCode . '_' . $this->_defaultCountryCode;
        if(strstr($lg, '-')) {
			$locale = str_replace('-', '_', $lg);
		}
		else {
			$locale = $lg . '_' . strtoupper($lg);
		}
		return $locale;
	}
	
	/**
	 * Testuje kod jezyka
	 * 
	 * @access public
	 * @param string Kod jezyka
	 * @return bool
	 * 
	 */	
	public function isValidLanguageCode($languageCode) {
		if(isset($this->_iso639Codes[$languageCode])) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * Testuje kod kraju
	 * 
	 * @access public
	 * @param string Kod kraju
	 * @return bool
	 * 
	 */			
	public function isValidCountryCode($countryCode) {
		if(isset($this->_iso3166Codes[$countryCode])) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * Laduje plik tlumaczenia
	 * 
	 * @access public
	 * @param string Sciezka do pliku. Domyslnie null
	 * @return void
	 * 
	 */			
    public function addLanguageFile($fileName=null) {
		if(empty($fileName)) {
			$fileName = $this->_localeDir . '/' . $this->_languageCode . $this->_localeFileExt;
			$fileNameDefault = $this->_localeDir . '/' . $this->_defaultLanguageCode . $this->_localeFileExt;
			if(file_exists($fileName)) {
				$this->_i18nTranslator->addLanguageFile($fileName);
			}
			else if(file_exists($fileNameDefault)) {
				$this->_i18nTranslator->addLanguageFile($fileNameDefault);
			}
		}
		else {
			$this->_i18nTranslator->addLanguageFile($fileName);
		}		
	}
	
	/**
	 * Ustawia zmienna LOCALE
	 * 
	 * @access public
	 * @param string Wartosc zmiennej LOCALE
	 * @return void
	 * 
	 */		
	public function setLocale($locale) { //mozna zmienic locale podczas dzialania skryptu
        if(strstr($locale, '_')) {
			$this->_locale = $locale;
			$tmpArr = explode('_', $locale);
			$this->_languageCode = $tmpArr[0];
			$this->_countryCode = strtoupper($tmpArr[1]);
		}
		else {
			$this->_locale = $locale . '_' . strtoupper($locale);
			$this->_languageCode = $locale;
			$this->_countryCode = strtoupper($locale);			
		}
		
		$this->addLanguageFile();
	}
	
	/**
	 * Zwraca wartosc zmiennej LOCALE
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getLocale() {
		return $this->_locale;
	}
	
	/**
	 * Zwraca kod jezyka
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getLanguageCode() {
		return $this->_languageCode;
	}
	
	/**
	 * Zwraca kod kraju
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getCountryCode() {
		return $this->_countryCode;
	}	
	
	/**
	 * Zwraca nazwe uzywanego jezyka
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getLanguageName($languageCode='') {
		if(isset($this->_iso639Codes[$languageCode])) {
			return $this->_iso639Codes[$languageCode];
		}
		return null;
	}
	
	/**
	 * Zwraca nazwe kraju
	 * 
	 * @access public
	 * @return string
	 * 
	 */		
	public function getCountryName($countryCode='') {
		if(isset($this->_iso3166Codes[$countryCode])) {
			return $this->_iso3166Codes[$countryCode];
		}
		return null;
	}
	
	/**
	 * Zwraca obiekt translatora
	 * 
	 * @access public
	 * @return object
	 * 
	 */		
	public function getTranslator() {
		return $this->_i18nTranslator;
	}
	
	/**
	 * Zwraca tlumaczenie dla podanego argumentu
	 * 
	 * @access public
	 * @param string Haslo do tlumaczenia
	 * @return string
	 * 
	 */		
	public function translate($word) {
		return $this->_i18nTranslator->translate($word);
	}
	
	/**
	 * Zwraca tlumaczenie dla podanego argumentu
	 * 
	 * @access public
	 * @param string Haslo do tlumaczenia
	 * @return string
	 * 
	 */			
	public function _($word) {
		return $this->_i18nTranslator->translate($word);
	}
	
}	


?>
