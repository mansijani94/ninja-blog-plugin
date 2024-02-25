# Ninja Blog WordPress Plugin

This WordPress plugin creates a page named "Ninja Blog" upon activation with a shortcode to display posts. It restricts editing capabilities for the "Ninja Blog" page and deletes the page upon deactivation.

## Features

- Automatically creates a page named "Ninja Blog" with a shortcode to display posts.
- Prevents editing of the "Ninja Blog" page content.
- Deletes the "Ninja Blog" page upon deactivation of the plugin.

## Installation

1. Download the `ninja-blog` folder.
2. Upload the folder to the `/wp-content/plugins/` directory on your WordPress site.
3. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

After activating the plugin, a page named "Ninja Blog" will be created with the slug `ninja-blog`. You can access this page using the URL `siteurl/ninja-blog`. The page will display a list of posts with default WordPress pagination.

To display the posts, use the `[ninja_blog_show_posts]` shortcode.

## License

This plugin is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Author

Mansi Jani