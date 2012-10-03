<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * @copyright  Nothing Interactive 2012 <https://www.nothing.ch/>
 * @author     Weyert de Boer <sprog@nothing.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/**
 * Class ContentOEmbed
 *
 * Front end content element "oembed".
 * @copyright  Nothing Interactive 2012
 * @author     Weyert de Boer <sprog@nothing.ch>
 * @package    Controller
 */
class ContentOEmbed extends ContentElement
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
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### oEmbed Content Element ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();

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
        if (preg_match('~^http://(?:www\.)?vimeo\.com/(?:clip:)?(\d+)~', $url, $match))
        {
            $url = 'http://vimeo.com/api/oembed.xml?url=' . $url;
        }

        return $url;
    }

	/**
	 * Generate the content element
	 */
	protected function compile()
	{
        $intWidth = $this->oembed_maxwidth;
        $intHeight = $this->oembed_maxheight;
        $strItemUrl = $this->getItemUrl($this->oembed_url);
        $strServiceUrl = $strItemUrl . '&maxwidth=' . $intWidth . '&maxheight=' . $intHeight . '&format=json';

        $arrServiceResponse = $this->getServiceResponse($strServiceUrl);
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