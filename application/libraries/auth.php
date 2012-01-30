<?php

/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Martin Jansen <mj@php.net>                                  |
// +----------------------------------------------------------------------+
//
// $Id: Auth.php,v 1.93 2004/08/31 06:48:22 yavo Exp $
//

define('AUTH_IDLED',       -1);
define('AUTH_EXPIRED',     -2);
define('AUTH_WRONG_LOGIN', -3);
define('AUTH_METHOD_NOT_SUPPORTED', -4);
define('AUTH_SECURITY_BREACH', -5);


/**
 * PEAR::Auth
 *
 * The PEAR::Auth class provides methods for creating an
 * authentication system using PHP.
 *
 * @author  Martin Jansen <mj@php.net>
 * @package Auth
 * @version $Revision: 1.93 $
 */
class Auth {

    /**
     * Auth lifetime in seconds
     *
     * If this variable is set to 0, auth never expires
     *
     * @var  integer
     * @see  setExpire(), checkAuth()
     */
    var $expire = 0;

    /**
     * Has the auth session expired?
     *
     * @var   bool
     * @see   checkAuth()
     */
    var $expired = false;

    /**
     * Maximum idletime in seconds
     *
     * The difference to $expire is, that the idletime gets
     * refreshed each time checkAuth() is called. If this
     * variable is set to 0, idletime is never checked.
     *
     * @var integer
     * @see setIdle(), checkAuth()
     */
    var $idle = 0;

    /**
     * Is the maximum idletime over?
     *
     * @var boolean
     * @see checkAuth()
     */
    var $idled = false;

    /**
     * User-defined function that creates the login screen
     *
     * @var string
     */
    var $loginFunction = '';

    /**
     * Should the login form be displayed
     *
     * @var   bool
     * @see   setShowlogin()
     */
    var $showLogin = true;
    
    /**
      * Is Login Allowed from this page
      *
      * @var  bool
      * @see setAllowLogin
      */
    var $allowLogin = true;

    /**
     * Current authentication status
     *
     * @var string
     */
    var $status = '';

    /**
     * Username
     *
     * @var string
     */
    var $username = '';

    /**
     * Password
     *
     * @var string
     */
    var $password = '';

    /**
     * Login callback function name
     *
     * @var string
     * @see setLoginCallback()
     */
    var $loginCallback = '';

    /**
     * Failed Login callback function name
     *
     * @var string
     * @see setLoginFailedCallback()
     */
    var $loginFailedCallback = '';

    /**
     * Logout callback function name
     *
     * @var string
     * @see setLogoutCallback()
     */
    var $logoutCallback = '';

    /**
     * Auth session-array name
     *
     * @var string
     */
    var $_sessionName = '_authsession';
    
    /**
     * Package Version
     *
     * @var string
     */
    var $version = "@version@";

    /**
     * Flag to use advanced security
     * When set extra checks will be made to see if the 
     * user's IP or useragent have changed across requests. 
     * Turned off by default to preserve BC.
     *
     * @var boolean
     */     
    var $advancedsecurity = false;
    
    /**
     * Username key in POST array
     *
     * @var string
     */
    var $_postUsername = 'username';
    
    /**
     * Password key in POST array
     *
     * @var string
     */
    var $_postPassword = 'password';
    
    /**
     * Holds a reference to the session auth variable
     * @var array
     */
    var $session;

    /**
     * Holds a reference to the global server variable
     * @var array
     */
    var $server;

    /**
     * Holds a reference to the global post variable
     * @var array
     */
    var $post;

    /**
     * Holds a reference to the global cookie variable
     * @var array
     */
    var $cookie;

    /**
     * A hash to hold various superglobals as reference
     * @var array
     */
    var $authdata;
    
    /**
      * How many times has checkAuth been called
      * var int
      */
    var $authChecks = 0;
    
    var $CI;
	var $db;
    
    var $options = array();
			
