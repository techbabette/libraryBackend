# [City Library](https://library.techbabette.com/) backend

Allows for full control over all dynamic content in the [City Library](https://library.techbabette.com).

A full description of application features can be found here [here](https://dszkzv3o6c2jj.cloudfront.net/documentation.pdf).

## Original deployment

Originally deployed on AWS with the architecture displayed below.

The terraform for this deployment can be found [here](https://github.com/techbabette/LibraryAWSDeployment)

![Original three layer deployment](https://i.imgur.com/UPA5tK6.png "Original deployment")

## Notable features

### Self-restoring database

The database refreshes every fifteen minutes, making any changes temporary and allowing for anyone to play around with the website.

The database restoration code can be found [here](https://github.com/techbabette/libraryBackend/blob/dev/database-loader/main.py)

### Terraform

The application and the database it uses can easily be deployed using the terraform definitions found [here](https://github.com/techbabette/libraryBackend/blob/dev/.tf.example)

Replace <production_env_source> with the source of the production environment you want to use

## Monitoring

[This playbook](https://github.com/techbabette/libraryBackend/blob/dev/playbooks/install_prometheus_exporter.yml) installs a prometheus node exporter and a [systemd service](https://github.com/techbabette/libraryBackend/blob/dev/playbooks/templates/nodeexporter.service) to control it.

The node exporter service starts as soon as it is installed and whenever the server it's on restarts.

The exported data is consumed by my [prometheus stack](https://github.com/techbabette/MonitoringStack), the resulting graph can be seen [here](https://monitor.techbabette.com/d/rYdddlPWk/node-exporter-full?orgId=1&refresh=1m).

### CI/CD

Every release is followed by a redeployment to a production hetzner server

### JWT Authentication

User authentication is accomplished using JWT tokens, allowing the application to scale horizontally (By adding more servers).

### Activity tracking

Important user activities are logged for the admins to search and preview.

The activities to be logged are defined [here](https://github.com/techbabette/libraryBackend/blob/dev/application/storage/json/routeMap.json), only the activities with a "text" field are logged.

### Models & Sorting

Every model has defined sorting options and a scoped sort method, easing potentially complicated sorts with joins and removing room for error.

Every index function, alongside the found records, also returns sort options that the [the frontend](https://github.com/techbabette/LibraryFrontend) can then integrate into itself and use.

Some index functions, given the appropriate query parameter, return only the sort options for the requested resource.
