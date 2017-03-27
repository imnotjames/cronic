# Cronic
## A cron job handler using Annotations for configuration.

### How it Works

Magic.  Annotate your class with `@cron` and a cron expression, and set up
the `cronic` command in your crontab.

```
class Example {

    /**
     * @cron * * * * *
     */
    function exampleMethod() {
    }
}
```
