<?php

class Redireccion {

    public static function redirigir($url) {
        header('Location: ' . $url, true, 301);
        exit();
    }

    public static function redirigir1($url) {
        echo '<script type="text/javascript">window.location.assign("' . $url . '");</script>';
    }

}

?>
