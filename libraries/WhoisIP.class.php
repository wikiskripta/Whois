<?php
/**
 * PHP class obtaining basic information about IP address
 * from the WHOIS registry.
 *
 * @author Petr Kajzar
 * @copyright 1st Faculty of Medicine, Charles University, Czech Republic
 * @license https://creativecommons.org/publicdomain/zero/1.0/ CC0-1.0
 */
class WhoisIP {

  /* variables */
  private $ip;              // IP address
  private $registry;        // registry found in the IANA registry
  private $report;          // complete report from the WHOIS registry
  private $abuse;           // abuse e-mail address
  
  /**
   * Class constructor: obtains all the information about the IP address
   * from the WHOIS registries.
   *
   * @param string $ip IP address
   */
  public function __construct($ip) {
    
    /* validate IP address */
    if (!$this->validateIP($ip)) return false;
    
    /* get "home" WHOIS registry address from the IANA report */
    $this->telnetWhois("whois.iana.org");
    if (!$this->getRegistry($this->report)) return false;
    
    /* obtain report from the "home" WHOIS registry */
    $this->telnetWhois($this->registry);
    
    /* obtain abuse e-mail address from the report */
    $this->getAbuse();

  }
  
  /**
   * IP address validation.
   *
   * @param string $ip IP address to validate
   * @return string IP address or false
   */
  private function validateIP($ip) {
    $this->ip = filter_var($ip, FILTER_VALIDATE_IP);
    return $this->ip;
  }
  
  /**
   * Telnet connection with the WHOIS registry
   *
   * @param string $address URL of the WHOIS registry
   * @param int $port Port of the telnet server (default 43).
   */
  private function telnetWhois($address, $port = 43) {
    
    /* here will be the response from the server */
    $response = "";
    
    /* build a query string */
    $query = $this->ip . "\r\n";
    
    /* make a connection with the WHOIS server */
    $whois = fsockopen($address, $port);
    fwrite($whois, $query);
    while (!feof($whois)) {
      $response .= fgets($whois, 128);
    }
    fclose($whois);
    
    /* store the response string */
    $this->report = $response;
    
  }
  
  /**
   * Get the "home" WHOIS registry URL from the IANA response.
   *
   * @param string $iana IANA report
   * @return string URL of the WHOIS registry or false
   */
  private function getRegistry($iana) {
    
    if (preg_match("/whois\.[\w]*\.net*/i", $iana, $registry)) {
      $this->registry = $registry[0];
      return $this->registry;
    } else {
      return false;
    }
    
  }
  
  /**
   * Get the abuse e-mail address from the WHOIS report.
   */
  private function getAbuse() {
    
    /* find the line containing abuse contact */
    if (preg_match("/% Abuse contact[^\n]*/i", $this->report, $line)) {
      
      /* extract abuse contact */
      if (preg_match("/[^\']*@[^\']*/i", $line[0], $abuse)) {
        $this->abuse = $abuse[0];
      }
    }
    
  }
  
  /**
   * Get the IP address.
   *
   * @return string IP address.
   */
  public function IP() {
    return $this->ip;
  }
  
  /**
   * Get URL of the "home" WHOIS registry
   *
   * @return string WHOIS URL.
   */
  public function registry() {
    return $this->registry;
  }

  /**
   * Get the full WHOIS report.
   *
   * @return string WHOIS report
   */
  public function report() {
    return $this->report;
  }
  
  /**
   * Get the abuse contact address.
   *
   * @return string Contact e-mail address for reporting abuse.
   */
  public function abuse() {
    return $this->abuse;
  }
  
}