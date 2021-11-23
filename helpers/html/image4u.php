<?php defined('_JEXEC') or die;

use PHPImageWorkshop\ImageWorkshop; // Use the namespace of ImageWorkshop
jimport('PHPImageWorkshop.ImageWorkshop');

class JHtmlImage
{
    public static function getAll($id, $type, $width, $height, $scale=1, $limit = 20)
    {
        $type = (string) $type;

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $int = 1;

        $query->select('url');
        $query->from('`#__eng_media`');
        $query->order('ordering ASC LIMIT '. $limit);
        $query->where("`object_id` = " . (int) $id );
        $query->where("`table` = '" . $type . "'");

        $db->setQuery($query);

        $items = $db->loadObjectList();
        $i = 0;
        foreach($items as $item)
        {
            $i++;
            if(!file_exists( JPATH_ROOT . '/' . $item->url ) || strpos($item->url, 'media') === false)
            {
                $item->image = "<img src='http://placehold.it/".$width."x/".$height."&text=Geen%20afbeeldingen' alt='Geen afbeeldingen' />";
            }
            else
            {
                $url = self::cache( $item->url, 'cache/com_engine/'.$type.'/'.$id.'/', $type, $width, $height, $crop );
                $item->image = "<img href='#' src='" . $url . "' alt='' />";
            }
        }

        return $items;
    }

    public static function cache( $url, $folder, $type, $width, $height, $scale=1 )
    {
        ini_set('memory_limit', '500M');

        $folder = str_replace('cache/', 'media/', $folder);

        $cacheFolder = $folder;
        $path_parts = pathinfo( $url );

        $cacheFilename = $path_parts['filename']."_".$width."x".$height."." . $path_parts['extension'];

        if((!file_exists( JPATH_ROOT .'/'. $cacheFolder . $cacheFilename ) || Input4U::get('cacheeverything', 'REQUEST') == true) && file_exists(JPATH_ROOT .'/'. $url))
        {
            $originalSize = getimagesize(JPATH_ROOT . '/' . $url);

            try{

                $thumbnail = ImageWorkshop::initFromPath( JPATH_ROOT .'/'. $url);
            } catch(Exception $ex){
                return '';
            }

            $originalWidth = $originalSize[0];
            $originalHeight = $originalSize[1];
            
            if($originalWidth > $width){
                if($originalWidth == $originalHeight){
                    $height = $width;

                    $thumbnail->resizeInPixel( $width, $height, false);
                } else {
                    if($originalWidth > $originalHeight){
                        $thumbnail->resizeByLargestSideInPixel( $width, true);
                    } else {
                        $thumbnail->resizeByNarrowSideInPixel( $width, true);
                    }
                }
            }

            $newSizes = self::getNewSize($originalWidth, $originalHeight, $width, $height);

            $thumbnail->resizeInPixel( $newSizes[0], $newSizes[1], false);

            $derp = $thumbnail->save( JPATH_ROOT . '/' . $cacheFolder, $cacheFilename );
        }

        return JURI::root(false)  . $cacheFolder . $cacheFilename;
    }

    public static function getNewSize($originalWidth, $originalHeight, $toWidth, $toHeight)
    {
        if($originalWidth == $originalHeight){
            if($toWidth > $toHeight || ($toWidth == $toHeight)){
                $newWidth = $toHeight;
                $newHeight = $toHeight;
            } else {
                $newWidth = $toWidth;
                $newHeight = $toWidth;
            }
        } elseif($originalWidth > $originalHeight){
            $newHeight = (($originalHeight * $toWidth) / $originalWidth);
            $newWidth = $toWidth;

            if($newHeight > $toHeight){
                $newHeight = $toHeight;
                $newWidth = (($originalWidth * $toHeight) / $originalHeight);
            }
        } else {
            $newWidth = (($originalWidth * $toHeight) / $originalHeight);

            $newHeight = $toHeight;

            if($newWidth > $toWidth){
                $newWidth = $toWidth;
                $newHeight = (($originalHeight * $toWidth) / $originalWidth);
            }
        }

        return array($newWidth, $newHeight);
    }
}