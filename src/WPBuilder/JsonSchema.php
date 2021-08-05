<?php

namespace WPBuilder;

use Opis\JsonSchema\Schema;
use Opis\JsonSchema\Validator as SchemaValidator;
use JsonException;

/**
 * Class JsonSchema
 * @package WPReminder\api
 */
class JsonSchema
{

    /**
     * @var Schema
     */
    private Schema $schema;
    /**
     * @var string|null
     */
    private ?string $error;
    /**
     * @var array|null
     */
    protected ?array $result;

    /**
     * JsonSchema constructor.
     * @param string $filename
     * @param string $base
     * @throws BuilderException
     */
    public function __construct(string $filename = "", string $base = "") {
        if($base === "" && defined("WP_REMINDER_SCHEMAS")) {
            $base = WP_REMINDER_SCHEMAS;
        }

        $file = "$base$filename";
        if(!file_exists($file)) throw new BuilderException("File does not exist");
        if(!is_readable($file)) throw new BuilderException("Cannot read schema file");

        $content = file_get_contents($file);
        if($content === false) throw new BuilderException("Could not read schema file content");

        $this->schema = Schema::fromJsonString($content);
        $this->error = null;
        $this->result = null;
    }

    /**
     * @param string $content
     * @param bool $throw_on_error
     * @return $this
     * @throws BuilderException
     */
    public function validate(string $content, bool $throw_on_error = true) : self {
        try {
            $payload = json_decode($content, false, 512, JSON_THROW_ON_ERROR);

            $validator = new SchemaValidator();
            $result = $validator->schemaValidation($payload, $this->schema);

            if($result->isValid()) {
                $this->result = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
                return $this;
            }

            $this->error = $result->getFirstError()->keyword() . ": " . implode(", ", $result->getFirstError()->keywordArgs());
            if($throw_on_error) throw new BuilderException($this->error);
            return $this;
        } catch (JsonException $e) {
            throw new BuilderException($e->getMessage());
        }
    }

    /**
     * @return bool
     */
    public function has_error() : bool {
        return !is_null($this->error);
    }

    /**
     * @return string
     */
    public function get_error() : string {
        if(!$this->has_error()) return "";
        return $this->error;
    }

    /**
     * @return array
     */
    public function get_result() : array {
        return $this->result;
    }

}