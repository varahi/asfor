<?php

namespace T3Dev\Trainingcaces\Domain\Model;

/***
 *
 * This file is part of the "Training Caces" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Dmitry Vasilev <dmitry@t3dev.ru>
 *
 ***/

class FrontendUserGroup extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup
{
    /**
     * @var int
     */
    protected $feloginRedirectPid;

    public function getFeloginRedirectPid(): int
    {
        return $this->feloginRedirectPid;
    }

    public function setFeloginRedirectPid(int $feloginRedirectPid)
    {
        $this->feloginRedirectPid = $feloginRedirectPid;
    }
}
