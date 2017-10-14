<?php
namespace MichaelDrennen\Pandoc;

use MichaelDrennen\Pandoc\Exceptions\InputFileDoesNotExist;
use MichaelDrennen\Pandoc\Exceptions\InputFileIsNotReadable;
use MichaelDrennen\Pandoc\Exceptions\InvalidFromType;
use MichaelDrennen\Pandoc\Exceptions\InvalidToType;
use MichaelDrennen\Pandoc\Exceptions\NoInputIsSet;
use MichaelDrennen\Pandoc\Exceptions\PathToOutputFileIsNotSet;
use MichaelDrennen\Pandoc\Exceptions\PathToPandocNotExecutable;
use MichaelDrennen\Pandoc\Exceptions\UnableToLocatePandocExecutable;

/**
 * Class Pandoc
 *
 * @package MichaelDrennen\Pandoc
 * @link    http://php.net/manual/en/function.exec.php
 * @todo    This class expects to be working on a *nix OS. Not windows. As a result, a lot of the commands called via
 * @todo    exec() will not work. In the future, I could polish the code to work in both environments.
 */
class Pandoc {

    /**
     * @var string The path to the Pandoc executable file.
     */
    protected $pathToExecutable;
    /**
     * @var string The path to the temp directory.
     */
    protected $pathToTmpDir;

    /**
     * @var string
     */
    protected $pathToInputFile;

    /**
     * @var string
     */
    protected $pathToOutputFile;

    /**
     * @var string The format of the data in the input file. Should be a string from the const inputFormats.
     */
    protected $fromType;

    /**
     * @var string The format of the data in the output file. Should be a string from the const outputFormats.
     */
    protected $toType;


    /**
     * @var array
     * @link https://pandoc.org/MANUAL.html
     *       By default I include the standalone flag. This can be removed with the removeOption() fluent function.
     */
    protected $options = [
        '-s' => null,
    ];

    /**
     * Valid input formats
     */
    const inputFormats = [
        'docbook',
        'html',
        'json',
        'latex',
        'markdown_github',
        'markdown_mmd',
        'markdown_phpextra',
        'markdown_strict',
        'markdown',
        'mediawiki',
        'rst',
        'textile',
        'native',
    ];

    /**
     * Valid output formats.
     */
    const outputFormats = [
        'asciidoc',
        'beamer',
        'context',
        'docbook',
        'docx',
        'dzslides',
        'epub',
        'epub3',
        'fb2',
        'html',
        'html5',
        'json',
        'latex',
        'man',
        'markdown_github',
        'markdown_mmd',
        'markdown_phpextra',
        'markdown_strict',
        'markdown',
        'mediawiki',
        'native',
        'odt',
        'opendocument',
        'org',
        'plain',
        'rst',
        'rtf',
        's5',
        'slideous',
        'slidy',
        'texinfo',
        'textile',
    ];

    /**
     * Pandoc constructor.
     *
     * @param string|null $pathToExecutable
     *
     * @throws \MichaelDrennen\Pandoc\Exceptions\PathToPandocNotExecutable
     * @throws \MichaelDrennen\Pandoc\Exceptions\UnableToLocatePandocExecutable
     */
    public function __construct( string $pathToExecutable = null ) {
        $this->setPathToExecutable( $pathToExecutable );
    }

    /**
     * @param string|null $pathToExecutable
     *
     * @throws \MichaelDrennen\Pandoc\Exceptions\PathToPandocNotExecutable
     * @throws \MichaelDrennen\Pandoc\Exceptions\UnableToLocatePandocExecutable
     */
    protected function setPathToExecutable( string $pathToExecutable = null ) {
        if ( is_null( $pathToExecutable ) ):
            exec( 'which pandoc', $output, $returnVar );
            if ( $returnVar === 0 ):
                $this->pathToExecutable = $output[ 0 ];
            else:
                throw new UnableToLocatePandocExecutable( "A path to the executable was not passed into the constructor. Attempted to find the path on my own and failed. The command 'which pandoc' did not return a path." );
            endif;
        else:
            $this->pathToExecutable = $pathToExecutable;
        endif;


        if ( false === is_executable( $this->pathToExecutable ) ):
            throw new PathToPandocNotExecutable( "This path to Pandoc is not an executable file: " . $this->pathToExecutable );
        endif;
    }


    /**
     * @param string $pathToInputFile
     *
     * @return \MichaelDrennen\Pandoc\Pandoc
     * @throws \MichaelDrennen\Pandoc\Exceptions\InputFileDoesNotExist
     * @throws \MichaelDrennen\Pandoc\Exceptions\InputFileIsNotReadable
     */
    public function fromFile( string $pathToInputFile ): Pandoc {
        if ( false === file_exists( $pathToInputFile ) ):
            if ( Helper::isRelativePath( $pathToInputFile ) ):
                $additionalErrorMessage = " and relative path was rooted at " . getcwd();
            else:
                $additionalErrorMessage = '';
            endif;
            throw new InputFileDoesNotExist( "The input file does not exist: " . $pathToInputFile . $additionalErrorMessage );
        endif;

        if ( false === is_readable( $pathToInputFile ) ):
            throw new InputFileIsNotReadable( "The input file is not readable: " . $pathToInputFile );
        endif;

        $this->pathToInputFile = $pathToInputFile;

        return $this;
    }

