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
 * User Model
 *
 * @class
 */
class User
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $hash;

    /**
     * @var array
     */
    protected $groups;

    /**
     * Constructor
     * @param string $username Username
     * @param array  $groups   Groups
     * @param string $password Password
     */
    public function __construct($username = null, array $groups = array(), $password = null)
    {
        $this->username = $username;
        $this->groups   = $groups;
        $this->setPassword($password);
    }

    /**
     * Set username
     * @param string $username Username
     * @return App\Model\User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set Password
     * @param string $password Password
     * @return App\Model\User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        if (null !== $password) {
            $this->setHash(password_hash($password, PASSWORD_BCRYPT));
        }

        return $this;
    }

    /**
     * Get Password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set groups
     * @param array $groups Groups
     * @return App\Model\User
     */
    public function setGroups(array $groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get Groups
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add a group
     * @param string $group Group name
     * @return App\Model\User
     */
    public function addGroup($group)
    {
        if (!in_array($group, $this->groups)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    /**
     * Remove a group
     * @param string $group Group name
     * @return App\Model\User
     */
    public function removeGroup($group)
    {
        foreach ($this->groups as $key => $grp) {
            if ($grp === $group) {
                unset($this->groups[$key]);
            }
        }

        return $this;
    }

    /**
     * Check if user has a group
     * @param string $group Group name
     * @return boolean
     */
    public function hasGroup($group)
    {
        foreach ($this->groups as $grp) {
            if ($grp === $group) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get Hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set Hash
     * @param string $hash Hash
     * @return App\Model\User
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Format to HT write
     * @return string
     */
    public function formatHT()
    {
        return "{$this->getUsername()}: {$this->getHash()}";
    }
}
