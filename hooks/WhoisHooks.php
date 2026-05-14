<?php
/**
 * Hooks for Whois extension
 *
 * @file
 * @ingroup Extensions
 * @author Petr Kajzar
 * @copyright 1st Faculty of Medicine, Charles University, Czech Republic
 * @license https://creativecommons.org/publicdomain/zero/1.0/ CC0-1.0
 */

use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;

class WhoisHooks {

  /**
   * Add a link to Special:Whois on Special:Contributions/<ipaddress> for
   * logged-in users.
   * @param int $id User ID
   * @param Title $title User page title
   * @param array &$tools User tools
   * @param SpecialPage $sp Special page
   */
  public static function addLink( $id, Title $title, array &$tools, SpecialPage $sp ) {
    
    // get IP address from the Special:Contributions
    $user = $sp->getUser();
    
    // build a link renderer
    $linker = $sp->getLinkRenderer();
    
    // if the user is logged in
    // and the special page contains an IP address,
    // add a WHOIS tool to the toolbar
    if ( $user->isRegistered() && filter_var($title->getText(), FILTER_VALIDATE_IP)) {
      $tools['whois'] = $linker->makeKnownLink(
        SpecialPage::getTitleFor( 'Whois' ),
        $sp->msg( 'special-whois-tool' ),
        [],
        [ 'ip' => $title->getText() ]
      );
    }
    
  }
  
}