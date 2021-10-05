<?php

declare(strict_types=1);

namespace PackageVersions;

use Composer\InstalledVersions;
use OutOfBoundsException;

class_exists(InstalledVersions::class);

/**
 * This class is generated by composer/package-versions-deprecated, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 *
 * @deprecated in favor of the Composer\InstalledVersions class provided by Composer 2. Require composer-runtime-api:^2 to ensure it is present.
 */
final class Versions
{
    /**
     * @deprecated please use {@see self::rootPackageName()} instead.
     *             This constant will be removed in version 2.0.0.
     */
    const ROOT_PACKAGE_NAME = 'simplesamlphp/simplesamlphp';

    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    const VERSIONS          = array (
  'gettext/gettext' => 'v4.8.5@ef2e312dff383fc0e4cd62dd39042e1157f137d4',
  'gettext/languages' => '2.8.1@4ad818b6341e177b7c508ec4c37e18932a7b788a',
  'phpfastcache/riak-client' => '3.4.3@d771f75d16196006604a30bb15adc1c6a9b0fcc9',
  'phpmailer/phpmailer' => 'v6.5.1@dd803df5ad7492e1b40637f7ebd258fee5ca7355',
  'psr/cache' => '1.0.1@d11b50ad223250cf17b86e38383413f5a6764bf8',
  'psr/container' => '1.1.1@8622567409010282b7aeebe4bb841fe98b58dcaf',
  'psr/event-dispatcher' => '1.0.0@dbefd12671e8a14ec7f180cab83036ed26714bb0',
  'psr/log' => '1.1.4@d49695b909c3b7628b6289db5479a1c204601f11',
  'robrichards/xmlseclibs' => '3.1.1@f8f19e58f26cdb42c54b214ff8a820760292f8df',
  'simplesamlphp/assert' => 'v0.0.13@5429921b320ca4f9d1844225884ac52f649ea1e3',
  'simplesamlphp/composer-module-installer' => 'v1.1.8@45161b5406f3e9c82459d0f9a5a1dba064953cfa',
  'simplesamlphp/saml2' => 'v4.2.4@8e3ad89b97d2f2f922f67894675e3460feab2209',
  'simplesamlphp/simplesamlphp-module-adfs' => 'v0.9.8@ac2ba46a6b94ed48b527ac190b0fa99bcda8d98e',
  'simplesamlphp/simplesamlphp-module-authcrypt' => 'v0.9.3@9a2c1a761e2d94394a4f2d3499fd6f0853899530',
  'simplesamlphp/simplesamlphp-module-authfacebook' => 'v0.9.3@9152731e939ad4a49e0f06da5f0009ebde0d2b5c',
  'simplesamlphp/simplesamlphp-module-authorize' => 'v0.9.3@0593bfcb84fca9d9133f415246ab8ca51b412c92',
  'simplesamlphp/simplesamlphp-module-authtwitter' => 'v0.9.1@29a15e58061222632fea9eb2c807aef5e2c0d54a',
  'simplesamlphp/simplesamlphp-module-authwindowslive' => 'v0.9.1@f40aecec6c0adaedb6693309840c98cec783876e',
  'simplesamlphp/simplesamlphp-module-authx509' => 'v0.9.8@66525b1ec4145ec8d0d0e9db4534624b6be4c1fb',
  'simplesamlphp/simplesamlphp-module-authyubikey' => 'v0.9.1@8c27bfeb4981d2e6fa40a831e945f40c5a4ad3d2',
  'simplesamlphp/simplesamlphp-module-cas' => 'v0.9.1@63b72e4600550c507cdfc32fdd208ad59a64321e',
  'simplesamlphp/simplesamlphp-module-cdc' => 'v0.9.1@16a5bfac7299e04e5feb472af328e07598708166',
  'simplesamlphp/simplesamlphp-module-consent' => 'v0.9.7@16a347ee4003e2adf415284b334a582e9b8a5717',
  'simplesamlphp/simplesamlphp-module-consentadmin' => 'v0.9.1@466e8d0d751f0080162d78e63ab2e125b24d17a1',
  'simplesamlphp/simplesamlphp-module-discopower' => 'v0.9.1@006c0617610f1bae11cf4d17e8ce4c509239a60e',
  'simplesamlphp/simplesamlphp-module-exampleattributeserver' => 'v1.0.0@63e0323e81c32bc3c9eaa01ea45194bb10153708',
  'simplesamlphp/simplesamlphp-module-expirycheck' => 'v0.9.3@59c59cdf87e2679257b46c07bb4c27666a11cc20',
  'simplesamlphp/simplesamlphp-module-ldap' => 'v0.9.12@88f2f40eedc27abfc5a9e5b61ae86cd6e86da4e1',
  'simplesamlphp/simplesamlphp-module-memcachemonitor' => 'v0.9.2@900b5c6b59913d9013b8dae090841a127ae55ae5',
  'simplesamlphp/simplesamlphp-module-memcookie' => 'v1.2.2@39535304e8d464b7baa1e82cb441fa432947ff57',
  'simplesamlphp/simplesamlphp-module-metarefresh' => 'v0.9.6@e284306a7097297765b5b78a4e28f19f18d4e001',
  'simplesamlphp/simplesamlphp-module-negotiate' => 'v0.9.11@e7c4597110c753a750cd522220fc2a5a34b7c1b8',
  'simplesamlphp/simplesamlphp-module-oauth' => 'v0.9.3@2a2433144dca408315e4ee163f9ab73a6110b2b1',
  'simplesamlphp/simplesamlphp-module-preprodwarning' => 'v0.9.2@8e032de33a75eb44857dc06d886ad94ee3af4638',
  'simplesamlphp/simplesamlphp-module-radius' => 'v0.9.3@36bd0f39f9a13f7eb96ead97c97c3634aa1c3f2d',
  'simplesamlphp/simplesamlphp-module-riak' => 'v0.9.1@c1a9d9545cb4e05b9205b34624850bb777aca991',
  'simplesamlphp/simplesamlphp-module-sanitycheck' => 'v0.9.1@15d6664eae73a233c3c4c72fd8a5c2be72b6ed2a',
  'simplesamlphp/simplesamlphp-module-smartattributes' => 'v0.9.1@b45d3ecd916e359a9cae05f9ae9df09b5c42f4e6',
  'simplesamlphp/simplesamlphp-module-sqlauth' => 'v0.9.3@c2dc4fc8aa6d8b2408131e09b39f06d8610ff374',
  'simplesamlphp/simplesamlphp-module-statistics' => 'v0.9.6@03fb6bdbbf5ce0a0cb257208db79aacac227ac10',
  'simplesamlphp/twig-configurable-i18n' => 'v2.3.4@e2bffc7eed3112a0b3870ef5b4da0fd74c7c4b8a',
  'symfony/cache' => 'v5.3.8@945bcebfef0aeef105de61843dd14105633ae38f',
  'symfony/cache-contracts' => 'v2.4.0@c0446463729b89dd4fa62e9aeecc80287323615d',
  'symfony/config' => 'v5.3.4@4268f3059c904c61636275182707f81645517a37',
  'symfony/console' => 'v5.3.7@8b1008344647462ae6ec57559da166c2bfa5e16a',
  'symfony/dependency-injection' => 'v5.3.8@e39c344e06a3ceab531ebeb6c077e6652c4a0829',
  'symfony/deprecation-contracts' => 'v2.4.0@5f38c8804a9e97d23e0c8d63341088cd8a22d627',
  'symfony/error-handler' => 'v5.3.7@3bc60d0fba00ae8d1eaa9eb5ab11a2bbdd1fc321',
  'symfony/event-dispatcher' => 'v5.3.7@ce7b20d69c66a20939d8952b617506a44d102130',
  'symfony/event-dispatcher-contracts' => 'v2.4.0@69fee1ad2332a7cbab3aca13591953da9cdb7a11',
  'symfony/filesystem' => 'v5.3.4@343f4fe324383ca46792cae728a3b6e2f708fb32',
  'symfony/finder' => 'v5.3.7@a10000ada1e600d109a6c7632e9ac42e8bf2fb93',
  'symfony/framework-bundle' => 'v5.3.8@ea6e30a8c9534d87187375ef4ee39d48ee40dd2d',
  'symfony/http-client-contracts' => 'v2.4.0@7e82f6084d7cae521a75ef2cb5c9457bbda785f4',
  'symfony/http-foundation' => 'v5.3.7@e36c8e5502b4f3f0190c675f1c1f1248a64f04e5',
  'symfony/http-kernel' => 'v5.3.9@ceaf46a992f60e90645e7279825a830f733a17c5',
  'symfony/polyfill-ctype' => 'v1.23.0@46cd95797e9df938fdd2b03693b5fca5e64b01ce',
  'symfony/polyfill-intl-grapheme' => 'v1.23.1@16880ba9c5ebe3642d1995ab866db29270b36535',
  'symfony/polyfill-intl-normalizer' => 'v1.23.0@8590a5f561694770bdcd3f9b5c69dde6945028e8',
  'symfony/polyfill-mbstring' => 'v1.23.1@9174a3d80210dca8daa7f31fec659150bbeabfc6',
  'symfony/polyfill-php73' => 'v1.23.0@fba8933c384d6476ab14fb7b8526e5287ca7e010',
  'symfony/polyfill-php80' => 'v1.23.1@1100343ed1a92e3a38f9ae122fc0eb21602547be',
  'symfony/polyfill-php81' => 'v1.23.0@e66119f3de95efc359483f810c4c3e6436279436',
  'symfony/routing' => 'v5.3.7@be865017746fe869007d94220ad3f5297951811b',
  'symfony/service-contracts' => 'v2.4.0@f040a30e04b57fbcc9c6cbcf4dbaa96bd318b9bb',
  'symfony/string' => 'v5.3.7@8d224396e28d30f81969f083a58763b8b9ceb0a5',
  'symfony/var-dumper' => 'v5.3.8@eaaea4098be1c90c8285543e1356a09c8aa5c8da',
  'symfony/var-exporter' => 'v5.3.8@a7604de14bcf472fe8e33f758e9e5b7bf07d3b91',
  'symfony/yaml' => 'v5.3.6@4500fe63dc9c6ffc32d3b1cb0448c329f9c814b7',
  'twig/extensions' => 'v1.5.4@57873c8b0c1be51caa47df2cdb824490beb16202',
  'twig/twig' => 'v2.14.7@8e202327ee1ed863629de9b18a5ec70ac614d88f',
  'webmozart/assert' => '1.9.1@bafc69caeb4d49c39fd0779086c03a3738cbb389',
  'whitehat101/apr1-md5' => 'v1.0.0@8b261c9fc0481b4e9fa9d01c6ca70867b5d5e819',
  'amphp/amp' => 'v2.6.1@c5fc66a78ee38d7ac9195a37bacaf940eb3f65ae',
  'amphp/byte-stream' => 'v1.8.1@acbd8002b3536485c997c4e019206b3f10ca15bd',
  'composer/package-versions-deprecated' => '1.11.99.4@b174585d1fe49ceed21928a945138948cb394600',
  'composer/semver' => '3.2.5@31f3ea725711245195f62e54ffa402d8ef2fdba9',
  'composer/xdebug-handler' => '1.4.6@f27e06cd9675801df441b3656569b328e04aa37c',
  'dnoegel/php-xdg-base-dir' => 'v0.1.1@8f8a6e48c5ecb0f991c2fdcf5f154a47d85f9ffd',
  'doctrine/instantiator' => '1.4.0@d56bf6102915de5702778fe20f2de3b2fe570b5b',
  'felixfbecker/advanced-json-rpc' => 'v3.2.1@b5f37dbff9a8ad360ca341f3240dc1c168b45447',
  'felixfbecker/language-server-protocol' => '1.5.1@9d846d1f5cf101deee7a61c8ba7caa0a975cd730',
  'mikey179/vfsstream' => 'v1.6.10@250c0825537d501e327df879fb3d4cd751933b85',
  'myclabs/deep-copy' => '1.10.2@776f831124e9c62e1a2c601ecc52e776d8bb7220',
  'netresearch/jsonmapper' => 'v3.1.1@ba09f0e456d4f00cef84e012da5715625594407c',
  'nikic/php-parser' => 'v4.13.0@50953a2691a922aa1769461637869a0a2faa3f53',
  'openlss/lib-array2xml' => '1.0.0@a91f18a8dfc69ffabe5f9b068bc39bb202c81d90',
  'phar-io/manifest' => '1.0.3@7761fcacf03b4d4f16e7ccb606d4879ca431fcf4',
  'phar-io/version' => '2.0.1@45a2ec53a73c70ce41d55cedef9063630abaf1b6',
  'phpdocumentor/reflection-common' => '2.2.0@1d01c49d4ed62f25aa84a747ad35d5a16924662b',
  'phpdocumentor/reflection-docblock' => '5.2.2@069a785b2141f5bcf49f3e353548dc1cce6df556',
  'phpdocumentor/type-resolver' => '1.5.1@a12f7e301eb7258bb68acd89d4aefa05c2906cae',
  'phpspec/prophecy' => '1.14.0@d86dfc2e2a3cd366cee475e52c6bb3bbc371aa0e',
  'phpunit/php-code-coverage' => '6.1.4@807e6013b00af69b6c5d9ceb4282d0393dbb9d8d',
  'phpunit/php-file-iterator' => '2.0.4@28af674ff175d0768a5a978e6de83f697d4a7f05',
  'phpunit/php-text-template' => '1.2.1@31f8b717e51d9a2afca6c9f046f5d69fc27c8686',
  'phpunit/php-timer' => '2.1.3@2454ae1765516d20c4ffe103d85a58a9a3bd5662',
  'phpunit/php-token-stream' => '3.1.3@9c1da83261628cb24b6a6df371b6e312b3954768',
  'phpunit/phpunit' => '7.5.20@9467db479d1b0487c99733bb1e7944d32deded2c',
  'sebastian/code-unit-reverse-lookup' => '1.0.2@1de8cd5c010cb153fcd68b8d0f64606f523f7619',
  'sebastian/comparator' => '3.0.3@1071dfcef776a57013124ff35e1fc41ccd294758',
  'sebastian/diff' => '3.0.3@14f72dd46eaf2f2293cbe79c93cc0bc43161a211',
  'sebastian/environment' => '4.2.4@d47bbbad83711771f167c72d4e3f25f7fcc1f8b0',
  'sebastian/exporter' => '3.1.3@6b853149eab67d4da22291d36f5b0631c0fd856e',
  'sebastian/global-state' => '2.0.0@e8ba02eed7bbbb9e59e43dedd3dddeff4a56b0c4',
  'sebastian/object-enumerator' => '3.0.4@e67f6d32ebd0c749cf9d1dbd9f226c727043cdf2',
  'sebastian/object-reflector' => '1.1.2@9b8772b9cbd456ab45d4a598d2dd1a1bced6363d',
  'sebastian/recursion-context' => '3.0.1@367dcba38d6e1977be014dc4b22f47a484dac7fb',
  'sebastian/resource-operations' => '2.0.2@31d35ca87926450c44eae7e2611d45a7a65ea8b3',
  'sebastian/version' => '2.0.1@99732be0ddb3361e16ad77b68ba41efc8e979019',
  'sensiolabs/security-checker' => 'v6.0.3@a576c01520d9761901f269c4934ba55448be4a54',
  'simplesamlphp/simplesamlphp-test-framework' => 'v0.1.2@f54a646a95f7b928d06a36d5f7f8303ac07f09b2',
  'squizlabs/php_codesniffer' => '3.6.0@ffced0d2c8fa8e6cdc4d695a743271fab6c38625',
  'symfony/http-client' => 'v5.3.8@c6370fe2c0a445aedc08f592a6a3149da1fea4c7',
  'symfony/mime' => 'v5.3.8@a756033d0a7e53db389618653ae991eba5a19a11',
  'symfony/polyfill-intl-idn' => 'v1.23.0@65bd267525e82759e7d8c4e8ceea44f398838e65',
  'symfony/polyfill-php72' => 'v1.23.0@9a142215a36a3888e30d0a9eeea9766764e96976',
  'theseer/tokenizer' => '1.2.1@34a41e998c2183e22995f158c581e7b5e755ab9e',
  'vimeo/psalm' => '3.18.2@19aa905f7c3c7350569999a93c40ae91ae4e1626',
  'webmozart/glob' => '4.3.0@06358fafde0f32edb4513f4fd88fe113a40c90ee',
  'webmozart/path-util' => '2.3.0@d939f7edc24c9a1bb9c0dee5cb05d8e859490725',
  'simplesamlphp/simplesamlphp' => 'v1.19.1@',
);

    private function __construct()
    {
    }

    /**
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function rootPackageName() : string
    {
        if (!self::composer2ApiUsable()) {
            return self::ROOT_PACKAGE_NAME;
        }

        return InstalledVersions::getRootPackage()['name'];
    }

    /**
     * @throws OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function getVersion(string $packageName): string
    {
        if (self::composer2ApiUsable()) {
            return InstalledVersions::getPrettyVersion($packageName)
                . '@' . InstalledVersions::getReference($packageName);
        }

        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }

        throw new OutOfBoundsException(
            'Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files'
        );
    }

    private static function composer2ApiUsable(): bool
    {
        if (!class_exists(InstalledVersions::class, false)) {
            return false;
        }

        if (method_exists(InstalledVersions::class, 'getAllRawData')) {
            $rawData = InstalledVersions::getAllRawData();
            if (count($rawData) === 1 && count($rawData[0]) === 0) {
                return false;
            }
        } else {
            $rawData = InstalledVersions::getRawData();
            if ($rawData === null || $rawData === []) {
                return false;
            }
        }

        return true;
    }
}