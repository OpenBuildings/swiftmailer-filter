<?php

namespace Openbuildings\Swiftmailer;

/**
 * @package    Openbuildings\Swiftmailer
 * @author     Ivan Kerin
 * @copyright  (c) 2013 OpenBuildings Ltd.
 * @license    http://spdx.org/licenses/BSD-3-Clause
 */
class FilterPlugin implements \Swift_Events_SendListener
{
  /**
   * Check if an email matches a given other email or domain
   * @param  string $email 
   * @param  string $match email or domain
   * @return boolean        
   */
  public static function emailMatches($email, $match)
  {
    if ( ! filter_var($email, FILTER_VALIDATE_EMAIL))
      throw new \Exception("Cannot match with '{$match}': '{$email}' is not a valid email");
      
    if (strpos($match, '@') === FALSE)
    {
      list($email_name, $email_domain) = explode('@', $email);

      return $email_domain === $match;
    }
    else
    {
      return $email === $match;
    }
  }

  /**
   * Check if a given email matches an array of emails or domains
   * @param  string $email       
   * @param  array  $match_array 
   * @return boolean              
   */
  public static function emailMatchesArray($email, array $match_array)
  {
    foreach ($match_array as $match)
    {
      if (FilterPlugin::emailMatches($email, $match))
        return TRUE;
    }

    return FALSE;
  }

  /**
   * Filter a swiftmailer email array, e.g. [email => name] with a whitelist and blacklist array (email or domains)
   * First the whitelist is applied, then the blacklist
   * 
   * @param  array  $whitelist array of emails or domains
   * @param  array  $blacklist array of emails or domains
   * @param  array  $array     Swiftmailer array of emails
   * @return array            filtered array
   */
  public static function filterEmailArray(array $whitelist, array $blacklist, array $array)
  {
    if ($whitelist)
    {
      foreach ($array as $email => $name) 
      {
        if ( ! FilterPlugin::emailMatchesArray($email, $whitelist)) 
        {
          unset($array[$email]);
        }
      }
    }

    if ($blacklist) 
    {
      foreach ($array as $email => $name) 
      {
        if (FilterPlugin::emailMatchesArray($email, $blacklist)) 
        {
          unset($array[$email]);
        }
      }
    }
    return $array;
  }

  protected $_whitelist;
  protected $_blacklist;

  function __construct($whitelist = NULL, $blacklist = NULL)
  {
    $this->setWhitelist($whitelist);
    $this->setBlacklist($blacklist);
  }

  /**
   * Setter, array or string
   * @param array|string $whitelist 
   */
  public function setWhitelist($whitelist)
  {
    $this->_whitelist = (array) $whitelist;

    return $this;
  }

  /**
   * Getter
   * @return array 
   */
  public function getWhitelist()
  {
    return $this->_whitelist;
  }

  /**
   * Setter, array or string
   * @param array|string $blacklist 
   */
  public function setBlacklist($blacklist)
  {
    $this->_blacklist = (array) $blacklist;

    return $this;
  }

  /**
   * Getter
   * @return array 
   */
  public function getBlacklist()
  {
    return $this->_blacklist;
  }

  /**
   * Apply whitelist and blacklist to "to", "cc" and "bcc"
   *
   * @param Swift_Events_SendEvent $evt
   */
  public function beforeSendPerformed(\Swift_Events_SendEvent $evt)
  {
    $message = $evt->getMessage();

    $message->setTo(FilterPlugin::filterEmailArray($this->getWhitelist(), $this->getBlacklist(), $message->getTo()));

    $message->setCc(FilterPlugin::filterEmailArray($this->getWhitelist(), $this->getBlacklist(), $message->getCc()));

    $message->setBcc(FilterPlugin::filterEmailArray($this->getWhitelist(), $this->getBlacklist(), $message->getBcc()));
  }

  /**
   * Do nothing
   *
   * @param Swift_Events_SendEvent $evt
   */
  public function sendPerformed(\Swift_Events_SendEvent $evt)
  {
    // Do Nothing
  }
}
