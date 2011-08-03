<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @version    $Id: Action.php 10664 2008-08-05 10:56:06Z matthew $
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_View_Helper_Abstract.php */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * Helper for parsing html
 *
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_View_Helper_Parse extends Zend_View_Helper_Abstract 
{
    public function parse($text, $type = 'html')
    {
    	$parsed = $text;
		switch ($type){
			case 'html':
				$parsed = $this->html($text);
				break;
		}
		return $parsed;
    }
    
    // {{{ html()

	/**
	 * Parse content text and make real html links
	 *
	 * @param string $text the text you want parsed
	 * @param boolean $full show full text
	 * @return string parsed text
	 */
	public function html($text) {

		// list
		$text = preg_replace( "/\[list\](.+?)\[\/list\]/s", '</p><ul>\\1</ul><p>', $text);
		$text = preg_replace( "/\[list=(.+?)(=.+?,.+?)?\](.+?)\[\/list\]/s", '</p><ol type="\\1">\\3</ol><p>', $text);
		$text = preg_replace( "/\[li\](.+?)\[\/li\]/s", "<li>\\1</li>", $text);
		// strikeout
		$text = preg_replace( "/\[s\](.+?)\[\/s\]/s", "<s>\\1</s>", $text);
		// underline
		$text = preg_replace( "/\[u\](.+?)\[\/u\]/s", "<u>\\1</u>", $text);
		// italic
		$text = preg_replace( "/\[i\](.+?)\[\/i\]/s", "<em>\\1</em>", $text);
		$text = preg_replace( "/\[em\](.+?)\[\/em\]/s", "<em>\\1</em>", $text);
		// bold
		$text = preg_replace( "/\[b\](.+?)\[\/b\]/s", "<strong>\\1</strong>", $text);
		$text = preg_replace( "/\[strong\](.+?)\[\/strong\]/s", "<strong>\\1</strong>", $text);
		// newline
		$text = str_replace("[newline]", '<br clear="all" />', $text);

		// quote
		$text = preg_replace( "/\[quote\](.+?)\[\/quote\]/s", "</p><blockquote><p>\\1</p></blockquote><p>", $text);
		$text = preg_replace( "/\[quote=(http|https):\/\/(.+?)(=.+?,.+?)?\](.+?)\[\/quote\]/s", '</p><blockquote cite="\\1://\\2"><p> \\4 <cite><a href="\\1://\\2"> Kilde </a></cite></p></blockquote><p>', $text);
		$text = preg_replace( "/\[quote=([0-9]+?)(=.+?,.+?)?\](.+?)\[\/quote\]/s", '</p><blockquote cite="#\\1"><p>\\3 <cite><a href="#\\1">#\\1</a></cite></p></blockquote><p>', $text);
		$text = preg_replace( "/\[quote=(.+?)? \(([0-9]+?)\)(=.+?,.+?)?\](.+?)\[\/quote\]/s", '</p><blockquote cite="\\1 (#\\2)"><p>\\4 <cite><a href="#\\2">\\1 (#\\2)</a></cite></p></blockquote><p>', $text);
		//$text = preg_replace_callback( "/\[quote=(.+?)? \(([0-9]+?)\)(=.+?,.+?)?\](.+?)\[\/quote\]/s", array('Z4_Parse', 'quoteCallBack'), $text); TODO: Fix quotes
		$text = preg_replace( "/\[quote=(.+?)(=.+?,.+?)?\](.+?)\[\/quote\]/s", '</p><blockquote><p>\\3<cite>\\1</cite></p></blockquote><p>', $text);
		// link
		$text = preg_replace("/\[url=(\s+)?javascript:/", "[url=", $text);
		$text = preg_replace( "/\[url\](.+?)\[\/url\]/s", '<a href="\\1">\\1</a>', $text);
		//$text = preg_replace( "/\[url=(.+?)(=.+?,.+?)?\](.+?)\[\/url\]/s", '<a href="\\1">\\3</a>', $text);
		$text = preg_replace( "/\[url=(.+?)\](.+?)\[\/url\]/s", '<a href="\\1">\\2</a>', $text);

		// handle urls
    	$text = preg_replace("/(?<!<a href=\")(?<!\")(?<!\">)(\s)((http|https|ftp):\/\/[\w?=&.\/-~#-_]+?)(&quot;)/i",
					" <a href=\"\\2\">\\2</a>\\4",
					$text);
    	$text = preg_replace("/(?<!<a href=\")(?<!\")(?<!\">)(\s)((http|https|ftp):\/\/[\w?=&.\/-~#-_]+)(&lt;)/i",
					" <a href=\"\\2\">\\2</a>\\4",
					$text);
    	$text = preg_replace("/(?<!<a href=\")(?<!\")(?<!\">)(\s)((http|https|ftp):\/\/[\w?=&.\/-~#-_]+)/i",
					" <a href=\"\\2\">\\2</a>",
					$text);
		$text = preg_replace("/^(?<!<a href=\")(?<!\")(?<!\">)((http|https|ftp):\/\/[\w?=&.\/-~#-_]+)/i",
					"<a href=\"\\1\">\\1</a>",
					$text);
	    $text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#;?&//=]+)', '\\1<a href="http://\\2">\\2</a>', $text);
	    $text = preg_replace("/((?<!<a href=\"mailto:)(?<!\">)(?<=(>|\s))[\w_-]+@[\w_.-]+\.[\w_.-]+[\w]+)/","<a href=\"mailto:\\1\">\\1</a>",$text);

	    //$text = eregi_replace("(<.[^>]*>|&aelig;|&oslash;|&aring;|&eacute;|&amp;[^;]*;|[aeiouyæøå])", "", $text);
		$text = nl2br($text);

		return $text;
	}

    // }}}
}