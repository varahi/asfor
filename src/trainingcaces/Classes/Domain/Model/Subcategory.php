<?php
declare(strict_types=1);

namespace T3Dev\Trainingcaces\Domain\Model;

/**
 *
 * This file is part of the "Test" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021
 */

/**
 * Subcategory
 */
class Subcategory extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * name
     *
     * @var string
     */
    protected $name = '';

    /**
     * shortName
     *
     * @var string
     */
    protected $shortName = '';

    /**
     * uniqueName
     *
     * @var string
     */
    protected $uniqueName = '';

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the shortName
     *
     * @return string $shortName
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Sets the shortName
     *
     * @param string $shortName
     * @return void
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * Returns the uniqueName
     *
     * @return string $uniqueName
     */
    public function getUniqueName()
    {
        return $this->uniqueName;
    }

    /**
     * Sets the uniqueName
     *
     * @param string $uniqueName
     * @return void
     */
    public function setUniqueName($uniqueName)
    {
        $this->uniqueName = $uniqueName;
    }
}
