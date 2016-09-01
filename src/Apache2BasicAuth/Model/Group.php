<?php
/*
 * This file is part of the PHP Apache2 Basic Auth Manager package.
 *
 * (c) Rafael Goulart <rafaelgou@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apache2BasicAuth\Model;

/**
 * Group Model
 *
 * @class
 */
class Group
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $users;

    /**
     * Constructor
     * @param string $name  Group name
     * @param array  $users Users
     */
    public function __construct($name = null, array $users = array())
    {
        $this->name  = $name;
        $this->users = $users;
    }

    /**
     * Set name
     * @param string $name Group name
     * @return Apache2BasicAuth\Model\Group
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set users
     * @param array $users Users
     * @return Apache2BasicAuth\Model\User
     */
    public function setUsers(array $users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get Users
     * @return array
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Check if group has a user
     * @param string $user User name
     * @return boolean
     */
    public function hasUser($user)
    {
        return in_array($user, $this->users);
    }

    /**
     * Add a user
     * @param string $user User name
     * @return Apache2BasicAuth\Model\User
     */
    public function addUser($user)
    {
        if (!$this->hasUser($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    /**
     * Remove a user
     * @param string $user User name
     * @return Apache2BasicAuth\Model\User
     */
    public function removeUser($user)
    {
        foreach ($this->users as $key => $grp) {
            if ($grp === $user) {
                unset($this->users[$key]);
            }
        }

        return $this;
    }

    /**
     * Format to HT write
     * @return string
     */
    public function formatHT()
    {
        $users = $this->getUsers();
        sort($users);
        print_r($users);
        $users = implode(' ', $users);

        return "{$this->getName()}: {$users}";
    }

}
