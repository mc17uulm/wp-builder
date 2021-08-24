{
    "name": "{{ $namespace }}",
    "slug": "{{ $slug }}",
    "version": "0.1.0",
    "type": "plugin",
    "author": {
        "name": "{{ $author_name }}",
        "email": "{{ $author_email }}"
    },
    "build": {
        "includes": [
            "{{ $slug }}.php"
        ]
    }
}
