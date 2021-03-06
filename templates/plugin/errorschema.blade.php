{
    "$schema": "http://json-schema.org/draft-07/schema",
    "type": "object",
    "properties": {
        "status": {
            "type": "string",
            "enum": ["error"]
        },
        "message": {
            "type": "string"
        },
        "debug": {
            "type": "string"
        }
    },
    "required": ["status", "message"],
    "additionalProperties": false,
    "examples": [
        {
            "status": "error",
            "message": "internal server error",
            "debug": "invalid parameter"
        }
    ]
}