<?php
/*
 * This file is part of the PHP Apache2 Basic Auth package.
 *
 * (c) Rafael Goulart <rafaelgou@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apache2BasicAuth;

use Apache2BasicAuth\Model\Group;
use Apache2BasicAuth\Model\User;

/**
 * HT Service
 * @author Rafael Goulart <rafaelgou@gmail.com>
 */
class Service
{

    /**
     * @var string
     */
    protected $groupFile;

    /**
     * @var string
     */
    protected $passwdFile;

    /**
     * @var array
     */
    protected $groups;

    /**
     * @var array
     */
    protected $users;

    /**
     * Constructor
     * @param string $passwdFile The .htpasswd filename
     * @param string $groupFile  The .htgroups filename
     */
    public function __construct($passwdFile, $groupFile = null)
    {
        if (!file_exists($passwdFile)) {
            throw new \Exception(".htpasswd file doesn't exist", 500);
        }
        if (null !== $groupFile
            && !empty($groupFile)
            && !file_exists($passwdFile)
            ) {
            throw new \Exception(".htgroups file doesn't exist", 500);
        }
        $this->groupFile  = $groupFile;
        $this->passwdFile = $passwdFile;
        $this->groups     = array();
        $this->users      = array();
        $this->read();
    }

    /**
     * Read .htgroups file from disc
     * @return Apache2BasicAuth\Service
     */
    public function readGroups()
    {
        if (null === $this->groupFile || empty($this->groupFile)) {
            return $this;
        }

        $file = file($this->groupFile, FILE_IGNORE_NEW_LINES);
        foreach ($file as $line) {
            if (!empty($line)) {
                $tmpArray  = explode(':', $line);
                $groupname = trim($tmpArray[0]);
                $users     = count($tmpArray) === 2
                    ? explode(' ', trim($tmpArray[1]))
                    : array();
            }
            $this->groups[$groupname] = (new Group($groupname, $users));
        }

        return $this;
    }

    /**
     * Write .htgroups file to disc
     * @return Apache2BasicAuth\Service
     */
    public function writeGroups()
    {
        $content = array();
        foreach ($this->getGroups() as $key => $group) {
            $content[$key] = $group->formatHT().PHP_EOL;
        }
        ksort($content);
        file_put_contents($this->groupFile, implode('', $content));

        return $this;
    }

    /**
     * Read .htpasswd file from disc
     * @return Apache2BasicAuth\Service
     */
    public function readPasswd()
    {
        $file = file($this->passwdFile, FILE_IGNORE_NEW_LINES);
        foreach ($file as $line) {
            if (!empty($line)) {
                $tmpArray  = explode(':', $line);
                $username  = trim($tmpArray[0]);
                $hash      = trim($tmpArray[1]);
            }
            $user = new User($username, $this->findGroupsForUsers($username));
            $user->setHash($hash);
            $this->users[$username] = $user;
        }

        return $this;
    }

    /**
     * Write .htpasswd file to disc
     * @return Apache2BasicAuth\Service
     */
    public function writePasswd()
    {
        $content = array();
        foreach ($this->getUsers() as $key => $user) {
            $content[$key] = $user->formatHT().PHP_EOL;
        }
        ksort($content);
        file_put_contents($this->passwdFile, implode('', $content));

        return $this;
    }

    /**
     * Read files from disc
     * @return Apache2BasicAuth\Service
     */
    public function read()
    {
        $this->readGroups();
        $this->readPasswd();

        return $this;
    }

    /**
     * Write files to disc
     * @return Apache2BasicAuth\Service
     */
    public function write()
    {
        $this->writePasswd();
        $this->writeGroups();

        return $this;
    }

    /**
     * Get groups
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set groups
     * @param array $groups Array of groups
     * @return Apache2BasicAuth\Service
     */
    public function setGroups(array $groups)
    {
        $this->groups = array();
        foreach ($groups as $group) {
            $this->persistGroup($group);
        }

        return $this;
    }

    /**
     * Persist group
     * @param Apache2BasicAuth\Model\Group $group Group
     * @return Apache2BasicAuth\Service
     */
    public function persistGroup(Group $group)
    {
        $old = $this->findGroup($group->getName());
        if (null !== $old) {
            foreach ($old->getUsers() as $username) {
                if (!$group->hasUser($username)) {
                    $user = $this->findUser($username);
                    $user->removeGroup($group->getName());
                    $this->users[$username] = $user;
                }
            }
        }

        foreach ($group->getUsers() as $username) {
            $user = $this->findUser($username);
            $user->addGroup($group->getName());
            $this->users[$username] = $user;
        }
        $this->groups[$group->getName()] = $group;

        return $this;
    }

