# Web sign in with Apple
Simple example web sign in with Apple by PHP

#### Example web login via Apple account

Please take a look at `example` folder

[Source](https://developer.okta.com/blog/2019/06/04/what-the-heck-is-sign-in-with-apple "What the Heck is Sign In with Apple?")

#### Decode Apple RS256

With base64 string get from ios devices
```php
Apple::verify_base64('xxxx')
```

From JWT string
```php
Apple::verify('xxxx')
```

#### Test command

```bash
vendor\bin\phpunit
```

