<?php

declare(strict_types=1);

if (!function_exists('dump')) {
    function dump($object = "", $echo = true)
    {
        $output = "<pre style='background-color: #0c0c0c; color: #0bbd0b; padding:20px;'>" . print_r($object, true) . "</pre>";
        if ($echo) {
            echo $output;
            return;
        }
        return $output;
    }
}

if (!function_exists('dd')) {
    function dd($object = "")
    {
        dump($object);
        die;
    }
}

use PHPUnit\Framework\TestCase;

include __DIR__.'/../../../publisher-collective_mock_functions.php';
include __DIR__.'/../../../../assets/php/settings/PublisherCollectiveSettings.php';

/**
 * Class PublisherCollectiveSettingsTest
 * @runTestsInSeparateProcesses
 */
final class PublisherCollectiveSettingsTest extends TestCase
{
    public static function getProperty($object, $property)
    {
        $reflectedClass = new \ReflectionClass($object);
        $reflection = $reflectedClass->getProperty($property);
        $reflection->setAccessible(true);
        return $reflection->getValue($object);
    }

    public function testSuccessReceivedIfExtraParamsSent(): void
    {
        $_POST['pc-ads-txt-extra-params'] = 'some-value';
        $publisherCollectiveSettings = new PublisherCollectiveSettings();
        $this->getProperty($publisherCollectiveSettings, 'resultMessage');
        $publisherCollectiveSettings->handleSubmission();
        $resultMessage = $this->getProperty($publisherCollectiveSettings, 'resultMessage');
        $this->assertEquals($resultMessage['status'], 'success');
        $this->assertEquals($resultMessage['message'], 'Successfully undated');
    }

    public function testErrorReceivedIfExtraParamsEmpty(): void
    {
        $_POST['pc-ads-txt-extra-params'] = '';
        $publisherCollectiveSettings = new PublisherCollectiveSettings();
        $this->getProperty($publisherCollectiveSettings, 'resultMessage');
        $publisherCollectiveSettings->handleSubmission();
        $resultMessage = $this->getProperty($publisherCollectiveSettings, 'resultMessage');
        $this->assertEquals($resultMessage['status'], $publisherCollectiveSettings::RESULT_STATUS['ERRORED']);
        $this->assertEquals($resultMessage['message'], $publisherCollectiveSettings::RESULT_MESSAGES['ERRORED']);
    }

    public function testNULLReceivedIfExtraParamsNOTSent(): void
    {
        $publisherCollectiveSettings = new PublisherCollectiveSettings();
        $this->getProperty($publisherCollectiveSettings, 'resultMessage');
        $publisherCollectiveSettings->handleSubmission();
        $resultMessage = $this->getProperty($publisherCollectiveSettings, 'resultMessage');
        $this->assertEquals($resultMessage['status'], null);
        $this->assertEquals($resultMessage['message'], '');
    }
}