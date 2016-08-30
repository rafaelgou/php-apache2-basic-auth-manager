<?php

namespace Apache2AuthManager;

/**
 * Class PHPHttp/Group
 *
 * @author Rafael Goulart <rafaelgou@gmail.com>
 * @see http://www.kavoir.com/2012/04/php-class-for-handling-htpasswd-and-htgroup-member-login-user-management.html
 */
class Group {

    private $file = '';

    public function __construct($file)
    {
        if (!file_exists($file)) {
            exit($file." doesn't exist.");
        }
        $this->file = $file;
    }

    private function write($groups = array())
    {
        $str = '';
        foreach ($groups as $group => $users) {
            $users_str = '';
            foreach ($users as $user) {
                if (!empty($users_str)) {
                    $users_str .= ' ';
                }
                $users_str .= $user;
            }
            $str .= "{$group}: $users_str\n";
        }
        file_put_contents($this->file, $str);
    }

    private function read()
    {
        $groups = array();
        $groups_str = file($this->file, FILE_IGNORE_NEW_LINES);
        foreach ($groups_str as $group_str) {
            if (!empty($group_str)) {
                $group_str_array = explode(':', $group_str);
                $groupname = trim($group_str_array[0]);
                $members   = trim($group_str_array[1]);
                if (count($group_str_array) == 2) {
                    $groups[$groupname] = explode(' ', $members);
                } else {
                    $groups[$groupname] = array();
                }
            }
        }
        return $groups;
    }

    public function getGroups()
    {
        return $this->read();
    }

    public function getGroup($group)
    {
        $all = $this->read();
        if (isset($all[$group])) {
            return $all[$group];
        } else {
            return false;
        }
    }

    public function addUserToGroup($username, $group)
    {
        $all = $this->read();
        if (isset($all[$group])) {
            if (!in_array($username, $all[$group])) {
                $all[$group][] = $username;
            }
        } else {
            $all[$group][] = $username;
        }
        $this->write($all);
    }

    public function setUsersToGroup($group, $users)
    {
        $all = $this->read();
        if (isset($all[$group])) {
            $all[$group] = $users;

        }

        $this->write($all);
    }

    public function setGroupsToUser($username, $groups)
    {
        $all = $this->read();

        foreach($all as $group => $users) {
            $user_index = array_search($username, $all[$group]);
            if ($user_index !== false) {
                unset($all[$group][$user_index]);
            }
        }

        foreach($groups as $group) {
            $all[$group][] = $username;
        }

        $this->write($all);
    }


    public function deleteUserFromGroup($username, $group)
    {
        $all = $this->read();
        if (array_key_exists($group, $all)) {
            $user_index = array_search($username, $all[$group]);
            if ($user_index !== false) {
                unset($all[$group][$user_index]);
                $this->write($all);
            }
        } else {
            return false;
        }
    }

    public function getGroupsByUser($username)
    {
        $all = $this->read();
        $user_groups = array();
        foreach ($all as $group => $users) {
            if (in_array($username, $users)) {
                $user_groups[] = $group;
            }
        }
        return $user_groups;
    }

    public function isInGroup($username, $group)
    {
        return in_array($group, $this->getGroupsByUser($username));
    }

    public function groupExists($group)
    {
        $all = $this->read();
        if (array_key_exists($group, $all)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteGroup($group)
    {
        $all = $this->read();
        if (array_key_exists($group, $all)) {
            unset($all[$group]);
            $this->write($all);
        } else {
            return false;
        }
    }

    public function addGroup($group)
    {
        $all = $this->read();
        if (!array_key_exists($group, $all)) {
            $all[$group] = array();
            $this->write($all);
        } else {
            return false;
        }
    }

}
