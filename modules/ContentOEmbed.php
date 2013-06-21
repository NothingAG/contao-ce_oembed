<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *
 * PHP version 5
 * @copyright  Nothing Interactive 2013 <https://www.nothing.ch/>
 * @author     Weyert de Boer <sprog@nothing.ch>
 * @author     Lukas Walliser <xari@nothing.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * Class ContentOEmbed
 *
 * Front end content element "oembed".
 * @copyright  Nothing Interactive 2012
 * @author     Weyert de Boer <sprog@nothing.ch>
 * @author     Lukas Walliser <xari@nothing.ch>
 */
class ContentOEmbed extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_oembed';


	/**
	 * Return if the image does not exist
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### oEmbed Content Element ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();

		} else {
            // Only load slider for front end
            $GLOBALS['TL_JAVASCRIPT'][] = 'assets/jquery/fitvids/jquery.fitvids.js';
            $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/ce_oembed/assets/init_fitVids.js';
        }

		return parent::generate();
	}

    /**
     * Returns the response of the oEmbed API request call
     *
     * @param $strUrl   the url to request
     * @return mixed
     */
    protected function getServiceResponse($strUrl)
    {
        $strServiceResponse = @file_get_contents($strUrl);

        if ( $strServiceResponse == '' )
        {
            $this->log('Unable to retrieve oEmbed data using url: ' . $strUrl, 'Content element class "ContentOEmbed"', TL_ERROR);
        }

        // try to parse the response as if its json
        $arrServiceResponse = @json_decode($strServiceResponse, true);

        if ( is_null($arrServiceResponse) ) {
            // consider it to be xml
            $arrServiceResponse = json_decode(json_encode((array) simplexml_load_string($strServiceResponse)),1);
        }

        return $arrServiceResponse;
    }

    /**
     * Analyses the given url to detect whether its defined supported content providers, if not it
     * will return the default one.
     *
     * @param $url      the content url
     * @return string
     */
    protected function getItemUrl($url)
    {
        if (preg_match('~^(ht|f)tp(s?)\:\/\/[-.\w]*vimeo.com/[a-zA-Z0-9\-‌​\.\?\,\'\/\\\+&amp;%\$#_]*~', $url, $match)) {

            $url = 'http://vimeo.com/api/oembed.xml?url=' . $url;
        }
        if (preg_match('~^(ht|f)tp(s?)\:\/\/[-.\w]*youtube.com/watch?[a-zA-Z0-9\-‌​\.\?\,\'\/\\\+&amp;%\$#_]*~', $url, $match))
        {
            $url = 'http://www.youtube.com/oembed?url=' . $url;
        }
        return $url;
    }

	/**
	 * Generate the content element
	 */
	protected function compile()
	{
        $strItemUrl = $this->getItemUrl($this->oembed_url);

        $strServiceUrl = $strItemUrl . '&format=json';

        $arrServiceResponse = $this->getServiceResponse($strServiceUrl);

        $arrServiceResponse = str_replace('http:', '', $arrServiceResponse);

        if ( is_array($arrServiceResponse) && array_key_exists('html', $arrServiceResponse) )
        {
            $strEmbedCode = $arrServiceResponse['html'];
            if ( empty($strEmbedCode)) {
                $strEmbedCode = '<!-- Failed: ' . $strItemUrl . '-->';
            }
            $this->Template->embeding_html = $strEmbedCode;
        }
	}
}
?>