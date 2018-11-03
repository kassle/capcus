# capcus

Revolutionary URL Shortener

## Development

### Directory Structure

bin = external supplement tools
src

- core = source code
- libs = external library
- site = web interface

### Running Test

#### Windows
.\bin\test.bat

### Build

#### Windows
.\bin\build.bat

### Deployment

#### nginx
```
        location ~ "^/[a-zA-Z0-9]{8}" {
            rewrite ^/(.*)$ $scheme://$host/index.php?type=capcus.get&id=1&code=$1 break;
        }

        root /home/kassle/capcus/src/site;
```