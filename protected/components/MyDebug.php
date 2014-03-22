<?php

class MyDebug
{

    public static function debug($msg, $level = 'vardump')
    {
        Yii::trace(CVarDumper::dumpAsString($msg), $level);
    }

    public static function pre($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

    public static function ech($text)
    {
        echo $text;
    }

    public static function file($data, $key = 'a+', $add_separator = false, $separator = '*')
    {
        $f = fopen('debug', $key);
        if ($add_separator)
            fwrite($f, $data . $separator);
        else
            fwrite($f, $data);
        fclose($f);
    }

    public static function dump($text)
    {
        var_dump($text);
    }

}

?>
