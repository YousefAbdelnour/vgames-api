## Notes Build #3

Move sql file from root folder under the data folder

# 1) General req

All representations encoded in JSON, including exceptions.

Responses should be well structured. Should have status, status code, the more info the better.

# 2) Data validation

Validate all inputs.

# 3) Error handling

Use HttpSpecializedException or Result pattern
Inform clients about invalid input and unsupported operations on resources.

# 4) Root resource: /

Expose a root resource / that returns information about the API (description, authors...). Should return all the resources exposed by the web service, with full URI.

# 5) Composite resource

Each team member must be responsible for implementing a composite resource. Min one third-party REST-based API to aggregate the returned data in a meaningful way. Should combine data from the remote API and own database of our web service.

Guzzle library, lab 5

# 6) Computation Functionality/Remote Processing

Each member must implement at least one value-returning computation functionality using a specific formula.

Must require data, passed as input body in POST req

Ex: computations related to finance, business, health, etc...

# 8) Logging

Logging functionality to log info about requests and relevant events into log files and db table.

## a) Logging to files

    - Maintain two log file: access.log and error.log
    - Designated folder: ex /var/logs, defined in constants.php
    - Use Monolog PHP library
    - Add to .gitignore
    - LogHelper class with public static methods to write log msgs to specific files.
    - Log failures from auth
    - Create middleware component to handle logging of requests.
    - Use log levels (view Monolog)
    - Log msgs should contain information about request (date, uri,  method, IP, etc...)
    -  Implemented as a team

Custom error handling section:
https://www.slimframework.com/docs/v4/middleware/error-handling.html

# For next week

For next week:

- Find and test third-party REST-based API.
- Computation functionality
