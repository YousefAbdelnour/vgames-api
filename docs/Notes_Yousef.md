# Build 3 Notes

Specifications for the api is under docs (yaml or json)

## Requirements For Build

1. All messages to the client are to be in the media type of the response (Json)
2. Response should have status, code, message (successful or failure), as much information as possible
3. Any input from the client should be validated
4. Error handling should be performed whenever we are dealing with inputs and operations performed on data
5. Must use HttpSpecializedException children (we make the children and we make them extend the class)
6. Custom error handler made by us could either catch exceptions thrown or checking the status code at the end of the response (anyway it will write the error to a log file for the purposes of the devs monitoring the errors and knowing what to improve on)
7. Computation functionality is subject to approval depending on the level of complexity
8. One Computation function per team member
9. needs to be a formula and calculated based on input in a post request from client
10. Always discuss what might go wrong in the code with other team members
11. Root resource should contain the info about the api (description, authors, optional operations, etc...). It should also return all the resources included in the web service

### Logging

#### What is it

Information about the request and the response

#### What to do

1. two log files
   1. Access log file
   2. Error log file
      1. It should contain Runtime exceptions
      2. Status Codes that do not make sense
   3. The log files should be ignored
   4. Log files should be in var/logs/
2. Log Helper class
   1. Methods for writing logs to the log files
3. Monolog class can be used for logging
   1. 
4. There should be a constant defined that points to /var/logs/
5. Create a middleware component that fetches the information to be logged
   1. Uses a helper class to log
   2. extract the method
   3. the resource uri
   4. Date and Time
   5. ip address of the Client
   6. queries and params
   7. headers __Optional__

### Composite Resource

#### Definition

Content fully or partially combined with different data sources

#### Requirements

1. One Composite Resource per team member
2. Use at least one third-party REST-based API
3. the data received from the api must be combined with a resource collection of our choice
4. Subject to approval
5. We will be reusing lab #5 (requirement #3)

## giant bomb api key

e328b284728c58cfc1628e2d0ac5618609217f58

## rawg api key

25fe58f136e544d9bd1247d6b312ac0f
