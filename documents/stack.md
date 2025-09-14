# Tech stack

Chitch uses some fundamental technologies to get the job done. In their order of occurrence:

1. Linux
2. Apache
3. PHP
4. Baseline Web Browser

## Linux Operating System

Linux is the operating system run on almost all of the web servers. It has nice file-system and task system, which we make use of. 

- Each request is handled in its own task. 

By doing a task per request, their is higher stability. If the script would fail on some wrong input then only that 1 client will be affected. Other tasks will run independently. 

- Block size of 4096 bytes
- Writes under block size are atomic

With this we can write to a file in the database and be non-blocking to other reads or writes to that file.

## Apache Web Server

Apache is the main web server used on Shared hosting. Therefore it is supported by default. The specifics can be found in the `public/.htaccess` file. But the general rules are

- All PHP pages only use HTTPS.
- Caching for static assets for 1 year.
- Redirect empty PHP paths to closest index.php. 

## PHP language

PHP is used as an programming language and for templates. It can do both because it is an expression-oriented language. It is the language we work in directly. The following extensions are required for Chitch:

- mbstring
- filter
- session
- tokenize

## Baseline Web browser

Chitch relies on some features provided by the browser to operate. These are optional but some supplementary features might not work if the browser does not have the required feature. Thus Baseline is established as the baseline of features we can rely on for browser
 

## A Request cycle through the stack

We can take a request and trace its journey in the order of the technologies that handle it. A summarization of the journey is as follows:

```
Request -> Apache Web Server -> PHP -> Chitch -> Web browser
```

Linux is ommitted because it is always present underneath PHP and the Web server. It is not really configurable by the host anyway. We just have to know some features which Chitch relies on. This journey is also cyclical because the Web browser also makes the Request in the first place. Lets go through the journey.

	 
