<?php

namespace T3Dev\Trainingcaces\Interfaces;

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

/**
 * Interface to be implemented by every frontend user
 * model that should be used with this registration
 */
interface FrontendUserInterface
{
    /**
     * Returns the username value
     *
     * @return string
     */
    public function getUsername();

    /**
     * Returns the firstName value
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Returns the lastName value
     *
     * @return string
     */
    public function getLastName();

    /**
     * Returns the email value
     *
     * @return string
     */
    public function getEmail();
}
