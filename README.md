# Headless WordPress Admin Access

This is a WordPress plugin to control access to the WordPress admin.

If the user trying to visit the WordPress admin does not have the capabilities this plugin requires, they will be logged out and sent to the decoupled frontend JS app URL, if set, or otherwise to the WordPress admin login page.

## Steps to Use

1. Make sure you have the [WPGraphQL CORS](https://github.com/funkhaus/wp-graphql-cors) plugin installed and activated.
1. From the WordPress admin sidebar, go to `GraphQL` > `Settings` and click the `CORS Settings` tab.
1. In the `Extend "Access-Control-Allow-Origin” header` field, enter the URL of your decoupled frontend JS app and click the button to save your changes.
1. In this plugin's `headless-wp-admin-access.php` file, modify the `log_out_and_redirect_non_admins()` method, if desired, to limit WordPress admin access to users with a certain capability.
1. Install and activate this plugin.
1. Test logging in as a user with the required capability, and as a user without it to confirm that admin access has been locked down correctly.
