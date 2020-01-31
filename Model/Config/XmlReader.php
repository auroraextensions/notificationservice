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
 * https://docs.auroraextensions.com/magento/extensions/2.x/notificationservice/LICENSE.txt
 *
 * @package       AuroraExtensions_NotificationService
 * @copyright     Copyright (C) 2020 Aurora Extensions <support@auroraextensions.com>
 * @license       MIT License
 */
declare(strict_types=1);

namespace AuroraExtensions\NotificationService\Model\Config;

use DOMDocument;
use AuroraExtensions\NotificationService\{
    Exception\ExceptionFactory,
    Csi\Config\Document\XmlDocumentInterface,
    Csi\Config\Document\XmlDocumentInterfaceFactory
};
use Magento\Framework\{
    Config\ConverterInterface,
    Config\Dom,
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

    /** @property XmlDocumentInterfaceFactory $documentFactory */
    protected $documentFactory;

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
     * @param XmlDocumentInterfaceFactory $documentFactory
     * @param ExceptionFactory $exceptionFactory
     * @param FileResolverInterface $fileResolver
     * @param SchemaLocatorInterface $schemaLocator
     * @param ValidationStateInterface $validationState
     * @param string $fileName
     * @param array $idAttributes
     * @param string $documentFactory
     * @param string $defaultScope
     * @return void
     */
    public function __construct(
        ConverterInterface $converter,
        XmlDocumentInterfaceFactory $documentFactory,
        ExceptionFactory $exceptionFactory,
        FileResolverInterface $fileResolver,
        SchemaLocatorInterface $schemaLocator,
        ValidationStateInterface $validationState,
        string $fileName = self::XML_FILE,
        array $idAttributes = [],
        string $defaultScope = 'global'
    ) {
        $this->converter = $converter;
        $this->documentFactory = $documentFactory;
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
        /** @var DOMDocument $sourceXml */
        $sourceXml = $this->documentFactory->create();

        /** @var string $key */
        /** @var string $xml */
        foreach ($files as $key => $xml) {
            try {
                /** @var XmlDocument $moduleXml */
                $moduleXml = $this->documentFactory->create([
                    'xml' => $xml,
                ]);

                /** @var DOMElement[] $xmlNodes */
                $xmlNodes = $moduleXml->getChildNodesByTagName(
                    $moduleXml->getDocumentElement(),
                    'releases'
                );

                if (!empty($xmlNodes)) {
                    /** @var DOMElement $xmlNode */
                    $xmlNode = $xmlNodes[0];

                    $sourceXml->appendNode(
                        $sourceXml->importNode($xmlNode)
                    );
                }
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

        /** @var DOMDocument $document */
        $document = $sourceXml->getDocument();

        /** @var array $errors */
        $errors = [];

        if (!$this->validate($document, $errors)) {
            /** @var string $message */
            $message = "Invalid Document\n" . implode("\n", $errors);

            /** @var LocalizedException $exception */
            $exception = $this->exceptionFactory->create(
                LocalizedException::class,
                __($message)
            );

            throw $exception;
        }

        return $this->converter
            ->convert($document);
    }

    /**
     * @param DOMDocument $document
     * @param array $errors
     * @return bool
     */
    private function validate(
        DOMDocument $document,
        array &$errors = []
    ): bool
    {
        if ($this->isValidationRequired()) {
            /** @var array $errors */
            $errors = Dom::validateDomDocument(
                $document,
                $this->getSchemaFile()
            );

            return !count($errors);
        }

        return true;
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
