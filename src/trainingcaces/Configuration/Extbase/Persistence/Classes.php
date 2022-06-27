<?php
declare(strict_types = 1);

return [
    \T3Dev\Trainingcaces\Domain\Model\FrontendUser::class => [
        'tableName' => 'fe_users',
    ],
    \T3Dev\Trainingcaces\Domain\Model\FrontendUserGroup::class => [
        'tableName' => 'fe_groups',
        'properties' => [
            'feloginRedirectPid' => [
                'fieldName' => 'felogin_redirectPid'
            ],
        ],
    ],
    \T3Dev\Trainingcaces\Domain\Model\FileReference::class => [
        'tableName' => 'sys_file_reference',
    ]
];
