<?php

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        // Add fields to rootLineFields
        $GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] .= '';

        // Hook for adding realurl custom configuration
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration'][$extKey] =
            'JosefGlatz\\Theme\\Hooks\\Frontend\\Realurl\\RealUrlAutoConfiguration->addThemeConfig';

        // Disable ext:news realurl hook
//        unset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration']['news']);

        // Add general UserTSConfig
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE: EXT:' . $extKey . '/Configuration/TSConfig/UserGeneral.tsc">'
        );

        if (TYPO3_MODE === 'BE') {
            // Add custom cache action item: delete realurl configuration file
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'][$extKey] = \JosefGlatz\Theme\Hooks\Backend\Toolbar\ClearRealurlAutoConfMenuItem::class;
        }

        // Add EXT:solr CommandController support for older versions
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('solr') && !class_exists(\ApacheSolrForTypo3\Solr\Command\SolrCommandController::class)) {
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = \JosefGlatz\Theme\Command\SolrCommandController::class;
        }
    },
    $_EXTKEY
);
