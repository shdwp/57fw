<?php
namespace Uac\Manager;

/**
 * @TODO: raise exceptions
 */
class UserManager extends \Orm\Manager {
    /**
     *  Create user with username, password, email
     *  @param string $username 
     *  @param string $raw_password 
     *  @param string $email
     *  @return \Auth\Entity\User
     */
    public function credentialsCreate($username, $raw_password, $email) {
        if ($this->find()
            ->filter('username =', $username)
            ->filter('email =', $email, 'or')
            ->count()
        ) throw new \Uac\Ex\UserExistException($username, $email);
            
        $user = new \Uac\Model\User();
        $user->username = $username;
        $user->password = $this->encryptPassword($raw_password);
        $user->email = $email;

        $this->save($user);
        return $user;
    }

    /**
     * Login user trought credentials
     * @param string $username
     * @param string $password
     * @throw \sfException
     * @return \Auth\Entity\User
     */
    public function credentialsLogin($username, $password) {
        $user = $this->find()
            ->filter('username =', $username)
            ->filter('password =', $this->encryptPassword($password))
            ->current();

        if ($user) {
            $user->auth_token = $this->generateToken($user);
            $user->auth_token_expire = (new \DateTime())->add(new \DateInterval('P14D'));
            $this->save($user);

            return $user;
        } else {
            return null;
        }
    }

    public function login($user, $res) {
        return $res->setCookie(
            'uac_token',
            $user->auth_token,
            time() + 72000,
            '/'
        ); 
    }

    /**
     * Login user trought User instance
     * @param \Auth\Entity\User $user
     * @return \Auth\Entity\User
     */
    public function instanceLogin($user) {
        return $this->credentialsLogin($user->username, $user->password);
    }

    /**
     * Login user troght cookie token
     * @param string $auth_token
     * @throw \sfException
     * @return \Auth\Entity\User
     */
    public function authTokenLogin($auth_token) {
        $user = $this->find()
            ->filter('auth_token =', $auth_token)
            ->current();

        if ($user) {
            $user->auth_token_expire = (new \DateTime())->add(new \DateInterval('P14D'));
            $this->save($user);
            return $user;
        } else {
            return null;
        }
    }

    /**
     * Logout user (change auth token)
     * @param \Auth\Entity\User $user
     */
    public function logout($user) {
        $user->auth_token = $this->generateToken($user);
    }

    /**
     * Generate token for cookie
     * @param \Auth\Entity\User $user
     * @return string
     */
    private function generateToken($user) {
        $stoken = $this->e->uac()->config('secret_token');
        return md5(
            $stoken . '/' .
            $user->username . '/' . 
            microtime() . '/' . 
            rand(0, strlen($stoken)*100)
        );
    }
    
    /**
     * Encrypt raw password
     * @param strng $raw_password
     * @return string
     */
    private function encryptPassword($raw_password) {
        return $raw_password;
    }
}
