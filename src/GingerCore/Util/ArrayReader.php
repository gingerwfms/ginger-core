<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 08.02.14 - 23:21
 */

namespace GingerCore\Util;

/**
 * Class ArrayReader
 *
 * @package GingerCore\Util
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ArrayReader
{
    /**
     * @var array
     */
    private $originalArray = array();

    /**
     * @param array $anArray
     */
    public function __construct(array $anArray)
    {
        $this->originalArray = $anArray;
    }

    /**
     * @param string $aPath
     * @param int $default
     * @return int
     */
    public function integerValue($aPath, $default = 0)
    {
        $value = $this->getValueFromPath($aPath);

        if (is_null($value)) {
            return intval($default);
        }

        return intval($value);
    }

    /**
     * @param string $aPath
     * @param float $default
     * @return float
     */
    public function floatValue($aPath, $default = 0.0)
    {
        $value = $this->getValueFromPath($aPath);

        if (is_null($value)) {
            return floatval($default);
        }

        return floatval($value);
    }

    /**
     * @param string $aPath
     * @param bool $default
     * @return bool
     */
    public function booleanValue($aPath, $default = false)
    {
        $value = $this->getValueFromPath($aPath);

        if (is_null($value)) {
            return boolval($default);
        }

        return boolval($value);
    }

    /**
     * @param string $aPath
     * @param string $default
     * @return string
     */
    public function stringValue($aPath, $default = '')
    {
        $value = $this->getValueFromPath($aPath);

        if (is_null($value)) {
            return strval($default);
        }

        return strval($value);
    }

    /**
     * @param string $aPath
     * @param array $default
     * @return array
     */
    public function arrayValue($aPath, array $default = array())
    {
        $value = $this->getValueFromPath($aPath);

        if (is_null($value)) {
            return $default;
        }

        if (is_scalar($value)) {
            return array($value);
        }

        if (is_object($value)) {
            $value = json_decode(json_encode($value), true);
        }

        return $value;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->originalArray;
    }

    /**
     * @param string $aPath
     * @return array
     */
    private function toPathKeys($aPath)
    {
        return explode('.', $aPath);
    }

    /**
     * @param string $aPath
     * @return mixed
     */
    private function getValueFromPath($aPath)
    {
        $pathKeys = $this->toPathKeys($aPath);

        $arrayCopyOrValue = $this->originalArray;

        foreach($pathKeys as $pathKey) {

            if (!isset($arrayCopyOrValue[$pathKey])) {
                return null;
            }

            $arrayCopyOrValue = $arrayCopyOrValue[$pathKey];
        }

        return $arrayCopyOrValue;
    }
} 