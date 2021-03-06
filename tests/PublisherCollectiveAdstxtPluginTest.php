<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

include __DIR__.'/publisher-collective_mock_functions.php';
include __DIR__.'/../publisher-collective.php';

/**
 * Class PublisherCollectiveAdstxtPluginTest.
 * @runTestsInSeparateProcesses
 */
final class PublisherCollectiveAdstxtPluginTest extends TestCase
{
    private const ADS_TXT_SAMPLE_DOMAIN = 'pcgamesn.com';

    public function testDomainIsCreatedCorrectlyFromGetOption(): void
    {
        $this->globalMockFunctions();
        $result = PublisherCollective::get_ads_txt_content_or_cache();
        $this->assertEquals($result, PublisherCollective::ADS_TXT_URL_PREFIX.self::ADS_TXT_SAMPLE_DOMAIN);
    }

    public function testDomainIsCreatedWhenUsingSERVER_NAME(): void
    {
        $_SERVER['SERVER_NAME'] = self::ADS_TXT_SAMPLE_DOMAIN;
        $this->globalMockFunctions();
        $result = PublisherCollective::get_ads_txt_content_or_cache();
        $this->assertEquals($result, PublisherCollective::ADS_TXT_URL_PREFIX.self::ADS_TXT_SAMPLE_DOMAIN);
    }

    public function testDomainIsCreatedWhenUsingHTTP_HOST(): void
    {
        $_SERVER['HTTP_HOST'] = self::ADS_TXT_SAMPLE_DOMAIN;
        $this->globalMockFunctions();
        $result = PublisherCollective::get_ads_txt_content_or_cache();
        $this->assertEquals($result, PublisherCollective::ADS_TXT_URL_PREFIX.self::ADS_TXT_SAMPLE_DOMAIN);
    }

    private function globalMockFunctions()
    {
        function wp_remote_get($input)
        {
            return $input;
        }

        function wp_remote_retrieve_body($input)
        {
            return $input;
        }

        function set_transient()
        {
        }

        function get_transient()
        {
        }

        function get_home_url(): string
        {
            return 'https://www.pcgamesn.com/';
        }

        if (! function_exists('get_option')) {
            function get_option(): string
            {
                return '';
            }
        }
    }
}
