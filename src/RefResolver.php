<?php

namespace Swaggest\JsonSchema;

use PhpLang\ScopeExit;
use Swaggest\JsonSchema\Constraint\Ref;
use Swaggest\JsonSchema\RemoteRef\BasicFetcher;

class RefResolver
{
    public $resolutionScope = '';
    public $url;
    /** @var RefResolver */
    private $rootResolver;

    /**
     * @param mixed $resolutionScope
     * @return string previous value
     */
    public function setResolutionScope($resolutionScope)
    {
        $rootResolver = $this->rootResolver ? $this->rootResolver : $this;
        if ($resolutionScope === $rootResolver->resolutionScope) {
            return $resolutionScope;
        }
        $prev = $rootResolver->resolutionScope;
        $rootResolver->resolutionScope = $resolutionScope;
        return $prev;
    }

    /**
     * @return string
     */
    public function getResolutionScope()
    {
        $rootResolver = $this->rootResolver ? $this->rootResolver : $this;
        return $rootResolver->resolutionScope;
    }


    public function updateResolutionScope($id)
    {
        $id = rtrim($id, '#');
        $rootResolver = $this->rootResolver ? $this->rootResolver : $this;
        if (strpos($id, '://') !== false) {
            $prev = $rootResolver->setResolutionScope($id);
        } else {
            $prev = $rootResolver->setResolutionScope(Helper::resolveURI($rootResolver->resolutionScope, $id));
        }

        return $prev;
    }

    public function setupResolutionScope($id, $data)
    {
        $rootResolver = $this->rootResolver ? $this->rootResolver : $this;

        $prev = $rootResolver->updateResolutionScope($id);

        $refParts = explode('#', $rootResolver->resolutionScope, 2);

        if ($refParts[0]) { // external uri
            $resolver = &$rootResolver->remoteRefResolvers[$refParts[0]];
            if ($resolver === null) {
                $resolver = new RefResolver();
                $resolver->rootResolver = $rootResolver;
                $resolver->url = $refParts[0];
                $this->remoteRefResolvers[$refParts[0]] = $resolver;
            }
        } else { // local uri
            $resolver = $this;
        }

        if (empty($refParts[1])) {
            $resolver->rootData = $data;
        } else {
            $refPath = '#' . $refParts[1];
            $resolver->refs[$refPath] = new Ref($refPath, $data);
        }

        return $prev;
    }

    private $rootData;

    /** @var Ref[] */
    private $refs = array();

    /** @var RefResolver[]|null[] */
    private $remoteRefResolvers = array();

    /** @var RemoteRefProvider */
    private $refProvider;

    /**
     * RefResolver constructor.
     * @param JsonSchema $rootData
     */
    public function __construct($rootData = null)
    {
        $this->rootData = $rootData;
    }

    public function setRootData($rootData)
    {
        $this->rootData = $rootData;
        return $this;
    }


    public function setRemoteRefProvider(RemoteRefProvider $provider)
    {
        $this->refProvider = $provider;
        return $this;
    }

    private function getRefProvider()
    {
        if (null === $this->refProvider) {
            $this->refProvider = new BasicFetcher();
        }
        return $this->refProvider;
    }

