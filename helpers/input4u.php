<?php defined('_JEXEC') or die;

/**
 * Class Input4U
 *
 * Since    Engine 2.0
 */
class Input4U
{
    /**
     * Gets
     *
     * @param $name
     * @param null $default
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function get($name, $method='POST', $default=NULL )
    {
        $jinput = JFactory::getApplication()->input;
        return $jinput->{$method}->get($name,$default,'string');
    }

    /**
     * Gets Integer
     *
     * @param string $name
     * @param int $default
     * @param string $method
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function getInt($name, $method='POST', $default=0 )
    {
        $jinput = JFactory::getApplication()->input;
        return $jinput->{$method}->get($name,$default,'int');
    }

    /**
     * Gets Array
     *
     * @param $name
     * @param array $default
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function getArray($name, $method="POST", $default=array() )
    {
        $jinput = JFactory::getApplication()->input;
        return $jinput->{$method}->get($name,$default,'array');
    }

    /**
     * Gets Files
     *
     * @param $name
     * @param array $default
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function getFiles($name, $default=array() )
    {
        $jinput = JFactory::getApplication()->input;
        return $jinput->files->get($name,$default);
    }

    /**
     * Gets Floats
     *
     * @param $name
     * @param string $method
     * @param int $default
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function getFloat($name, $method='POST', $default=0 )
    {
        $jinput = JFactory::getApplication()->input;
        return $jinput->{$method}->get($name,$default,'float');
    }

    /**
     * Gets Boolean value
     *
     * @param $name
     * @param string $method
     * @param int $default
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function getBool($name, $method='POST', $default=0 )
    {
        $jinput = JFactory::getApplication()->input;
        return $jinput->{$method}->get($name,$default,'bool');
    }

    /**
     * Gets only alphabetical characters (a-z,A-Z,0-9) without spaces
     *
     * @param $name
     * @param string $method
     * @param int $default
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function getAlnum($name, $method='POST', $default=0 )
    {
        $jinput = JFactory::getApplication()->input;
        return $jinput->{$method}->get($name,$default,'alnum');
    }

    /**
     * Converts the input into a string and validates it as a path. (e.g. path/to/file.png or path/to/dir)
     *
     * @param $name
     * @param string $method
     * @param int $default
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function getPath($name, $method='POST', $default=0 )
    {
        $jinput = JFactory::getApplication()->input;
        return $jinput->{$method}->get($name,$default,'path');
    }

    /**
     * Gets a sanitised string without tags
     *
     * @param $name
     * @param string $method
     * @param int $default
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function getHtml($name, $method='POST', $default=0 )
    {
        $jinput = JFactory::getApplication()->input;
        return $jinput->{$method}->get($name,$default,'html');
    }

    /**
     * Strips all invalid username characters.
     *
     * @param $name
     * @param string $method
     * @param int $default
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function getUsername($name, $method='POST', $default=0 )
    {
        $jinput = JFactory::getApplication()->input;
        return $jinput->{$method}->get($name,$default,'username');
    }


    /**
     * Gets Configuration info
     *
     * @param $name
     * @param string $default
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function getCfg($name, $default='')
    {
        $app = JFactory::getConfig();
        return $app->get($name,$default);
    }

    public static function set( $name, $value )
    {
        $jinput = JFactory::getApplication()->input;
        return $jinput->set($name,$value);
    }

    public static function Itemid($prefix=true)
    {
        return ($prefix ? '&Itemid=' : '' ) . self::getInt('Itemid', 'REQUEST');
    }
}
