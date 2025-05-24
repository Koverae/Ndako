# Debugging Errors

If you encounter errors while setting up Ndako on-premise, you can track them.
You should enable the debugging errors variable to see the details of errors.

The debug is disabled by default as it is designed to be used only in the development environment. It can slow the application down (because it has to gather data).

To enable Debug, set true the APP_DEBUG variable in the .env file, located in the root directory of your Ndako installation.

![Env](/screenshots/ndako-install-debug.png)