    /**
     * Constructor
     *
     * Set up the storage driver.
     *
     * @param string    Type of the storage driver
     * @param mixed     Additional options for the storage driver
     *                  (example: if you are using DB as the storage
     *                   driver, you have to pass the dsn string here)
     *
     * @param string    Name of the function that creates the login form
     * @param boolean   Should the login form be displayed if neccessary?
     * @return void
     */
    function Auth($showLogin = true)
    {   
    	//Get CI super Object
    	$this->CI = & get_instance();
    	$options = $this->CI->config->item('authConfig');
		
		$this->CI->load->library('ezsql_mysql');
		$this->db = $this->CI->ezsql_mysql;
		
    	 //echo $options;
        $this->applyAuthOptions($options);
        
        $this->_parseOptions($options);
		
        // Start the session suppress error if already started
        @session_start();
       
       // echo "<pre>";print_r($_SESSION);echo "</pre>";
       

        // Make Sure Auth session variable is there
        if( !isset($_SESSION[$this->_sessionName]) && !isset($GLOBALS['HTTP_SESSION_VARS'][$this->_sessionName]) ) {
            //session_register($this->_sessionName);
            $_SESSION[$this->_sessionName] = '';
        }

        // Assign Some globals to internal references, this will replace _importGlobalVariable
        isset($_SESSION) ? $this->session =& $_SESSION[$this->_sessionName] : $this->session =& $GLOBALS['HTTP_SESSION_VARS'][$this->_sessionName] ;
        isset($_SERVER) ? $this->server =& $_SERVER : $this->server =& $GLOBALS['HTTP_SERVER_VARS'];
        isset($_POST) ? $this->post =& $_POST : $this->post =& $GLOBALS['HTTP_POST_VARS'];
        isset($_COOKIE) ? $this->cookie =& $_COOKIE : $this->cookie =& $GLOBALS['HTTP_COOKIE_VARS'];
        //isset($_GET) ? $var = &$_GET : $var = &$GLOBALS['HTTP_GET_VARS'];
       
        

        if (is_bool($showLogin)) {
            $this->showLogin = $showLogin;
        }
    }
    
    /**
      * Set the Auth options 
      *
      * Some options which are Auth specific will be applied
      * the rest will be left for usage by the container
      * @param array An array of Auth options
      * @return array The options which were not applied
      */
    function &applyAuthOptions(&$options) {
        if(is_array($options)){
            if (!empty($options['sessionName'])) {
                $this->_sessionName = $options['sessionName'];
                unset($options['sessionName']);
            }
            if (!empty($options['allowLogin'])) {
                $this->allowLogin = $options['allowLogin'];
                unset($options['allowLogin']);
            }
            if (!empty($options['postUsername'])) {
                $this->_postUsername = $options['postUsername'];
                unset($options['postUsername']);
            }
            if (!empty($options['postPassword'])) {
                $this->_postPassword = $options['postPassword'];
                unset($options['postPassword']);
            }
            if (!empty($options['advancedsecurity'])) {
                $this->advancedsecurity = $options['advancedsecurity'];
                unset($options['advancedsecurity']);
            }
        }
        return($options);
    }
    
     /**
     * Parse options passed to the container class
     *
     * @access private
     * @param  array
     */
    function _parseOptions(&$array)
    {
        foreach ($array as $key => $value) {         
        	$this->options[$key] = $value;         
        }
		
        /* Include additional fields if they exist */
        if(!empty($this->options['db_fields'])){
            if(is_array($this->options['db_fields'])){
                $this->options['db_fields'] = join($this->options['db_fields'], ', ');
            }
            $this->options['db_fields'] = ', '.$this->options['db_fields'];
        }
        //echo "<pre>";print_r($this->options);echo "</pre>";exit;
        
    }

    /**
     * Assign data from login form to internal values
     *
     * This function takes the values for username and password
     * from $HTTP_POST_VARS/$_POST and assigns them to internal variables.
     * If you wish to use another source apart from $HTTP_POST_VARS/$_POST,
     * you have to derive this function.
     *
     * @global $HTTP_POST_VARS, $_POST
     * @see    Auth
     * @return void
     * @access private
     */
    function assignData() {
        if (isset($this->post[$this->_postUsername]) && $this->post[$this->_postUsername] != '') {
            $this->username = (get_magic_quotes_gpc() == 1 ? stripslashes($this->post[$this->_postUsername]) : $this->post[$this->_postUsername]);
        }
        if (isset($this->post[$this->_postPassword]) && $this->post[$this->_postPassword] != '') {
            $this->password = (get_magic_quotes_gpc() == 1 ? stripslashes($this->post[$this->_postPassword]) : $this->post[$this->_postPassword] );
        }
    }

