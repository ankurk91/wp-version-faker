# Version Faker for WordPress

> Always show that you are running the latest version of WordPress


### Prerequisites
* php v5.3.0+ || v7.0.x
* WordPress v3.8.0 or above

### How does it work ?
* WordPress exposes a global variable ```$wp_version``` that is not constant and can be overwrite.
* This plugin calls official WordPress [version-check](https://api.wordpress.org/core/version-check/1.7/) API and find the latest version then overwrites this global variable.
* Plugin stores this version into a transient with an expiration of 12 hours, which means plugin will check for new version after 12 hours. 

### License
MIT [License](LICENSE.txt)
