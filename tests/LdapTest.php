<?php

use PHPUnit\Framework\TestCase;
use DPRMC\Ldap\Ldap;

class LdapTest extends TestCase {
    /**
     * @link http://www.forumsys.com/tutorials/integration-how-to/ldap/online-ldap-test-server/
     */
    public function testValidAuthentication() {
        $host          = 'ldap.forumsys.com';
        $port          = 389;
        $rdn           = 'cn=read-only-admin,dc=example,dc=com';
        $userName      = 'uid=tesla';
        $password      = 'password';
        $timeout       = 1.0;
        $ldapVersion   = 3;
        $ldap          = new Ldap( $host, $port, $timeout, $ldapVersion );
        $authenticated = $ldap->authenticate( $rdn, $userName, $password );
        $this->assertTrue( $authenticated );
    }
}
