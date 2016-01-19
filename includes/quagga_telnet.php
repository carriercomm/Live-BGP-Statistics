<?php
/*-----------------------------------------------------------------------------
* Live BGP Statistics                                                         *
*                                                                             *
* Main Author: Vaggelis Koutroumpas vaggelis@koutroumpas.gr                   *
* (c)2008-2016 for AWMN                                                       *
* Credits: see CREDITS file                                                   *
*                                                                             *
* This program is free software: you can redistribute it and/or modify        *
* it under the terms of the GNU General Public License as published by        * 
* the Free Software Foundation, either version 3 of the License, or           *
* (at your option) any later version.                                         *
*                                                                             *
* This program is distributed in the hope that it will be useful,             *
* but WITHOUT ANY WARRANTY; without even the implied warranty of              *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the                *
* GNU General Public License for more details.                                *
*                                                                             *
* You should have received a copy of the GNU General Public License           *
* along with this program. If not, see <http://www.gnu.org/licenses/>.        *
*                                                                             *
*-----------------------------------------------------------------------------*/

// CLASS BORROWED AND MODIFIED FROM Ray Soucy's 'Cisco for PHP' Class
// ORIGINAL LICENSE AND CREDITS >

/**
 * Cisco for PHP
 *
 * A PHP class to connect to Cisco IOS devices over Telnet.
 *
 * Copyright (C) 2009 Ray Patrick Soucy
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   Cisco
 * @author    Ray Soucy <rps@soucy.org>
 * @version   1.0.7
 * @copyright 2009 Ray Patrick Soucy 
 * @link      http://www.soucy.org/
 * @license   GNU General Public License version 3 or later
 * @since     File available since Release 1.0.5
 */

/**
 * Cisco
 * @package    Cisco
 * @version    Release: @package_version@
 * @deprecated Class deprecated in Release 2.0.0
 */
 
 

class Quagga 
{

    private $_hostname;
    private $_password;
    private $_port;
    private $_connection;
    private $_data;
    private $_timeout;
    private $_prompt;
    private $_promptfull;

    /**
     * Class Constructor
     * @param  string  $hostname Hostname or IP address of the device
     * @param  string  $password Password used to connect
     * @param  string  $port Port to connect to
     * @param  integer $timeout  Connetion timeout (seconds)
     * @return object  Quagga object
     */
    public function __construct($hostname, $password, $port, $timeout = 20) 
    {
        $this->_hostname = $hostname;
        $this->_password = $password;
        $this->_port = $port;
        $this->_timeout = $timeout;
    } // __construct

    /**
     * Establish a connection to the device
     */
    public function connect() 
    {
        $this->_connection = fsockopen($this->_hostname, $this->_port, $errno, $errstr, $this->_timeout);
        if ($this->_connection === false) {
            echo "Error: Connection Failed for $this->_hostname\n";
            return false;
        } // if   
        stream_set_timeout($this->_connection, $this->_timeout);
        
		$this->_readTo(':');
		$this->_send($this->_password);
        $this->_prompt = '>';
        $this->_readTo($this->_prompt);
	    if (strpos($this->_data, $this->_prompt) === false) {
	        fclose($this->_connection);
	        echo "Error: Authentication Failed for $this->_hostname\n";
	        return false;
	    } // if
        //Store full prompt for later use when '>' prompt conflicts with output.
        $this->_promptfull = $this->_data;
        
        return true;
    } // connect

    /**
     * Close an active connection
     */
    public function close() 
    {
        $this->_send('quit');
        if (fclose($this->_connection)){
			return true;
        }else{
			return false;
        }
    } // close

    
    private function _fwrite_stream($fp, $string) {
	    for ($written = 0; $written < strlen($string); $written += $fwrite) {
	        $fwrite = fwrite($fp, substr($string, $written));
	        if ($fwrite === false) {
	            return $written;
	        }
	    }
	    return $written;
	}
    
    /**
     * Issue a command to the device
     */
    private function _send($command) 
    {
        $this->_fwrite_stream($this->_connection, $command . "\n");
    } // _send

    /**
     * Read from socket until $char
     * @param string $char Single character (only the first character of the string is read)
     */
    private function _readTo($char) 
    {        
    	//Set proper prompt
    	if (strlen($char) > 1){
			$big_prompt = true;
			$char = explode("\n", $char);
			$char = trim($char[1]); 
    	}else{
			$big_prompt = false;			
    	}
    	
    	// Reset $_data
        $this->_data = "";
        while (($c = fgetc($this->_connection)) !== false) {
        	$this->_data .= $c;
            if ($big_prompt == true){
				if (strstr($this->_data, $char)) break; 	
            }else{
            	if ($c == $char[0]) break;
            }
            
            if ($c == '-') { 
                // Continue at --More-- prompt
                if (substr($this->_data, -8) == '--More--') $this->_fwrite_stream($this->_connection, ' ');
            } // if
        } // while
        // Remove --More-- and backspace
        $this->_data = str_replace('--More--', "", $this->_data);
        $this->_data = str_replace(chr(8), "", $this->_data);
        // Set $_data as false if previous command failed.
        if (strpos($this->_data, '% Invalid input detected') !== false) $this->_data = false;
    } // _readTo


    
    public function bgpd($cmd){
    	$this->_send("terminal length 0");
		$this->_readTo($this->_prompt);
		$this->_send($cmd);
        $this->_readTo($this->_promptfull);
        
        return $this->_data;
		
	}   
    

} // Quagga

// USAGE EXAMPLE
/*
$quagga = new Quagga("10.0.0.1", "pass", "2605", 1);
$quagga->connect();
$RESULT = $quagga->bgpd("show ip bgp");
$quagga->close();
echo $RESULT;
*/