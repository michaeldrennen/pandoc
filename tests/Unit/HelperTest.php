<?php

namespace Tests\Unit;

use MichaelDrennen\Pandoc\Helper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase {


    public function testIsRelativePathWhenPassedRelativePath() {
        $relativePath   = './this/is/a/relative/path';
        $isRelativePath = Helper::isRelativePath( $relativePath );
        $this->assertTrue( $isRelativePath );
    }

    public function testIsRelativePathWhenPassedAbsolutePath() {
        $absolutePath   = '/this/is/an/absolute/path';
        $isRelativePath = Helper::isRelativePath( $absolutePath );
        $this->assertFalse( $isRelativePath );
    }

    public function testIsAbsolutePathWhenPassedRelativePath() {
        $relativePath   = './this/is/a/relative/path';
        $isAbsolutePath = Helper::isAbsolutePath( $relativePath );
        $this->assertFalse( $isAbsolutePath );
    }

    public function testIsAbsolutePathWhenPassedAbsolutePath() {
        $absolutePath   = '/this/is/an/absolute/path';
        $isAbsolutePath = Helper::isAbsolutePath( $absolutePath );
        $this->assertTrue( $isAbsolutePath );
    }


}
