App:       PHP Search and Replace
Version:   1.0.1
Author:    MT Jordan <mtjo62@gmail.com>
Copyright: 2014
License:   zlib/libpng License

**********************************************************************************

PHP Search and Replace

PHP Search and Replace is a PHP 5 class that can search and replace strings
in multiple text based files and subdirectories.

*********************************************************************************

PHP Search and Replace Features:

    * Search and replace text strings on multiple text based files
    * Restrict search to specific file types - ie: php,sql,txt,html,xml
    * Recursively search through subdirectories
    * Search for whole word only
    * Case-sensitive search
    * Search using regular expression as search string
    * Number of files searched and modified returned as an array for reporting
    * Requires no third party libraries
    * Tested on PHP 5.2.17+

Restrictions:

    * You must enter an absolute or relative path for the directory - no URLs
    * Only text based files with a MIME type of text/* can be searched and modified
      unless set_mime() method set to true

Requirements:

    * PHP 5.2+ (5.4+ recommended)

*********************************************************************************

Usage:

See demo.php for a working example

<?php

include 'search_replace.php';

//create search object and enter required vars
$test = new search_replace( 'path/to/dir', 'needle', 'replacement' );

//set options
$test->set_case( true or false ); //true for case-sensitive search - default: false
$test->set_whole_word( true or false ); //true for whole word match - default: false
$test->set_regex( true or false ); //true for search with regular expression - default: false
$test->set_recursive( true or false ); //true to search through subdirectories - default: false
$test->set_extension( 'php,css,xml' ); //comma delimited string of exclusive extensions - default: null
$test->set_mime( true or false ); //true to search all file types - default: false

//perform search/replace and return results
$result = $test->get_results();

echo $result[0]; //number of files searched
echo $result[1]; //number of files modified
echo $result[2]; //modified file path
echo $result[3]; //number of replacement occurrences

?>