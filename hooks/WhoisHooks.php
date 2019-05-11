<?php

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
    if ( $user->isLoggedIn() && filter_var($title->getText(), FILTER_VALIDATE_IP)) {
      $tools['whois'] = $linker->makeKnownLink(
        SpecialPage::getTitleFor( 'Whois' ),
        $sp->msg( 'special-whois-tool' ),
        [],
        [ 'ip' => $title->getText() ]
      );
    }
    
  }
  
}