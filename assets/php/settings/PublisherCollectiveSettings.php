<?php

declare(strict_types=1);

/**
 * Class PublisherCollectiveSettings.
 */
class PublisherCollectiveSettings
{
    public const PAGE_NAME = 'publisher-collective-adstxt-settings';

    public const PAGE_TITLE = 'Publisher Collective ads.txt settings';

    public const RESULT_STATUS = [
        'ERRORED' => 'error',
        'SUCCESS' => 'success',
        'WARNING' => 'warning',
        'INFO' => 'info',
    ];

    public const RESULT_MESSAGES = [
        'ERRORED' => 'Additional line items should not be blank',
        'SUCCESS' => 'Successfully undated',
        'WARNING' => 'warning',
        'INFO' => 'info',
    ];

    private array $resultMessage = [
        'status' => null,
        'message' => '',
    ];

    public function init(): void
    {
        add_action('admin_menu', [$this, 'initiateAdminMenu']);
        add_action('admin_init', [$this, 'initiateAdminFields']);
        $this->handleSubmission();
    }

    public function initiateAdminMenu(): void
    {
        add_menu_page(
            self::PAGE_NAME,
            'Ads txt',
            'administrator',
            self::PAGE_NAME,
            [$this, 'renderAdsTxtSettingsPageMenu'],
            'dashicons-admin-settings',
            6
        );
    }

    public function initiateAdminFields(): void
    {
        add_settings_error(
            'pub_col_settings_error_field_key',
            'pub_col_settings_error_field',
            __($this->resultMessage['message'], 'wpse'),
            $this->resultMessage['status']
        );

        register_setting(
            self::PAGE_NAME,
            'pub_col_settings_error_field_key'
        );

        add_settings_section(
            // ID used to identify this section and with which to register options
            'pub_col_settings_error_section',
            // Title to be displayed on the administration page
            '',
            // Callback used to render the description of the section
            [$this, 'renderErrorMessages'],
            // Page on which to add this section of options
            self::PAGE_NAME
        );

        add_settings_section(
        // ID used to identify this section and with which to register options
            'additional_line_items_section',
            // Title to be displayed on the administration page
            self::PAGE_TITLE,
            // Callback used to render the description of the section
            [$this, 'featuresSectionDescription'],
            // Page on which to add this section of options
            self::PAGE_NAME
        );

        add_settings_field(
            'additional_line_items_field',
            'Additional line items',
            [$this, 'addThemeBodyFunction'],
            self::PAGE_NAME,
            'additional_line_items_section'
        );

        register_setting(
            self::PAGE_NAME,
            'additional_line_items_field'
        );
    }

    public function renderAdsTxtSettingsPageMenu(): void
    {
        settings_fields(self::PAGE_NAME);
        do_settings_sections(self::PAGE_NAME);
    }

    public function featuresSectionDescription(): void
    {
        echo 'Edit settings on your ads txt plugin';
    }

    public function renderErrorMessages(): void
    {
        if (! empty($this->resultMessage['status'])) {
            include PUB_COL_PLUGIN_DIR.'assets/templates/additional-line-items-error-messages.php';
        }
    }

    public function addThemeBodyFunction(): void
    {
        include PUB_COL_PLUGIN_DIR.'assets/templates/additional-line-items-form.php';
    }

    public function handleSubmission(): void
    {
        if (isset($_POST['pc-ads-txt-extra-params'])) {
            $adsTxtExtraParams = $_POST['pc-ads-txt-extra-params'];
            update_option('pc-ads-txt-extra-params', $adsTxtExtraParams);
            $this->resultMessage['status'] = self::RESULT_STATUS['SUCCESS'];
            $this->resultMessage['message'] = self::RESULT_MESSAGES['SUCCESS'];
        }
    }
}
