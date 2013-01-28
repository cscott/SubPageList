<?php

/**
 * Initialization file for the SubPageList extension.
 * 
 * Documentation:	 		https://www.mediawiki.org/wiki/Extension:SubPageList
 * Support					https://www.mediawiki.org/wiki/Extension_talk:SubPageList
 * Source code:             https://gerrit.wikimedia.org/r/gitweb?p=mediawiki/extensions/SubPageList.git
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SubPageList
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * This documentation group collects source code files belonging to SubPageList.
 *
 * @defgroup SPL SubPageList
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

// Include the Validator extension if that hasn't been done yet, since it's required for SubPageList to work.
if ( !defined( 'Validator_VERSION' ) ) {
	@include_once( dirname( __FILE__ ) . '/../Validator/Validator.php' );
}

// Only initialize the extension when all dependencies are present.
if ( !defined( 'ParamProcessor_VERSION' ) ) {
	die( '<b>Error:</b> You need to have <a href="http://www.mediawiki.org/wiki/Extension:Validator">Validator (ParamProcessor)</a> 1.0 or later installed in order to use <a href="http://www.mediawiki.org/wiki/Extension:SubPageList">SubPageList</a>.<br />' );
}


define( 'SPL_VERSION', '1.0 alpha' );


$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'SubPageList',
	'version' => SPL_VERSION,
	'author' => array(
		'[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]',
		'Van de Bugger. Based on [https://www.mediawiki.org/wiki/Extension:SubPageList3 SubPageList3].',
	),
	'url' => 'https://www.mediawiki.org/wiki/Extension:SubPageList',
	'descriptionmsg' => 'spl-desc'
);


$wgExtensionMessagesFiles['SubPageList'] = __DIR__ . '/SubPageList.i18n.php';
$wgExtensionMessagesFiles['SubPageListMagic'] = __DIR__ . '/SubPageList.i18n.magic.php';


$wgAutoloadClasses['SubPageBase'] = __DIR__ . '/SubPageBase.class.php';
$wgAutoloadClasses['SubPageList'] = __DIR__ . '/SubPageList.class.php';
$wgAutoloadClasses['SubPageCount'] = __DIR__ . '/SubPageCount.class.php';

$wgAutoloadClasses['SubPageList\DBConnectionProvider'] = __DIR__ . '/includes/DBConnectionProvider.php';
$wgAutoloadClasses['SubPageList\LazyDBConnectionProvider'] = __DIR__ . '/includes/LazyDBConnectionProvider.php';
$wgAutoloadClasses['SubPageList\SimpleSubPageFinder'] = __DIR__ . '/includes/SimpleSubPageFinder.php';
$wgAutoloadClasses['SubPageList\SubPageFinder'] = __DIR__ . '/includes/SubPageFinder.php';


$wgHooks['ParserFirstCallInit'][] = 'SubPageList::staticInit';
$wgHooks['ParserFirstCallInit'][] = 'SubPageCount::staticInit';

/**
 * Occurs after a new article has been created.
 * https://www.mediawiki.org/wiki/Manual:Hooks/ArticleInsertComplete
 *
 * @param WikiPage $article
 * @param User $user
 * @param $text
 * @param $summary
 * @param $minoredit
 * @param $watchthis
 * @param $sectionanchor
 * @param $flags
 * @param Revision $revision
 */
$wgHooks['ArticleInsertComplete'][] = function( WikiPage $article, User &$user, $text, $summary, $minoredit,
												$watchthis, $sectionanchor, &$flags, Revision $revision ) {

	if ( $GLOBALS['egSPLAutorefresh'] ) {
		// TODO $article->getTitle()
	}
};

/**
 * Occurs after the delete article request has been processed.
 * https://www.mediawiki.org/wiki/Manual:Hooks/ArticleDeleteComplete
 *
 * @param $article
 * @param User $user
 * @param $reason
 * @param $id
 */
$wgHooks['ArticleDeleteComplete'][] = function( &$article, User &$user, $reason, $id ) {
	// TODO $article->getTitle()
};

/**
 * Occurs whenever a request to move an article is completed.
 * https://www.mediawiki.org/wiki/Manual:Hooks/TitleMoveComplete
 *
 * @param Title $title
 * @param Title $newtitle
 * @param User $user
 * @param $oldid
 * @param $newid
 */
$wgHooks['TitleMoveComplete'][] = function( Title &$title, Title &$newtitle, User &$user, $oldid, $newid ) {

	// TODO
//	self::invalidateBasePages( $title );
//	self::invalidateBasePages( $newtitle );

};

/**
 * Hook to add PHPUnit test cases.
 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList
 *
 * @since 1.0
 *
 * @param array $files
 *
 * @return boolean
 */
$wgHooks['UnitTestsList'][]	= function( array &$files ) {
	// @codeCoverageIgnoreStart
	$testFiles = array(
		'LazyDBConnectionProvider',
		'SimpleSubPageFinder',
	);

	foreach ( $testFiles as $file ) {
		$files[] = __DIR__ . '/tests/' . $file . 'Test.php';
	}

	return true;
	// @codeCoverageIgnoreEnd
};


require_once 'SubPageList.settings.php';
