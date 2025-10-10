<?php
$f_csp_enable = get_field('csp_enable', 'option');
if ($f_csp_enable) {
    /**
     * ENV_PATH
     * This is critical for csp to work correctly.
     * We need to set the paths to all external links that are needed for the site to work properly.
     */
    define('ENV_PATH', get_site_url());

    // ALLOWABLE_FONTS
    $f_csp_allow_fonts = get_field('csp_allow-fonts', 'option');
    $csp_allow_fonts = " https://fonts.googleapis.com/ https://fonts.gstatic.com/  ";
    $csp_allow_fonts .= " " . ENV_PATH . " ";
    if ($f_csp_allow_fonts) {
        foreach ($f_csp_allow_fonts as $allow_fonts) {
            $csp_allow_fonts .= $allow_fonts['link'] . ' ';
        }
    }

    // ALLOWABLE_SCRIPTS
    $f_csp_allow_scripts = get_field('csp_allow-scripts', 'option');
    // We automatically include the site url and blob data & some of the big companies urls...
    $csp_allow_scripts = "https://tagassistant.google.com/ https://www.googletagmanager.com/ https://secure.gravatar.com/ https://0.gravatar.com/ https://google.com/ https://www.google.com/ https://www.google-analytics.com/ https://www.googletagmanager.com/ https://tagmanager.google.com https://ajax.googleapis.com/ https://googleads.g.doubleclick.net/ https://ssl.gstatic.com https://www.gstatic.com https://www.facebook.com/ https://connect.facebook.net/ https://twitter.com/ https://analytics.twitter.com/ https://t.co/ https://static.ads-twitter.com/ https://linkedin.com/ https://px.ads.linkedin.com/ https://px4.ads.linkedin.com/ https://player.vimeo.com/ https://www.youtube.com/ https://www.youtube-nocookie.com/ https://youtu.be/ https://i.ytimg.com/" . site_url() . "" . $csp_allow_fonts . " ";
    if ($f_csp_allow_scripts) {
        foreach ($f_csp_allow_scripts as $allow_scripts) {
            $csp_allow_scripts .= $allow_scripts['link'] . ' ';
        }
    }

    // Default scripts that should not be deferred
    $default_disallow_scripts = array(
        array('handle' => 'wp-i18n'),
        array('handle' => 'wp-element'),
        array('handle' => 'wp-blocks'),
        array('handle' => 'wp-components'),
        array('handle' => 'wp-polyfill'),
        array('handle' => 'wp-hooks'),
        array('handle' => 'wp-data'),
        array('handle' => 'wp-api-fetch'),
        array('handle' => 'wp-dom-ready'),
        array('handle' => 'wp-edit-post'),
        array('handle' => 'wp-plugins'),
        array('handle' => 'wp-edit-blocks'),
        array('handle' => 'wp-block-editor')
    );

    // Merge with user-defined scripts
    $f_csp_disallow_scripts_defer = get_field('csp_disallow-script-defer', 'option');
    if ($f_csp_disallow_scripts_defer && is_array($f_csp_disallow_scripts_defer)) {
        $disallow_scripts_defer = array_merge($default_disallow_scripts, $f_csp_disallow_scripts_defer);
    } else {
        $disallow_scripts_defer = $default_disallow_scripts;
    }

    define('DISALLOW_SCRIPTS_DEFER', $disallow_scripts_defer);
    define('ALLOWABLE_FONTS', $csp_allow_fonts);
    define('ALLOWABLE_SCRIPTS', $csp_allow_scripts);

    /**
     * Custom Nonce - Enhanced version
     */
    if (false === ($csp_time = get_transient('csp_time_dilation'))) {
        $csp_time = time();
        $csp_expire_time = rand(300, 600); // Increased to 5-10 minutes for better stability
        set_transient('csp_time_dilation', $csp_time, $csp_expire_time);
    }

    define('CSP_NONCE', wp_create_nonce('csp_nonce_' . $csp_time));

    /**
     * Add CSP nonce to body class and make it available to JavaScript
     */
    function ronikdesigns_body_class($classes)
    {
        $classes[] = 'csp-enabled';
        return $classes;
    }
    add_filter('body_class', 'ronikdesigns_body_class');

    function hook_csp()
    {
?>
        <span data-csp="<?php echo CSP_NONCE; ?>" style="opacity:0;position:absolute;left:-3000px;top:-3000px;height:0;overflow:hidden;"></span>
        <script nonce="<?php echo CSP_NONCE; ?>">
            // Make nonce available globally for plugins that need it
            window.CSP_NONCE = '<?php echo CSP_NONCE; ?>';
        </script>
        <?php
    }
    add_action('wp_head', 'hook_csp', 1); // Higher priority

    /**
     * We only want to trigger when user is not logged in.
     * Due to the complexity of the wp admin interface.
     */
    if (!is_admin() && !is_user_logged_in()) {

        // Remove unnecessary WordPress CSS
        function ronikdesigns_remove_wp_block_library_css()
        {
            wp_dequeue_style('wp-block-library');
            wp_dequeue_style('wp-block-library-theme');
            wp_dequeue_style('wc-block-style');
        }
        add_action('wp_enqueue_scripts', 'ronikdesigns_remove_wp_block_library_css', 100);

        // Move jQuery to footer
        function ronikdesigns_jquery_to_footer()
        {
            wp_scripts()->add_data('jquery-core', 'group', 1);
            wp_scripts()->add_data('jquery-migrate', 'group', 1);
        }
        add_action('wp_enqueue_scripts', 'ronikdesigns_jquery_to_footer');

        // Remove jQuery migrate
        function ronikdesigns_remove_jquery_migrate($scripts)
        {
            if (!is_admin() && isset($scripts->registered['jquery'])) {
                $script = $scripts->registered['jquery'];
                if ($script->deps) {
                    $script->deps = array_diff($script->deps, array('jquery-migrate'));
                }
            }
        }
        add_action('wp_default_scripts', 'ronikdesigns_remove_jquery_migrate');

        /**
         * ENHANCED: Add nonce to ALL script tags
         */
        add_filter('script_loader_tag', function ($tag, $handle, $src) {
            // Add nonce to all script tags if not already present
            if (strpos($tag, 'nonce=') === false) {
                $tag = str_replace('<script ', '<script nonce="' . CSP_NONCE . '" ', $tag);
            }

            // Add defer to most scripts, but not to critical ones
            $critical_scripts = apply_filters('csp_critical_scripts', DISALLOW_SCRIPTS_DEFER);
            $should_defer = true;

            foreach ($critical_scripts as $critical_script) {
                if ($critical_script['handle'] == $handle) {
                    $should_defer = false;
                    break;
                }
            }

            if ($should_defer && strpos($tag, 'defer') === false) {
                $tag = str_replace('<script ', '<script defer ', $tag);
            }

            return $tag;
        }, 10, 3);

        /**
         * ENHANCED: Add nonce to inline scripts
         */
        add_filter('wp_inline_script_attributes', function ($attributes) {
            $attributes['nonce'] = CSP_NONCE;
            return $attributes;
        });

        add_filter('wp_script_attributes', function ($attributes) {
            if (!isset($attributes['nonce'])) {
                $attributes['nonce'] = CSP_NONCE;
            }
            return $attributes;
        });

        /**
         * ENHANCED: Hook into wp_print_scripts to catch plugin inline scripts
         */
        function capture_and_nonce_inline_scripts()
        {
            ob_start(function ($html) {
                // Find all script tags without nonce and add them
                $pattern = '/<script(?![^>]*nonce=)([^>]*)>(.*?)<\/script>/is';
                return preg_replace_callback($pattern, function ($matches) {
                    $attributes = $matches[1];
                    $content = $matches[2];

                    // Skip if it's an external script (has src attribute)
                    if (strpos($attributes, 'src=') !== false) {
                        return $matches[0];
                    }

                    // Add nonce to inline scripts
                    return '<script nonce="' . CSP_NONCE . '"' . $attributes . '>' . $content . '</script>';
                }, $html);
            });
        }
        add_action('wp_head', 'capture_and_nonce_inline_scripts', 1);
        add_action('wp_footer', 'capture_and_nonce_inline_scripts', 1);

        /**
         * ENHANCED: Better CSP headers with stricter policy
         */
        function additional_securityheaders($headers)
        {
            $nonce = "nonce-" . CSP_NONCE;

            // Security headers
            $headers['Referrer-Policy'] = 'no-referrer-when-downgrade';
            $headers['X-Content-Type-Options'] = 'nosniff';
            $headers['X-XSS-Protection'] = '1; mode=block';
            $headers['Permissions-Policy'] = 'browsing-topics=(), fullscreen=(self ' . ENV_PATH . ' https://www.youtube.com https://www.youtube-nocookie.com https://player.vimeo.com), geolocation=*, camera=()';
            $headers['Cross-Origin-Opener-Policy'] = 'same-origin';
            // COEP removed - incompatible with third-party embeds like YouTube
            // $headers['Cross-Origin-Embedder-Policy'] = 'credentialless';
            $headers['X-Frame-Options'] = 'SAMEORIGIN';

            // ENHANCED CSP - Maximum XSS protection with backward compatibility
            $csp = "default-src 'self'; ";

            // Scripts: Secure modern approach with backward compatibility
            // 'unsafe-inline' is ignored by modern browsers when nonce is present
            // https: http: are ignored by browsers supporting 'strict-dynamic'
            $csp .= "script-src 'self' '$nonce' 'strict-dynamic' 'unsafe-inline' https: http:; ";

            // FIXED: Remove data: scheme from script-src-elem for XSS protection
            // Only allow specific trusted domains and nonce
            $csp .= "script-src-elem 'self' '$nonce' " . ALLOWABLE_SCRIPTS . "; ";

            // Styles: Allow unsafe-inline for now (can be improved later with nonces for styles too)
            $csp .= "style-src 'self' 'unsafe-inline' " . ALLOWABLE_SCRIPTS . "; ";

            // FIXED: Add connect-src for AJAX/fetch requests (Google Analytics, etc.)
            $csp .= "connect-src 'self' https://www.google-analytics.com https://analytics.google.com https://region1.google-analytics.com https://region1.analytics.google.com https://stats.g.doubleclick.net " . ALLOWABLE_SCRIPTS . "; ";

            $csp .= "font-src 'self' " . ALLOWABLE_FONTS . "; ";
            $csp .= "img-src 'self' data: blob: " . ALLOWABLE_SCRIPTS . "; ";
            $csp .= "frame-src 'self' " . ALLOWABLE_SCRIPTS . "; ";
            $csp .= "object-src 'none'; ";
            $csp .= "base-uri 'none'; ";
            $csp .= "report-uri " . ENV_PATH . "/csp-report;";

            $headers['Content-Security-Policy'] = $csp;

            return $headers;
        }
        add_filter('wp_headers', 'additional_securityheaders', 1);

        /**
         * ENHANCED: Plugin compatibility filters
         */

        // Gravity Forms
        add_filter('gform_csp_nonce', function () {
            return CSP_NONCE;
        });

        // WooCommerce
        add_filter('woocommerce_inline_script_attributes', function ($attributes) {
            $attributes['nonce'] = CSP_NONCE;
            return $attributes;
        });

        // Contact Form 7
        add_filter('wpcf7_script_attributes', function ($attributes) {
            $attributes['nonce'] = CSP_NONCE;
            return $attributes;
        });

        /**
         * ENHANCED: Generic plugin inline script handler
         * This tries to catch inline scripts from plugins that don't use WordPress hooks
         */
        function fix_plugin_inline_scripts()
        {
        ?>
            <script nonce="<?php echo CSP_NONCE; ?>">
                // Helper function for plugins to add nonce to dynamically created scripts
                (function() {
                    const originalCreateElement = document.createElement;
                    document.createElement = function(tagName) {
                        const element = originalCreateElement.call(this, tagName);
                        if (tagName.toLowerCase() === 'script') {
                            element.setAttribute('nonce', '<?php echo CSP_NONCE; ?>');
                        }
                        return element;
                    };

                    // Also override innerHTML for scripts
                    const originalSetInnerHTML = Object.getOwnPropertyDescriptor(Element.prototype, 'innerHTML').set;
                    Object.defineProperty(Element.prototype, 'innerHTML', {
                        set: function(value) {
                            if (this.tagName === 'SCRIPT' && !this.hasAttribute('nonce')) {
                                this.setAttribute('nonce', '<?php echo CSP_NONCE; ?>');
                            }
                            return originalSetInnerHTML.call(this, value);
                        }
                    });
                })();
            </script>
<?php
        }
        add_action('wp_head', 'fix_plugin_inline_scripts', 999);
    }
}

/**
 * BONUS: CSP Report Handler
 * Add this to handle CSP violation reports
 */
function handle_csp_reports()
{
    if ($_SERVER['REQUEST_URI'] === '/csp-report' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = file_get_contents('php://input');
        $report = json_decode($input, true);

        // Log CSP violations (you can customize this)
        error_log('CSP Violation: ' . print_r($report, true));

        // You could also save to database or send alerts

        http_response_code(204); // No content
        exit;
    }

    add_action('send_headers', function() {
        header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
    });
}
add_action('init', 'handle_csp_reports');
?>