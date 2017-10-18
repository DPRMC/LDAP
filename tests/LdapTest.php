<?php

use DPRMC\Ldap\Ldap;
use PHPUnit\Framework\TestCase;

class LdapTest extends TestCase {
    /**
     * @link http://www.forumsys.com/tutorials/integration-how-to/ldap/online-ldap-test-server/
     */
    public function testValidAuthentication() {
        $host          = 'ldap.forumsys.com';
        $port          = 389;
        $rdn           = 'cn=read-only-admin,dc=example,dc=com';
        $password      = 'password';
        $timeout       = 1.0;
        $ldapVersion   = 3;
        $ldap          = new Ldap( $host, $port, $timeout, $ldapVersion );
        $authenticated = $ldap->authenticate( $rdn, $password );
        $this->assertTrue( $authenticated );
    }


    public function testLdapConnectFailure() {
        $this->expectException( \DPRMC\Ldap\Exceptions\AuthenticationFailed::class );
        $host        = 'ldap.forumsys.com';
        $port        = 389;
        $rdn         = 'cn=read-only-admin,dc=example,dc=com';
        $password    = 'password';
        $timeout     = 1.0;
        $ldapVersion = 999;
        $ldap        = new Ldap( $host, $port, $timeout, $ldapVersion );
        $ldap->authenticate( $rdn, $password );
    }

    public function testInvalidAuthentication() {
        $this->expectException( \DPRMC\Ldap\Exceptions\AuthenticationFailed::class );
        $host          = 'ldap.forumsys.com';
        $port          = 389;
        $rdn           = 'cn=read-only-admin,dc=example,dc=com';
        $userName      = 'uid=tesla';
        $password      = 'badPassword';
        $timeout       = 1.0;
        $ldapVersion   = 3;
        $ldap          = new Ldap( $host, $port, $timeout, $ldapVersion );
        $authenticated = $ldap->authenticate( $rdn, $password );
    }




}
