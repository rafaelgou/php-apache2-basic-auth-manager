<?php

namespace PHPHttp;

/**
 * @see http://www.kavoir.com/2012/04/php-class-for-handling-htpasswd-and-htgroup-member-login-user-management.html
 */
class Passwd {
    
    private $file = '';
    private $salt = '';
    
    public function __construct($file) {

        $this->salt = $salt = uniqid(mt_rand(), true);

        if (file_exists($file)) {
            $this -> file = $file;
        } else {
            die($file." doesn't exist.");
            return false;
        }
    }

    private function write($pairs = array()) {
        $str = '';
        foreach ($pairs as $username => $password) {
            $str .= "$username:{SHA}$password\n";
        }
        file_put_contents($this -> file, $str);
    }
    
    private function read() {
        $pairs = array();
        $fh = fopen($this -> file, 'r');
        while (!feof($fh)) {
            $pair_str = str_replace("\n", '', fgets($fh));
            $pair_array = explode(':{SHA}', $pair_str);
            if (count($pair_array) == 2) {
                $pairs[$pair_array[0]] = $pair_array[1];
            }
        }
        return $pairs;
    }
    
    private function getHash($clear_password = '') {
        if (!empty($clear_password)) {
            return base64_encode(sha1($clear_password, true));
        } else {
            return false;
        }
    }
    
    public function getUsers() {
        return $this -> read();
    }
    
    public function addUser($username = '', $clear_password = '') {
        if (!empty($username) && !empty($clear_password)) {
            $all = $this -> read();
            if (!array_key_exists($username, $all)) {
                $all[$username] = $this -> getHash($clear_password);
                $this -> write($all);
            }
        } else {
            return false;
        }
    }
    
    public function deleteUser($username = '') {
        $all = $this -> read();
        if (array_key_exists($username, $all)) {
            unset($all[$username]);
            $this -> write($all);
        } else {
            return false;
        }
    }
    
    public function userExists($username = '') {
        $all = $this -> read();
        if (array_key_exists($username, $all)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getClearPassword($username) {
        return strtolower(substr(sha1($username.$this -> salt), 4, 12));
    }
    
    public function getUsersAndGroups(Group $groupHandler)
    {   
        $users = $this->read();

        $usersAndGroups = array();

        foreach($users as $username => $password) {
            $usersAndGroups[$username] = array(
                'username' => $username,
                'password' => $password,
                'groups'   => $groupHandler->getGroupsByUser($username),
            );
            
        }

        return $usersAndGroups;        
    }
}
