<?php

/**
 * SAML 2.0 remote IdP metadata for SimpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-remote
 */

$metadata['https://sts.windows.net/3eda8495-cd20-4cf1-8d32-dc5ba0b065bd/'] = [
    'entityid' => 'https://sts.windows.net/3eda8495-cd20-4cf1-8d32-dc5ba0b065bd/',
    'contacts' => [],
    'metadata-set' => 'saml20-idp-remote',
    'SingleSignOnService' => [
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'Location' => 'https://login.microsoftonline.com/3eda8495-cd20-4cf1-8d32-dc5ba0b065bd/saml2',
        ],
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            'Location' => 'https://login.microsoftonline.com/3eda8495-cd20-4cf1-8d32-dc5ba0b065bd/saml2',
        ],
    ],
    'SingleLogoutService' => [
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'Location' => 'https://login.microsoftonline.com/3eda8495-cd20-4cf1-8d32-dc5ba0b065bd/saml2',
        ],
    ],
    'ArtifactResolutionService' => [],
    'NameIDFormats' => [],
    'keys' => [
        [
            'encryption' => false,
            'signing' => true,
            'type' => 'X509Certificate',
            'X509Certificate' => 'MIIC8DCCAdigAwIBAgIQaFfMGVkR5o9OwKKn0AzZ1zANBgkqhkiG9w0BAQsFADA0MTIwMAYDVQQDEylNaWNyb3NvZnQgQXp1cmUgRmVkZXJhdGVkIFNTTyBDZXJ0aWZpY2F0ZTAeFw0yMTA4MjgwNDI2NDNaFw0yNDA4MjgwNDI2NDNaMDQxMjAwBgNVBAMTKU1pY3Jvc29mdCBBenVyZSBGZWRlcmF0ZWQgU1NPIENlcnRpZmljYXRlMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtPjQ121gwJ5FgBsZyM7T/aglyOBiTLy6bjopqotm6gvxKUB7wR9ojYkmXROS0n6DJvnwgR87wF14X6fawoiP2oX9VFi1GZfddOrX7LnT+vxFn94BAzRoIqlx4uHJfRLhYFpgmqCMffS9nNdC65YkGRy+qsgfYxBRgx0sJaOoOSvE9uZoV/pNX3AkettdMCVcC5n8+g1iFJ/AhtIqQGYpD96tRBVN9lJECiyuHlCZmrLRWGCL8cAp+5mQUlq+563e5sze4wvxS60+L8IVi49rMoJrlLaaKfme3obKGvnOVkGNVcc1JSdrRG4DoUZNIROrBUuN+HhF5at4PM+iD5SxTQIDAQABMA0GCSqGSIb3DQEBCwUAA4IBAQBm6IBsuYv6jlLADSeV5NyhQGu4H5kaZsjlPN9z+HfMENupSWaDT8/fLDJfcM6p40jd4l3089FiPUOHKU5pJ6tUnB/2yERSJIBetAuUq++BtB6bScpAl8JFfIFG7xLfxxFFALKB08dEQa+zWbmrGSxIvIE5WHIyNrVD0dMo25gHwzOqkNw0zLMNVpsdYcIbjVqRCmeJxXqbYXK0TH+TvufJS7DAxcmKHEJlqEmS68kbCctEBKBr+5pZVGqTUQXOU40/S2BwHZtogD2Y+l8PTIcuyGIAv4n+4sBOnTdvhdfd0F2VHlpqVXrR77qbW91jX2DOnfsjLyx6fCxgKMg4lYQ8',
        ],
    ],
];