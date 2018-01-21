<?php

namespace Swaggest\JsonSchema;

class Context extends MagicMap
{
    public $import = true;

    /** @var DataPreProcessor */
    public $dataPreProcessor;

    /** @var RefResolver */
    public $refResolver;

    /** @var RemoteRefProvider|null */
    public $remoteRefProvider;

    /** @var string */
    public $propagateObjectItemClass;

    /** @var \SplObjectStorage */
    public $circularReferences;

    /** @var bool */
    public $skipValidation = false;

    /** @var string[] map of from -> to class names */
    public $objectItemClassMapping;

    /** @var bool allow soft cast from to/strings */
    public $tolerateStrings = false;

    /** @var bool do not tolerate special symbols even if base64_decode accepts string */
    public $strictBase64Validation = false;

    public $unpackContentMediaType = true;

    /** @var string property mapping set name */
    public $mapping = Schema::DEFAULT_MAPPING;

    public $version = Schema::VERSION_AUTO;

    /**
     * @param boolean $skipValidation
     * @return Context
     */
    public function setSkipValidation($skipValidation = true)
    {
        $this->skipValidation = $skipValidation;
        return $this;
    }


    /**
     * ProcessingOptions constructor.
     * @param RemoteRefProvider $remoteRefProvider
     */
    public function __construct(RemoteRefProvider $remoteRefProvider = null)
    {
        $this->remoteRefProvider = $remoteRefProvider;
    }

    /**
     * @return DataPreProcessor
     */
    public function getDataPreProcessor()
    {
        return $this->dataPreProcessor;
    }

    /**
     * @param DataPreProcessor $dataPreProcessor
     * @return Context
     */
    public function setDataPreProcessor($dataPreProcessor)
    {
        $this->dataPreProcessor = $dataPreProcessor;
        return $this;
    }

    /**
     * @return RemoteRefProvider|null
     */
    public function getRemoteRefProvider()
    {
        return $this->remoteRefProvider;
    }

    /**
     * @param RemoteRefProvider $remoteRefProvider
     * @return Context
     */
    public function setRemoteRefProvider($remoteRefProvider)
    {
        $this->remoteRefProvider = $remoteRefProvider;
        return $this;
    }


}