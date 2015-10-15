# Masonry Builder
Use Masonry to build/deploy.

## Example usage

This simple yaml file would build Masonry Builder to a temporary directory

```
- Delete:
    name: /tmp/Masonry-Builder

- MakeDirectory:
    name: /tmp/Masonry-Builder

- CloneRepository:
    repository: git@github.com:Visionmongers/Masonry-Builder.git
    directory: /tmp/Masonry-Builder

- Composer:
    command: install
    location: /tmp/Masonry-Builder
```

You can try it yourself by running

```
$ masonry --configuration example.yml.dist
```