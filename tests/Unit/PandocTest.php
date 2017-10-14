<?php

namespace Tests\Unit;

use MichaelDrennen\Pandoc\Exceptions\NoInputIsSet;
use MichaelDrennen\Pandoc\Exceptions\PathToPandocNotExecutable;
use MichaelDrennen\Pandoc\Pandoc;
use PHPUnit\Framework\TestCase;

class PandocTest extends TestCase {

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testConstructorWithoutExecutablePath() {
        $pandoc = new Pandoc();
        $this->assertInstanceOf( Pandoc::class, $pandoc );
    }

    public function testConstructorWithBadExecutablePath() {
        $this->expectException( PathToPandocNotExecutable::class );
        new Pandoc( '/this/is/a/fake/path' );
    }


    public function testConvertWithNoInput() {
        $this->expectException( NoInputIsSet::class );
        $pandoc         = new Pandoc();
        $toFileLocation = './tests/Output/bar.docx';
        $pandoc->toFile( $toFileLocation )
               ->convert();
    }


    public function testConvertFileFromTo() {
        $pandoc           = new Pandoc();
        $fromFileLocation = './tests/Input/foo.txt';
        $toFileLocation   = './tests/Output/bar.docx';
        @unlink( $toFileLocation );

        $pandoc->fromFile( $fromFileLocation )
               ->toFile( $toFileLocation )
               ->convert();


        $this->assertFileExists( $toFileLocation );
        unlink( $toFileLocation );

    }

    public function testConvertContentFromTo() {
        $pandoc         = new Pandoc();
        $content        = "<p>Did you ever hear the tragedy of Darth Plagueis The Wise?</p>";
        $toFileLocation = './tests/Output/bar.docx';
        @unlink( $toFileLocation );
        $pandoc->content( $content )
               ->fromType( 'html' )
               ->toType( 'docx' )
               ->toFile( $toFileLocation )
               ->convert();
        $this->assertFileExists( $toFileLocation );
        unlink( $toFileLocation );
    }

    public function testHelp() {
        $pandoc = new Pandoc();
        $help   = $pandoc->help();
        $this->assertNotEmpty( $help );
    }

    public function testVersion() {
        $pandoc = new Pandoc();
        $help   = $pandoc->version();
        $this->assertNotEmpty( $help );
    }

}