    /**
     * Start new auth session
     *
     * @return void
     * @access public
     */
    function start() {
        $this->assignData();
        
        if (!$this->checkAuth() && $this->allowLogin) {
        	
            $this->login();
        }
    }

    /**
     * Login function
     *
     * @return void
     * @access private
     */
    function login() {
    	
        $login_ok = false;
     
        // Check if using challenge responce
        (isset($this->post['authsecret']) && $this->post['authsecret'] == 1) ? $usingChap = true : $usingChap = false;
  
        /**
         * When the user has already entered a username,
         * we have to validate it.
         */   
        if (!empty($this->username)) {
        	
            if (true === $this->fetchData($this->username, $this->password, $usingChap)) {
                $this->session['challengekey'] = md5($this->username.$this->password);
                $login_ok = true;  
             
            } 
        }
        
        if (!empty($this->username) && $login_ok) {
        	  
            $this->setAuth($this->username);
             
        }
        /**
         * If the login failed or the user entered no username,
         * output the login screen again.
         */
        if (!empty($this->username) && !$login_ok) {
            $this->status = AUTH_WRONG_LOGIN;
        }

            return;
        
    }
    
    
     /**
     * Get user information from database
     *
     * This function uses the given username to fetch
     * the corresponding login data from the database
     * table. If an account that matches the passed username
     * and password is found, the function returns true.
     * Otherwise it returns false.
     *
     * @param   string Username
     * @param   string Password
     * @return  mixed  Error object or boolean
     */
    function fetchData($username, $password)
    {
        
          $sql = "SELECT *".
               " FROM campaign_user".
               " WHERE ".$this->options['usernamecol']." = '".$this->username."'";
		
			
       $res = $this->db->get_row($sql,'ARRAY_A');

		
        if (!$res) {
            $this->activeUser = '';
            return false;
        }

        if ($this->verifyPassword(trim($password, "\r\n"),
                                  trim($res[$this->options['passwordcol']], "\r\n"),
                                  $this->options['cryptType'])) {

            // Store additional field values in the session
            foreach ($res as $key => $value) {
                if ($key == $this->options['passwordcol'] ||
                    $key == $this->options['usernamecol']) {
                    continue;
                }
		//echo "<pre>";print_r($key);echo "</pre>";exit;
                     $this->CI->auth->setAuthData($key, $value);
                
            }

            return true;
        }

        $this->activeUser = $res[$this->options['usernamecol']];
        return false;
    }
    
   /**
     * Crypt and verfiy the entered password
     *
     * @param  string Entered password
     * @param  string Password from the data container (usually this password
     *                is already encrypted.
     * @param  string Type of algorithm with which the password from
     *                the container has been crypted. (md5, crypt etc.)
     *                Defaults to "md5".
     * @return bool   True, if the passwords match
     */
    function verifyPassword($password1, $password2, $cryptType = "md5")
    {
    	
        switch ($cryptType) {
        case "crypt" :
            return (($password2 == "**" . $password1) ||
                    (crypt($password1, $password2) == $password2)
                    );
            break;

        case "none" :
            return ($password1 == $password2);
            break;

        case "md5" :
        	return (md5($password1) == $password2);
            break;

        default :
            if (function_exists($cryptType)) {
                return ($cryptType($password1) == $password2);
            }
            else if (method_exists($this,$cryptType)) { 
                return ($this->$cryptType($password1) == $password2);
            } else {
                return false;
            }
            break;
        }
    }
    

    /**
     * Set the maximum expire time
     *
     * @param  integer time in seconds
     * @param  bool    add time to current expire time or not
     * @return void
     * @access public
     */
    function setExpire($time, $add = false) {
        $add ? $this->expire += $time : $this->expire = $time;
    }

    /**
     * Set the maximum idle time
     *
     * @param  integer time in seconds
     * @param  bool    add time to current maximum idle time or not
     * @return void
     * @access public
     */
    function setIdle($time, $add = false) {
        $add ? $this->idle += $time : $this->idle = $time;
    }

