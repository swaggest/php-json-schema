<?php

namespace Swaggest\JsonSchema;

class ProcessingOptions extends MagicMap
{


    public $import = true;
    /** @var DataPreProcessor */
    public $dataPreProcessor;
    /** @var RefResolver */
    public $refResolver;

    /** @var RemoteRefProvider */
    public $remoteRefProvider;

    /** @var string */
    public $propagateObjectItemClass;

    /** @var \SplObjectStorage */
    public $circularReferences;

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
     * @return ProcessingOptions
     */
    public function setDataPreProcessor($dataPreProcessor)
    {
        $this->dataPreProcessor = $dataPreProcessor;
        return $this;
    }

    /**
     * @return RemoteRefProvider
     */
    public function getRemoteRefProvider()
    {
        return $this->remoteRefProvider;
    }

    /**
     * @param RemoteRefProvider $remoteRefProvider
     * @return ProcessingOptions
     */
    public function setRemoteRefProvider($remoteRefProvider)
    {
        $this->remoteRefProvider = $remoteRefProvider;
        return $this;
    }


}