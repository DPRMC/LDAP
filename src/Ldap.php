<?php

namespace DPRMC\Ldap;

use DPRMC\Ldap\Exceptions\AuthenticationFailed;
use DPRMC\Ldap\Exceptions\LdapBindFailed;
use DPRMC\Ldap\Exceptions\UnableToConnectToLdapServer;
use DPRMC\Ldap\Exceptions\UnableToReachLdapServer;

class Ldap {

    protected $ldapHost;
    protected $ldapPort;
    protected $timeout;
    protected $ldapVersion;

    protected $errno;
    protected $errstr;

    public function __construct( string $ldapHost, int $ldapPort = 389, float $timeout = 1.0, int $ldapVersion = 3 ) {
        $this->ldapHost = $ldapHost;
        $this->ldapPort = $ldapPort;
        $this->timeout  = $timeout;
        $this->ldapVersion = $ldapVersion;
        @fsockopen( $this->ldapHost, $this->ldapPort, $this->errno, $this->errstr, $this->timeout );
    }

    public function authenticate( string $rdn, string $userName, string $password ): bool {
        $filePointer = @fsockopen( $this->ldapHost, $this->ldapPort, $this->errno, $this->errstr, $this->timeout );

        if ( false === $filePointer ):
            throw new UnableToReachLdapServer( "Unable to reach the ldap server you tried at: " . $this->ldapHost . ':' . $this->ldapPort . " with a timeout of " . $this->timeout . " seconds." );
        endif;



        // Connecting to LDAP
        $ldapLinkIdentifier = ldap_connect( $this->ldapHost, $this->ldapPort );

        if ( false === $ldapLinkIdentifier ):
            throw new UnableToConnectToLdapServer( "Unable to reach the ldap server you tried at: " . $this->ldapHost . ':' . $this->ldapPort );
        endif;

        // Set version
        ldap_set_option($ldapLinkIdentifier, LDAP_OPT_PROTOCOL_VERSION, $this->ldapVersion);

        // binding to ldap server
        try {
            $ldapIsBound = ldap_bind( $ldapLinkIdentifier, $rdn, $password );
        } catch ( \Exception $exception ) {
            throw new AuthenticationFailed( "Login failed. Your username and/or password were incorrect.", 0, $exception );
        }

        if ( false === $ldapIsBound ):
            throw new LdapBindFailed("The ldap_bind() failed.");
        endif;

        return true;
    }
}