    /**
     * Set name of the session to a customized value.
     *
     * If you are using multiple instances of PEAR::Auth
     * on the same domain, you can change the name of
     * session per application via this function.
     * This will chnage the name of the session variable 
     * auth uses to store it's data in the session
     *
     * @param  string New name for the session
     * @return void
     * @access public
     */
    function setSessionName($name = 'session') {
        $this->_sessionName = '_auth_'.$name;
        isset($_SESSION) ? $this->session =& $_SESSION[$this->_sessionName] : $this->session =& $GLOBALS['HTTP_SESSION_VARS'][$this->_sessionName] ;
    }

    /**
     * Should the login form be displayed if neccessary?
     *
     * @param  bool    show login form or not
     * @return void
     * @access public
     */
    function setShowLogin($showLogin = true) {
        $this->showLogin = $showLogin;
    }

    /**
     * Should the login form be displayed if neccessary?
     *
     * @param  bool    show login form or not
     * @return void
     * @access public
     */
    function setAllowLogin($allowLogin = true) {
        $this->allowLogin = $allowLogin;
    }
    
  

    /**
     * Register additional information that is to be stored
     * in the session.
     *
     * @param  string  Name of the data field
     * @param  mixed   Value of the data field
     * @param  boolean Should existing data be overwritten? (default
     *                 is true)
     * @return void
     * @access public
     */
    function setAuthData($name, $value, $overwrite = true) {
        if (!empty($this->session['data'][$name]) && $overwrite == false) {
            return;
        }
        $this->session['data'][$name] = $value;
    }

    /**
     * Get additional information that is stored in the session.
     *
     * If no value for the first parameter is passed, the method will
     * return all data that is currently stored.
     *
     * @param  string Name of the data field
     * @return mixed  Value of the data field.
     * @access public
     */
    function getAuthData($name = null) {
        if (!isset($this->session['data'])) {
            return null;
        }
        if (!is_null($name)) {
            return $this->session['data'][$name];
        }
        return $this->session['data'];
    }

    /**
     * Register variable in a session telling that the user
     * has logged in successfully
     *
     * @param  string Username
     * @return void
     * @access public
     */
    function setAuth($username) {
        if (!isset($this->session) || !is_array($this->session)) {
            $this->session = array();
        }

        if (!isset($this->session['data'])) {
            $this->session['data'] = array();
        }

        $this->session['sessionip'] = isset($this->server['REMOTE_ADDR']) ? $this->server['REMOTE_ADDR'] : '';
        $this->session['sessionuseragent'] = isset($this->server['HTTP_USER_AGENT']) ? $this->server['HTTP_USER_AGENT'] : '';

        // This should be set by the container to something more safe
        // Like md5(passwd.microtime)
        if(empty($this->session['challengekey'])) {
            $this->session['challengekey'] = md5($username.microtime());
        }

        $this->session['challengecookie'] = md5($this->session['challengekey'].microtime());
        setcookie('authchallenge', $this->session['challengecookie']);

        $this->session['registered'] = true;
        $this->session['username']   = $username;
        $this->session['timestamp']  = time();
        $this->session['idle']       = time();
    }
    
    /**
      * Enables advanced security checks
      *
      * Currently only ip change and useragent change 
      * are detected
      * @todo Add challenge cookies - Create a cookie which changes every time 
      *       and contains some challenge key which the server can verify with a session var
      *       cookie might need to be crypted (user pass)
      * @param bool Enable or disable
      * @return void
      * @access public
      */
    function setAdvancedSecurity($flag=true) {
        $this->advancedsecurity = $flag;
    }

