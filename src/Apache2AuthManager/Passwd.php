<?php

namespace Apache2AuthManager;

/**
 * Class PHPHttp/Passwd
 *
 * @author Rafael Goulart <rafaelgou@gmail.com>
 * @see http://www.kavoir.com/2012/04/php-class-for-handling-htpasswd-and-htgroup-member-login-user-management.html
 */
class Passwd {

    private $file = '';

    public function __construct($file)
    {
      if (!file_exists($file)) {
          exit($file." doesn't exist.");
      }
      $this->file = $file;

    }

    private function write($pairs = array())
    {
        $str = '';
        foreach ($pairs as $username => $password) {
            $str .= "$username:$password\n";
        }
        file_put_contents($this->file, $str);
    }

    private function read()
    {
        $pairs = array();
        $fh = fopen($this->file, 'r');
        while (!feof($fh)) {
            $pair_str = str_replace("\n", '', fgets($fh));
            $pair_array = explode(':', $pair_str);
            if (count($pair_array) == 2) {
                $pairs[$pair_array[0]] = $pair_array[1];
            }
        }
        return $pairs;
    }

    private function getHash($clearPassword = '')
    {
        if (!empty($clearPassword)) {
            return password_hash($clearPassword, PASSWORD_BCRYPT);
        } else {
            return false;
        }
    }

    public function getUsers()
    {
        return $this->read();
    }

    public function addUser($username, $clearPassword)
    {
        $all = $this->read();
        if (!array_key_exists($username, $all)) {
            $all[$username] = $this->getHash($clearPassword);
            $this->write($all);
        }
    }

    public function editUser($username, $clearPassword)
    {
        $all = $this->read();
        if (array_key_exists($username, $all)) {
            $all[$username] = $this->getHash($clearPassword);
            $this->write($all);
        }
    }

    public function deleteUser($username = '')
    {
        $all = $this->read();
        if (array_key_exists($username, $all)) {
            unset($all[$username]);
            $this->write($all);
        } else {
            return false;
        }
    }

    public function userExists($username = '')
    {
        $all = $this->read();
        if (array_key_exists($username, $all)) {
            return true;
        } else {
            return false;
        }
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
