<?php
namespace MichaelDrennen\Pandoc;

class Helper {

    /**
     * @param string|null $path
     *
     * @return bool True if the path passed in is a relative path. False if absolute.
     */
    public static function isRelativePath( string $path = null ): bool {
        if ( empty( $path ) ):
            return false;
        endif;

        $firstCharacterFromPath = substr( $path, 0, 1 );

        if ( '/' === $firstCharacterFromPath ):
            return false;
        endif;

        return true;
    }

    /**
     * @param string|null $path
     *
     * @return bool True if the path passed in is an absolute path. False if relative.
     */
    public static function isAbsolutePath( string $path = null ): bool {
        if ( empty( $path ) ):
            return false;
        endif;

        $firstCharacterFromPath = substr( $path, 0, 1 );

        if ( '/' === $firstCharacterFromPath ):
            return true;
        endif;

        return false;
    }
}