<?php
namespace MichaelDrennen\Pandoc;

use MichaelDrennen\Pandoc\Exceptions\UnableToLocatePadocExecutable;

/**
 * Class Pandoc
 *
 * @package MichaelDrennen\Pandoc
 * @link    http://php.net/manual/en/function.exec.php
 */
class Pandoc {

    private $pathToExecutable;

    public function __construct( string $pathToExecutable = null, string $pathToTmpDir = null ) {
        $this->setPathToExecutable( $pathToExecutable );
    }

    private function setPathToExecutable( string $pathToExecutable = null ) {
        if ( is_null( $pathToExecutable ) ):
            exec( 'which pandoc', $output, $returnVar );
            if ( $returnVar === 0 ):
                $this->pathToExecutable = $output[ 0 ];
            else:
                throw new UnableToLocatePadocExecutable( "A path to the executable was not passed into the constructor. Attempted to find the path on my own and failed. The command 'which pandoc' did not return a path." );
            endif;
        else:
            $this->executable = $pathToExecutable;
        endif;


        if ( ! is_executable( $this->executable ) ):
            throw new PandocException( 'Pandoc executable is not executable' );
        endif;
    }
}