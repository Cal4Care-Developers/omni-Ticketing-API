<?php

/**
 * SAML 2.0 remote IdP metadata for SimpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-remote
 */
$metadata['MetaentityID'] = [
    'entityid' => 'MetaentityID',
    'contacts' => [],
    'metadata-set' => 'saml20-idp-remote',
    'SingleSignOnService' => [
        [
            'Binding' => 'SingleLogoutServiceBinding',
            'Location' => 'SingleLogoutServiceLocation',
        ],
        [
            'Binding' => 'SingleLogoutServiceBinding',
            'Location' => 'SingleLogoutServiceLocation',
        ],
    ],
    'SingleLogoutService' => [
        [
            'Binding' => 'SingleLogoutServiceBinding',
            'Location' => 'SingleLogoutServiceLocation',
        ],
    ],
    'ArtifactResolutionService' => [],
    'NameIDFormats' => [],
    'keys' => [
        [
            'encryption' => false,
            'signing' => true,
            'type' => 'X509Certificate',
            'X509Certificate' => 'MSSSOX509Certificate',
        ],
    ],
];