<?php defined('_JEXEC') or die;

/**
 * Class Price4U
 *
 * @version     1.0
 * @since       03-01-2017
 */
class Price4U
{
    /**
     * Obtains PriceData, adds euro sign
     *
     * @param $price
     * @param string $symbol
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function showPrice($price, $symbol="€" )
    {
        $html = '';
        
        if( $symbol ) {
            $html .= $symbol." ";
        }

        $html .= number_format( $price, 2, ",", "." );
        $aHtml = explode(",",$html);
        if($aHtml[1] == 0) {
            $aHtml[1] = '-';
        }
        return $aHtml[0].','.$aHtml[1];
    }
}