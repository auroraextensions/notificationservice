<?php
/**
 * XmlReader.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/notificationoutbox/LICENSE.txt
 *
 * @package       AuroraExtensions_NotificationOutbox
 * @copyright     Copyright (C) 2020 Aurora Extensions <support@auroraextensions.com>
 * @license       MIT License
 */
declare(strict_types=1);

namespace AuroraExtensions\NotificationOutbox\Model\Config;

use AuroraExtensions\NotificationOutbox\Exception\ExceptionFactory;
use Magento\Framework\{
    Config\ConverterInterface,
    Config\Dom,
    Config\DomFactory,
    Config\Dom\ValidationException,
    Config\FileIterator,
    Config\FileResolverInterface,
    Config\ReaderInterface,
    Config\Reader\Filesystem,
    Config\SchemaLocatorInterface,
    Config\ValidationStateInterface,
    Exception\LocalizedException
};

class XmlReader implements ReaderInterface
{
    /** @constant string ERROR_INVALID_XML_FILE */
    public const ERROR_INVALID_XML_FILE = "The XML in file '%1' is invalid:\n%2\nVerify the XML and try again.";

    /** @constant string XML_FILE */
    public const XML_FILE = 'notifications.xml';

    /** @property ConverterInterface $converter */
    protected $converter;

    /** @property string $defaultScope */
    protected $defaultScope;

    /** @property string $domFactory */
    protected $domFactory;

    /** @property ExceptionFactory $exceptionFactory */
    protected $exceptionFactory;

    /** @property string $fileName */
    protected $fileName;

    /** @property FileResolverInterface $fileResolver */
    protected $fileResolver;

    /** @property array $idAttributes */
    protected $idAttributes = [];

    /** @property SchemaLocatorInterface $schemaLocator */
    protected $schemaLocator;

    /** @property ValidationStateInterface $validationState */
    protected $validationState;

    /**
     * @param ConverterInterface $converter
     * @param DomFactory $domFactory
     * @param ExceptionFactory $exceptionFactory
     * @param FileResolverInterface $fileResolver
     * @param SchemaLocatorInterface $schemaLocator
     * @param ValidationStateInterface $validationState
     * @param string $fileName
     * @param array $idAttributes
     * @param string $domFactory
     * @param string $defaultScope
     * @return void
     */
    public function __construct(
        ConverterInterface $converter,
        DomFactory $domFactory,
        ExceptionFactory $exceptionFactory,
        FileResolverInterface $fileResolver,
        SchemaLocatorInterface $schemaLocator,
        ValidationStateInterface $validationState,
        string $fileName = self::XML_FILE,
        array $idAttributes = [],
        string $defaultScope = 'global'
    ) {
        $this->converter = $converter;
        $this->domFactory = $domFactory;
        $this->exceptionFactory = $exceptionFactory;
        $this->fileResolver = $fileResolver;
        $this->schemaLocator = $schemaLocator;
        $this->validationState = $validationState;
        $this->fileName = $fileName;
        $this->idAttributes = $idAttributes;
        $this->defaultScope = $defaultScope;
    }

    /**
     * {@inheritdoc}
     */
    public function read($scope = null)
    {
        /** @var FileIterator|array $files */
        $files = $this->fileResolver->get(
            $this->fileName,
            $scope ?? $this->defaultScope
        );

        if ($files instanceof FileIterator) {
            $files = $files->toArray();
        }

        if (!count($files)) {
            return [];
        }

        return $this->readFiles($files);
    }

    /**
     * @param array $files
     * @return array
     */
    private function readFiles(array $files = []): array
    {
        /** @var string $fileXml */
        $fileXml = array_shift($files);

        /** @var Dom $xmlMerger */
        $xmlMerger = $this->getXmlMerger($fileXml);

        /** @var string $key */
        /** @var string $xml */
        foreach ($files as $key => $xml) {
            try {
                $xmlMerger->merge($xml);
            } catch (ValidationException | LocalizedException $e) {
                /** @var LocalizedException $exception */
                $exception = $this->exceptionFactory->create(
                    LocalizedException::class,
                    __(
                        static::ERROR_INVALID_XML_FILE,
                        $key,
                        $e->getMessage()
                    )
                );

                throw $exception;
            }
        }

        if ($this->isValidationRequired()) {
            /** @var array $errors */
            $errors = [];

            if (!$xmlMerger->validate($this->getSchemaFile(), $errors)) {
                /** @var string $message */
                $message = "Invalid Document\n" . implode("\n", $errors);

                /** @var LocalizedException $exception */
                $exception = $this->exceptionFactory->create(
                    LocalizedException::class,
                    __($message)
                );

                throw $exception;
            }
        }

        return $this->converter
            ->convert($xmlMerger->getDom());
    }

    /**
     * @param string $xml
     * @return Dom
     */
    private function getXmlMerger(string $xml): Dom
    {
        return $this->domFactory->createDom([
            'xml' => $xml,
            'validationState' => $this->validationState,
            'idAttributes' => $this->idAttributes,
            'typeAttributeName' => null,
            'schemaFile' => $this->getPerFileSchema(),
        ]);
    }

    /**
     * @return bool
     */
    private function isValidationRequired(): bool
    {
        return (bool) $this->validationState
            ->isValidationRequired();
    }

    /**
     * @return string|null
     */
    private function getSchemaFile(): ?string
    {
        return $this->schemaLocator
            ->getSchema();
    }

    /**
     * @return string|null
     */
    private function getPerFileSchema(): ?string
    {
        /** @var string|null $perFileSchema */
        $perFileSchema = $this->schemaLocator
            ->getPerFileSchema();

        /** @var bool $isValidationRequired */
        $isValidationRequired = $this->isValidationRequired();

        return ($perFileSchema && $isValidationRequired) ? $perFileSchema : null;
    }
}