    /**
     * Fluent setter for the converted output file.
     *
     * @param string $pathToOutputFile
     *
     * @return \MichaelDrennen\Pandoc\Pandoc
     */
    public function toFile( string $pathToOutputFile ): Pandoc {
        $this->pathToOutputFile = $pathToOutputFile;
        $this->setOption( '--output', $this->pathToOutputFile );

        return $this;
    }

    /**
     * @param string $fromType
     *
     * @return $this
     * @throws \MichaelDrennen\Pandoc\Exceptions\InvalidFromType
     */
    public function fromType( string $fromType ) {
        if ( false === in_array( $fromType, self::inputFormats ) ):
            throw new InvalidFromType( "The file type you passed in is not one of the supported formats in const inputFormats: " . $fromType );
        endif;

        $this->fromType = $fromType;

        return $this;
    }

    /**
     * @param string $toType
     *
     * @return $this
     * @throws \MichaelDrennen\Pandoc\Exceptions\InvalidToType
     */
    public function toType( string $toType ) {
        if ( false === in_array( $toType, self::outputFormats ) ):
            throw new InvalidToType( "The file type you passed in is not one of the supported formats in const outputFormats: " . $toType );
        endif;
        $this->toType = $toType;

        return $this;
    }

    /**
     * @param string $content
     *
     * @return $this
     * @throws \MichaelDrennen\Pandoc\Exceptions\InputFileDoesNotExist
     * @throws \MichaelDrennen\Pandoc\Exceptions\InputFileIsNotReadable
     */
    public function content( string $content ) {
        $tempFromFilePath = tempnam( sys_get_temp_dir(), 'pandocTemp' );
        file_put_contents( $tempFromFilePath, $content );
        $this->fromFile( $tempFromFilePath );

        return $this;
    }

    /**
     * @return string The formatted string of options the user wants to send to the Pandoc command line tool.
     */
    protected function getOptionString(): string {
        $optionParts = [];
        foreach ( $this->options as $key => $value ):
            if ( is_null( $value ) ):
                $optionParts[] = $key;
            else:
                $optionParts[] = $key . '=' . $value;
            endif;
        endforeach;
        $optionString = implode( ' ', $optionParts );

        return $optionString;
    }

    /**
     * The Pandoc command line tool has a bunch of options. You can set them here.
     *
     * @param string      $key
     * @param string|null $value
     *
     * @return $this
     * @link https://pandoc.org/MANUAL.html
     */
    public function setOption( string $key, string $value = null ) {
        $this->options[ $key ] = $value;

        return $this;
    }

    /**
     * @param $key
     *
     * @return $this
     * @link https://pandoc.org/MANUAL.html
     */
    public function removeOption( $key ) {
        unset( $this->options[ $key ] );

        return $this;
    }

    /**
     * @return string
     * @throws \MichaelDrennen\Pandoc\Exceptions\NoInputIsSet
     * @throws \MichaelDrennen\Pandoc\Exceptions\PathToOutputFileIsNotSet
     */
    protected function getCommandString() {

        if ( is_null( $this->pathToOutputFile ) ):
            throw new PathToOutputFileIsNotSet( "You must set the path to the output file with the toFile() function." );
        endif;

        if ( is_null( $this->pathToInputFile ) ):
            throw new NoInputIsSet( "You need to specify either string content or an input file with content() or fromFile() respectively." );
        endif;

        $optionString        = $this->getOptionString();
        $commandString       = $this->pathToExecutable . " " . $optionString . ' ' . $this->pathToInputFile;
        $escapedShellCommand = escapeshellcmd( $commandString );

        return $escapedShellCommand;
    }

    /**
     * @return array
     * @throws \MichaelDrennen\Pandoc\Exceptions\NoInputIsSet
     * @throws \MichaelDrennen\Pandoc\Exceptions\PathToOutputFileIsNotSet
     */
    public function convert(): array {
        $escapedShellCommand = $this->getCommandString();
        exec( $escapedShellCommand, $output, $returnVar );

        return $output;
    }


    // Utility functions.

    /**
     * @param string $option
     *
     * @return array
     */
    protected function callPandocSimple( string $option ): array {
        $command             = $this->pathToExecutable . ' ' . $option;
        $escapedShellCommand = escapeshellcmd( $command );
        exec( $escapedShellCommand, $output, $returnVar );

        return $output;
    }

    /**
     * @return array
     */
    public function listInputFormats(): array {
        return $this->callPandocSimple( '--list-input-formats' );
    }

    /**
     * @return array
     */
    public function listOutputFormats(): array {
        return $this->callPandocSimple( '--list-output-formats' );
    }

    /**
     * List supported Markdown extensions, one per line, followed by a + or - indicating whether it is enabled by
     * default in pandoc's Markdown.
     *
     * @return array
     */
    public function listExtensions(): array {
        return $this->callPandocSimple( '--list-extensions' );
    }

    /**
     * @return array
     */
    public function version(): array {
        return $this->callPandocSimple( '--version' );
    }

    /**
     * @return array
     */
    public function help(): array {
        return $this->callPandocSimple( '--help' );
    }


}