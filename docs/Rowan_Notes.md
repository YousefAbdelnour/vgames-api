# Build 3 

1. Interaection between client and webservice must ne represented by JSON. 
2. All messages exchanged must be encoded in JSON including exceptions  
3. Should contain field called status, success, or error 
4. Error handling must be performed for every user input 
5. Use HTTPSpecializedExceptions for all children 
6. Custom error handler with logs
   
## Resource Instructions 

7. One composite resource per team member, one third party REST based API of your choice 
8. Must be approved by teacher 

Guzzle Library needs to be used 

Go back to lab #5 -> in that document when we didn't implement #3, every team member must implement the helper task that allows you to talk to the remote API

Expose multiple public methods 

 
### What to do: 

Maintain two files: 

Have two log files:
1. Storing access information 
2. Errors, runtime exceptions 

Use Streamhandler

Use a helper class to log
