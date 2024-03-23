# [City Library](https://dszkzv3o6c2jj.cloudfront.net) backend

Allows for full control over all dynamic content in the [City Library](https://dszkzv3o6c2jj.cloudfront.net).

A full description of application features can be found here [here](https://dszkzv3o6c2jj.cloudfront.net/documentation.pdf).

## Notable features

### Self-restoring database

The database refreshes every fifteen minutes, making any changes temporary and allowing for anyone to play around with the website.

The database restoration code can be found [here](https://github.com/techbabette/LibraryDatabaseRestorer)

### JWT Authentication

User authentication is accomplished using JWT tokens, allowing the application to scale horizontally (Adding more servers).

### Activity tracking

Important user activities are logged for the admins to search and preview.

The activities to be logged are defined [here](https://github.com/techbabette/libraryBackend/blob/dev/application/storage/json/routeMap.json), only the activities with a "text" field are logged.

### Models & Sorting

Every model has defined sorting options and a scoped sort method, easing potentially complicated sorts with joins and removing room for error.

Every index function, alongside the found records, also returns sort options that the [the frontend](https://github.com/techbabette/LibraryFrontend) can then integrate into itself and use.

Some index functions, given the appropriate query parameter, return only the sort options for the requested resource.
