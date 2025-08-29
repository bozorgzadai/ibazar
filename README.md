# ibazar

A PHP-based web application with MVC architecture, supporting SOAP and RESTful integrations, including SMS functionality via Kavenegar.

## Features

- MVC structure: organized controllers, models, and views
- Asset management: CSS, JS, fonts, and images for products, brands, shops, and more
- SOAP support via NuSOAP
- SMS sending via Kavenegar PHP SDK
- Localization support (Persian language)
- Configurable via `config.php`

## Folder Structure

```
config.php
index.php
asset/
image/
lib/
locale/
mvc/
system/
theme/
```

## Installation

1. Clone the repository.
2. Configure your web server (see `htaccess` for Apache).
3. Import the database from `ibazar (31.06.97) DB.sql.gz`.
4. Update settings in `config.php`.
5. Install dependencies for Kavenegar SMS:
    ```sh
    cd system/kavenegarPhpMaster
    composer update
    ```

## Usage

### SOAP Integration

NuSOAP classes are available in `lib/nusoap/`. See `nusoap.php` for usage.

## Localization

Edit `locale/fa.php` for Persian language support.

## License

See individual library licenses (e.g., Kavenegar SDK is MIT). Project code is subject to your own licensing.

## Credits

- NuSOAP for SOAP support
- Kavenegar PHP SDK for SMS integration

---

For more information, see [Kavenegar RESTful API Document](http://kavenegar.com/rest.html).