    /**
     * Checks if there is a session with valid auth information.
     *
     * @access private
     * @return boolean  Whether or not the user is authenticated.
     */
    function checkAuth() {
        $this->authChecks++;
        //echo "<pre>test";print_r($this->session);echo "</pre>";
        //exit;
        if (isset($this->session)) {
        	//echo "<pre>";print_r($this->session);echo "</pre>";
             
            // Check if authentication session is expired
            if ($this->expire > 0 &&
                isset($this->session['timestamp']) &&
                ($this->session['timestamp'] + $this->expire) < time()) {
                $this->expired = true;
                $this->status = AUTH_EXPIRED;
              $this->logout();
                return false;
            }

            // Check if maximum idle time is reached
            if ($this->idle > 0 &&
                isset($this->session['idle']) &&
                ($this->session['idle'] + $this->idle) < time()) {
                $this->idled = true;
                $this->status = AUTH_IDLED;
                  
               
                $this->logout();
                return false;
            }

            if (isset($this->session['registered']) &&
                isset($this->session['username']) &&
                $this->session['registered'] == true &&
                $this->session['username'] != '') {
                $this->CI->auth->updateIdle();
				 
                if ($this->advancedsecurity) {
                    // Only Generate the challenge once
                    if($this->authChecks == 1) {
                        $this->session['challengecookieold'] = $this->session['challengecookie'];
                        $this->session['challengecookie'] = md5($this->session['challengekey'].microtime());
                        setcookie('authchallenge', $this->session['challengecookie']);
                    }
                    // Check for ip change
                    if ( isset($this->server['REMOTE_ADDR']) && $this->session['sessionip'] != $this->server['REMOTE_ADDR']) {
                        // Check if the IP of the user has changed, if so we assume a man in the middle attack and log him out
                        $this->expired = true;
                        $this->status = AUTH_SECURITY_BREACH;
                    
                        $this->logout();
                        return false;
                    }
                    // Check for useragent change
                    if ( isset($this->server['HTTP_USER_AGENT']) && $this->session['sessionuseragent'] != $this->server['HTTP_USER_AGENT']) {
                        // Check if the User-Agent of the user has changed, if so we assume a man in the middle attack and log him out
                        $this->expired = true;
                        $this->status = AUTH_SECURITY_BREACH;
                          
                        $this->logout();
                        return false;
                    }
    
                    // Check challenge cookie here, if challengecookieold is not set this is the first time and check is skipped
                    // TODO when user open two pages similtaneuly (open in new window,open in tab) auth breach is caused 
                    // find out a way around that if possible
                    /*if ( isset($this->session['challengecookieold']) && $this->session['challengecookieold'] != $this->cookie['authchallenge']) {
                        $this->expired = true;
                        $this->status = AUTH_SECURITY_BREACH;
                          echo "<pre>test";print_r($this->session);echo "</pre>";echo "gembel";exit;
                        $this->logout();
                        $this->login();
                        return false;
                    }*/
                }

                return true;
            }
        }
        return false;
    }

    /**
     * Statically checks if there is a session with valid auth information.
     *
     * @access private
     * @see checkAuth
     * @return boolean  Whether or not the user is authenticated.
     */
    function staticCheckAuth($options = null) {
        static $staticAuth;
        if(!isset($staticAuth)) {
            $staticAuth = new Auth('null', $options);
        }
        return( $staticAuth->checkAuth() );
    }

    /**
     * Has the user been authenticated?
     *
     * @access public
     * @return bool  True if the user is logged in, otherwise false.
     */
    function getAuth() {
        return $this->checkAuth();
    }

    /**
     * Logout function
     *
     * This function clears any auth tokens in the currently
     * active session and executes the logout callback function,
     * if any
     *
     * @access public
     * @return void
     */
    function logout() {
        $this->username = '';
        $this->password = '';
        
        $this->session = null;
    }

    /**
     * Update the idletime
     *
     * @access private
     * @return void
     */
    function updateIdle()
    {
        $this->session['idle'] = time();
    }

    /**
     * Get the username
     *
     * @return string
     * @access public
     */
    function getUsername()
    {
        if (isset($this->session['username'])) {
            return($this->session['username']);
        }
        return('');
    }

    /**
     * Get the current status
     *
     * @return string
     * @access public
     */
    function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Gets the post varible used for the username
     * 
     * @return string
     * @access public
     */
    function getPostUsernameField() {
        return($this->_postUsername);
    }

    /**
     * Gets the post varible used for the username
     * 
     * @return string
     * @access public
     */
    function getPostPasswordField() {
        return($this->_postPassword);
    }

    /**
     * Returns the time up to the session is valid
     *
     * @access public
     * @return integer
     */
    function sessionValidThru()
    {
        if (!isset($this->session['idle'])) {
            return 0;
        }
        return ($this->session['idle'] + $this->idle);
    }

      
    

}
?>
