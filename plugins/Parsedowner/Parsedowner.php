<?php

include ('lib/parsedown/Parsedown.php');
include ('lib/parsedown-extra/ParsedownExtra.php');

class Parsedowner extends DowncastPlugin {

    /**
     * Configure
     *
     * Plugin Configuration
     * Add any code here to set variables and configuration values.
     *
     * @param none
     * @return void
     */
    public function config() {

        /*
         * Parser
         * 
         * $this->PARSER='ParsedownExtra;// for Parsedown Extra http://parsedown.org/
         * $this->PARSER='Parsedown';// for plain Parsedown http://parsedown.org/ https://github.com/erusev/parsedown-extra
         * $this->PARSER='PHPMarkdownClassic';  https://michelf.ca/projects/php-markdown/classic/
         * $this->PARSER='PHPMarkdownExtraClassic'; https://michelf.ca/projects/php-markdown/classic/
         * $this->PARSER='Bootdown';//Downcast Default 
         * $this->PARSER='Disabled';//Returns unparsed text 
         * $this->PARSER='Text';//Returns text with new lines replaced with <br> tags
         */

        $this->PARSER = 'Text';


    }

    /**
     * Inititialize
     *
     * Plugin Initialization
     * Add any code here that you want fired when you create plugin and just after configuration.
     *
     * @param none
     * @return void
     */
    public function init() {
        /*
         * Override parseDown Method
         */

        $override = $this->downcast()->addMethodOverride( 'parseMarkdown', array( $this, 'parseMarkdown' ), __CLASS__ );
        if ( $override !== true )
        {
            echo '<br>Plugin error: cannot override method, plugin ' . $override . ' conflicts';

        }


    }

    /**
     * Parse Markdown
     *
     * Parses Markdown with the Parsedown Library
     *
     * @param none
     * @return void
     */
    public function parseMarkdown( $args ) {
        $text = $args[ 'text' ];
        $method = 'parser' . $this->PARSER;
        if ( is_callable(array($this,$method))) {
        return $this->$method( $text );       
}
     

    }

    private $_parser = null; //contains the parsedown object

    /**
     * Parser Parsedown
     *
     * Returns Parsed Text Using Parsedown
     *
     * @param none
     * @return $text The parsed Text
     */

    public function parserParsedown( $text ) {
        if ( is_null( $this->_parser ) ) {


            $this->_parser = new Parsedown();

}
        $result = $this->_parser->text( $text );
        return $result;
    }

    /**
     * Parser Parsedown Extra
     *
     * Returns Parsed Text Using ParsdownExtra
     *
     * @param none
     * @return $text The parsed Text
     */
    public function parserParsedownExtra( $text ) {
        if ( is_null( $this->_parser ) ) {


            $this->_parser = new ParsedownExtra();

}
        $result = $this->_parser->text( $text );
        return $result;
    }

    /**
     * Parser PHP Markdown Classic
     *
     * Returns Parsed Text Using PHP Markdown Classic
     *
     * @param none
     * @return $text The parsed Text
     */
    public function parserPHPMarkdownClassic( $text ) {
        if ( is_null( $this->_parser ) ) {


            $this->_parser = new stdClass();
            include('lib/php-markdown-classic-1.0.2/markdown.php');
}
        $result = Markdown( $text );
        return $result;
}

    /**
     * Parser PHP Markdown Extra Classic
     *
     * Returns Parsed Text Using PHP Extra Markdown Classic
     *
     * @param none
     * @return $text The parsed Text
     */
    public function parserPHPMarkdownExtraClassic( $text ) {
        if ( is_null( $this->_parser ) ) {


            $this->_parser = new stdClass(); //used only to block another inclusion of markdown 
            include('lib/php-markdown-extra-classic-1.2/markdown.php');
}
        $result = Markdown( $text );
        return $result;
}

    /**
     * Parser Bootdown
     *
     * Returns Parsed Text Using Bootdown
     *
     * @param none
     * @return $text The parsed Text
     */
    public function parserBootdown( $text ) {
        if ( is_null( $this->_parser ) ) {


            $this->_parser = new stdClass(); //used only to block another inclusion of markdown 
            include('lib/bootdown/bootdown.php');
}
        $result = Markdown( $text );
        return $result;
}


    /**
     * Parser Disabled
     *
     * Returns Text (Markdown is not parsed) but with line breaks replaced with br
     *
     * @param none
     * @return $text The parsed Text
     */
    public function parserText( $text ) {

        return nl2br($text);
}

    /**
     * Parser Disabled
     *
     * Returns unparsed text exactly as whats in file
     *
     * @param none
     * @return $text The parsed Text
     */
    public function parserDisabled( $text ) {

        return $text;
}
}

?>
