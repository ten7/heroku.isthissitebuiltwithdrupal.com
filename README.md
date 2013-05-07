This is the code behind the website http://isthissitebuiltwithdrupal.com and is made available for demonstration/learning purposes and in case someone has ideas about improving it.

## Install your own copy

You can get a copy of this up and running by cloning this repository and then installing the dependencies with composer.

  composer.phar install


## API

This application exposes a super simple rest API.

GET: /{URL}
Accept: appplication/json

URL: The fully qualified URL of website to test.

Returns:

````
  {
    "url":"http:\/\/drupalize.me",
    "is_drupal":"yes",
    "tests": {
      "expires header":"failed",
      "drupal.settings":"passed"
    },
    "errors":[]
  }
````