    /**
     * Remove group
     * @param Apache2BasicAuth\Model\Group $group Group
     * @return Apache2BasicAuth\Service
     */
    public function removeGroup(Group $group)
    {
        if (array_key_exists($group->getName(), $this->groups)) {
            unset($this->groups[$group->getName()]);
        }
        foreach ($this->getUsers() as $user) {
            $user->removeGroup($group->getName());
        }

        return $this;
    }

    /**
     * Find group by name
     * @param string $groupname Group name
     * @return Apache2BasicAuth\Model\Group|null
     */
    public function findGroup($groupname)
    {
        return array_key_exists($groupname, $this->groups)
            ? clone $this->groups[$groupname]
            : null;
    }

    /**
     * Get group names
     * @return array
     */
    public function getGroupNames()
    {
        return array_keys($this->getGroups());
    }

    /**
     * Get usernames
     * @return array
     */
    public function getUsernames()
    {
        return array_keys($this->getUsers());
    }

    /**
     * Create a group instance
     * @param string $groupname Group name
     * @param array  $users     Users
     * @return Apache2BasicAuth\Model\Group
     */
    public function createGroup($groupname = null, array $users = array())
    {
        $group = new Group($groupname, $users);

        return $group;
    }

    /**
     * Get users
     * @return array
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set users
     * @param array $users Array of users
     * @return Apache2BasicAuth\Service
     */
    public function setUsers(array $users)
    {
        $this->users = array();
        foreach ($users as $user) {
            $this->persistUser($user);
        }

        return $this;
    }

    /**
     * Persist user
     * @param Apache2BasicAuth\Model\User $user User
     * @return Apache2BasicAuth\Service
     */
    public function persistUser(User $user)
    {
        $old = clone $this->findUser($user->getUsername());

        if (null !== $old) {
            foreach ($old->getGroups() as $groupname) {
                if (!$user->hasGroup($groupname)) {
                    $group = $this->findGroup($groupname);
                    $group->removeUser($user->getUsername());
                    $this->groups[$groupname] = $group;
                }
            }
        }

        foreach ($user->getGroups() as $groupname) {
            $group = $this->findGroup($groupname);
            $group->addUser($user->getUsername());
            $this->groups[$groupname] = $group;
        }
        $this->users[$user->getUsername()] = $user;

        return $this;
    }

    /**
     * Remove user
     * @param Apache2BasicAuth\Model\User $user User
     * @return Apache2BasicAuth\Service
     */
    public function removeUser(User $user)
    {
        if (array_key_exists($user->getUsername(), $this->users)) {
            foreach ($this->getGroups() as $group) {
                $group->removeUser($user->getUsername());
            }
            unset($this->users[$user->getUsername()]);
        }

        return $this;
    }

    /**
     * Create a user instance and persists to collection
     * @param string $username Username
     * @param array  $groups   Groups
     * @param string $password User password
     * @return Apache2BasicAuth\Service
     */
    public function createUser($username = null, array $groups = array(), $password = null)
    {
        $user = new User($username, $groups, $password);

        return $user;
    }

    /**
     * Find user by name
     * @param string $username User
     * @return Apache2BasicAuth\Model\User|null
     */
    public function findUser($username)
    {
        return array_key_exists($username, $this->users)
            ? clone $this->users[$username]
            : null;
    }

    /**
     * Persist user or group
     * @param object $object User or Group
     * @return Apache2BasicAuth\Service
     */
    public function persist($object)
    {
        if ($object instanceof User) {
            return $this->persistUser($object);
        }

        if ($object instanceof Group) {
            return $this->persistGroup($object);
        }

        throw new \Exception("Object not allow to persist", 500);
    }

    /**
     * Find groups for an user
     * @param string $username Username
     * @return array
     */
    protected function findGroupsForUsers($username)
    {
        $groups = array();
        foreach ($this->getGroups() as $group) {
            if ($group->hasUser($username)) {
                $groups[] = $group->getName();
            }
        }
        sort($groups);

        return $groups;
    }
}
