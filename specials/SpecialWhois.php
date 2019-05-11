<?php
/**
 * SpecialPage for Whois extension
 *
 * @file
 * @ingroup Extensions
 * @author Petr Kajzar
 * @license https://creativecommons.org/publicdomain/zero/1.0/ CC0-1.0
 */
class SpecialWhois extends SpecialPage {
  
	public function __construct() {
		parent::__construct( 'whois', 'user', $listed = false );
	}

	/**
	 * Show the special page to the user
	 *
	 * @param string $ipaddress The IP address in the "subpage" argument.
	 */
	public function execute( $ipaddress ) {
	  
	  // check logged in users
	  if(!$this->getUser()->isLoggedIn()) {
	    $this->displayRestrictionError();
	  }
	  
	  // initial code: get headers and basic page setup
	  $request = $this->getRequest();
		$out = $this->getOutput();
		$this->setHeaders();
		$out->setPageTitle( $this->msg( 'special-whois-title' ) );
		$this->addHelpLink( 'Extension:Whois' );
		$out->addWikiMsg( 'special-whois-intro' );
		$ipaddress = $ipaddress ?? $request->getText( 'ip' );
		
		// validate IP
    if (!filter_var($ipaddress, FILTER_VALIDATE_IP)) {
      $out->showErrorPage( 'error', 'special-whois-error' );
      return;
		};
    
    // if everything's OK, print a summary report
    $text = $this->buildReport( $ipaddress );
    $out->addWikiText( $text );
    
	}
	
	/**
	 * Build a WHOIS report
	 *
	 * @param string $ipaddress IP address.
	 * @return string Wikitext to display
	 */
	private function buildReport( $ipaddress ) {
	  
	  $whois = new WhoisIP( $ipaddress );
    
    // heading
    $text = "== {$this->msg( 'special-whois-info' )} {$whois->IP()} ==\n";
    
    // table
    $text .= "{| class='wikitable'\n";

    // print IP address
    $text .= $this->buildRow( 'special-whois-ip', $whois->IP() );
    
    // print abuse contact
    $text .= $this->buildRow( 'special-whois-email', $whois->abuse() );
    
    // print whois registry
    $text .= $this->buildRow( 'special-whois-registry', $whois->registry() );
    
    // end of table
    $text .= "|}\n";
    
    // print full report
    if ($whois->report()) {
      $text .= "=== {$this->msg( 'special-whois-report' )} {$whois->registry()} ===\n";
      $text .= "<pre>{$whois->report()}</pre>\n";
    }
    
    // that's all
    return $text;

	}
	
	/**
	 * Build a wikitable row with label and data
	 *
	 * @param string $name Name of a localization message for a row label
	 * @param string $data Data from the WHOIS registry to display in a table row
	 * @return string Row of a wikitable with the data
	 */
	private function buildRow( $name, $data ) {
	  
	  if ($data) {
      return "|-\n| '''{$this->msg( $name )}:''' || {$data}\n";
    }
    
	}

}
