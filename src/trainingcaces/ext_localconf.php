<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Trainingcaces',
            'Trainingcaces',
            [

                \T3Dev\Trainingcaces\Controller\ExamController::class => 'list, list, show, edit, update, delete, export',
                \T3Dev\Trainingcaces\Controller\FrontendUserController::class => 'show, updateExamsArray, downloadPdf, edit, update, rotateUserImage',

            ],
            // non-cacheable actions
            [

                \T3Dev\Trainingcaces\Controller\ExamController::class => 'list, update, delete, export',
                \T3Dev\Trainingcaces\Controller\FrontendUserController::class => 'show, updateExamsArray, downloadPdf, edit, update, rotateUserImage',

            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Trainingcaces',
            'TrainingcacesApi',
            [
                \T3Dev\Trainingcaces\Controller\JsonController::class => 'auth, testers, students, list, editAjax, show, editUser, updateUser',
            ],
            // non-cacheable actions
            [
                \T3Dev\Trainingcaces\Controller\JsonController::class => 'auth, testers, students, list, editAjax, show, editUser, updateUser',
            ]
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'mod {
                wizards.newContentElement.wizardItems.plugins {
                    elements {
                        trainingcaces {
                            iconIdentifier = trainingcaces-plugin-trainingcaces
                            title = LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_trainingcaces.name
                            description = LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_trainingcaces.description
                            tt_content_defValues {
                                CType = list
                                list_type = trainingcaces_trainingcaces
                            }
                        }
                    }
                    show = *
                }
           }'
        );

        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

        $iconRegistry->registerIcon(
            'trainingcaces-plugin-trainingcaces',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:trainingcaces/Resources/Public/Icons/user_plugin_trainingcaces.svg']
        );
    }
);
