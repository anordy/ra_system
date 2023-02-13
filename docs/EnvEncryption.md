# Encrypting Environment Variables.
Environment variables are a crucial part of any software application, as they affect the way the application runs. They contain different parameters and configurations such as database, email, SMS, queue connections, etc., which are required for the application to function properly.

Due to the fact that environment variables contain sensitive information that is of interest to attackers, we have implemented an encryption mechanism to ensure that the credentials are not compromised in case the file is obtained by an attacker. In this way, the attacker will not be able to access the plain credentials.