    /**
     * @param string $referencePath
     * @return Ref
     * @throws InvalidValue
     */
    public function resolveReference($referencePath)
    {
        if ($this->resolutionScope) {
            $referencePath = Helper::resolveURI($this->resolutionScope, $referencePath);
        }

        $refParts = explode('#', $referencePath, 2);
        $url = rtrim($refParts[0], '#');
        $refLocalPath = isset($refParts[1]) ? '#' . $refParts[1] : '#';

        if ($url === $this->url) {
            $referencePath = $refLocalPath;
        }

        /** @var null|Ref $ref */
        $ref = &$this->refs[$referencePath];

        $refResolver = $this;

        if (null === $ref) {
            if ($referencePath[0] === '#') {
                if ($referencePath === '#') {
                    $ref = new Ref($referencePath, $refResolver->rootData);
                } else {
                    $ref = new Ref($referencePath);
                    $path = explode('/', trim($referencePath, '#/'));

                    /** @var JsonSchema $branch */
                    $branch = &$refResolver->rootData;
                    while (!empty($path)) {
                        if (isset($branch->{Schema::ID_D4}) && is_string($branch->{Schema::ID_D4})) {
                            $refResolver->updateResolutionScope($branch->{Schema::ID_D4});
                        }
                        if (isset($branch->{Schema::ID}) && is_string($branch->{Schema::ID})) {
                            $refResolver->updateResolutionScope($branch->{Schema::ID});
                        }

                        $folder = array_shift($path);

                        // unescaping special characters
                        // https://tools.ietf.org/html/draft-ietf-appsawg-json-pointer-07#section-4
                        // https://github.com/json-schema-org/JSON-Schema-Test-Suite/issues/130
                        $folder = str_replace(array('~0', '~1', '%25'), array('~', '/', '%'), $folder);

                        if ($branch instanceof \stdClass && isset($branch->$folder)) {
                            $branch = &$branch->$folder;
                        } elseif (is_array($branch) && isset($branch[$folder])) {
                            $branch = &$branch[$folder];
                        } else {
                            throw new InvalidValue('Could not resolve ' . $referencePath . '@' . $this->getResolutionScope() . ': ' . $folder);
                        }
                    }
                    $ref->setData($branch);
                }
            } else {
                if ($url !== $this->url) {
                    $rootResolver = $this->rootResolver ? $this->rootResolver : $this;
                    /** @var null|RefResolver $refResolver */
                    $refResolver = &$rootResolver->remoteRefResolvers[$url];
                    $this->setResolutionScope($url);
                    if (null === $refResolver) {
                        $rootData = $rootResolver->getRefProvider()->getSchemaData($url);
                        $refResolver = new RefResolver($rootData);
                        $refResolver->rootResolver = $rootResolver;
                        $refResolver->refProvider = $this->refProvider;
                        $refResolver->url = $url;
                        $rootResolver->setResolutionScope($url);
                    }
                }

                $ref = $refResolver->resolveReference($refLocalPath);
            }
        }

        return $ref;
    }


    /**
     * @param $data
     * @param Context $options
     * @param int $nestingLevel
     * @throws Exception
     */
    public function preProcessReferences($data, Context $options, $nestingLevel = 0)
    {
        if ($nestingLevel > 200) {
            throw new Exception('Too deep nesting level', Exception::DEEP_NESTING);
        }
        if (is_array($data)) {
            foreach ($data as $key => $item) {
                $this->preProcessReferences($item, $options, $nestingLevel + 1);
            }
        } elseif ($data instanceof \stdClass) {
            /** @var JsonSchema $data */
            if (
                isset($data->{Schema::ID_D4})
                && is_string($data->{Schema::ID_D4})
                && (($options->version === Schema::VERSION_AUTO) || $options->version === Schema::VERSION_DRAFT_04)
            ) {
                $prev = $this->setupResolutionScope($data->{Schema::ID_D4}, $data);
                /** @noinspection PhpUnusedLocalVariableInspection */
                $_ = new ScopeExit(function () use ($prev, $options) {
                    $this->setResolutionScope($prev);
                });
            }

            if (isset($data->{Schema::ID})
                && is_string($data->{Schema::ID})
                && (($options->version === Schema::VERSION_AUTO) || $options->version >= Schema::VERSION_DRAFT_06)
            ) {
                $prev = $this->setupResolutionScope($data->{Schema::ID}, $data);
                /** @noinspection PhpUnusedLocalVariableInspection */
                $_ = new ScopeExit(function () use ($prev, $options) {
                    $this->setResolutionScope($prev);
                });
            }


            foreach ((array)$data as $key => $value) {
                $this->preProcessReferences($value, $options, $nestingLevel + 1);
            }
        }
    }


}