<?php
/**
 * WP Rapid Fields Pro
 *
 * A lightweight and flexible custom fields framework for WordPress.
 * Supports theme options, post meta, term meta, and user meta with various field types.
 *
 * @package    WP_Rapid_Fields
 * @version    7.1
 * @author     WP导师 (wpclass.cn)
 * @link       https://wpclass.cn
 * @updated    2026-03-30
 *
 * Features:
 * - Standard field types (Text, Textarea, Select, Radio, Checkbox, etc.)
 * - Rich UI fields (Color Picker, WP Editor, Image / File Upload)
 * - Complex types (Gallery, Searchable Post Select, Nested Groups)
 * - Repeatable "Set" fields for simple lists.
 * - Polylang language support
 */
if (!class_exists('WP_Rapid_Fields_Pro')) {

    class WP_Rapid_Fields_Pro
    {

        private $config;
        private $tabs;
        private $flat_fields = [];
        private $fields_lookup = [];
        private $pll_lang = '';
        private $pll_active = false;

        public function __construct($config, $tabs)
        {
            $this->config = $config;
            $this->tabs = $tabs;

            // Detect Polylang
            $this->pll_active = function_exists('pll_current_language');
            $this->pll_lang = $this->pll_active ? pll_current_language('slug') : '';

            foreach ($this->tabs as $tab) {
                if (isset($tab['fields']) && is_array($tab['fields'])) {
                    foreach ($tab['fields'] as $field) {
                        if (isset($field['id']) && (!isset($field['type']) || $field['type'] !== 'heading')) {
                            $this->fields_lookup[$field['id']] = $field;
                        }
                    }
                }
            }

            $this->flat_fields = array_values($this->fields_lookup);

            $context = $this->config['context'] ?? 'post';

            if ($context === 'post') {
                add_action('add_meta_boxes', [$this, 'init_meta_box']);
                add_action('save_post', [$this, 'save_post_meta']);
            }
            elseif ($context === 'term') {
                $tax_config = $this->config['taxonomy'] ?? 'category';
                $taxonomies = is_array($tax_config) ? $tax_config : [$tax_config];
                foreach ($taxonomies as $tax) {
                    add_action("{$tax}_edit_form_fields", [$this, 'render_term_fields_edit'], 10, 2);
                    add_action("{$tax}_add_form_fields", [$this, 'render_term_fields_add'], 10);
                    add_action("edited_{$tax}", [$this, 'save_term_meta']);
                    add_action("create_{$tax}", [$this, 'save_term_meta']);
                }
            }
            elseif ($context === 'option') {
                add_action('admin_menu', [$this, 'add_admin_menu']);
                add_action('admin_init', [$this, 'register_settings']);
            }
            elseif ($context === 'user') {
                add_action('show_user_profile', [$this, 'render_user_fields']);
                add_action('edit_user_profile', [$this, 'render_user_fields']);
                add_action('personal_options_update', [$this, 'save_user_meta']);
                add_action('edit_user_profile_update', [$this, 'save_user_meta']);
            }

            add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        }

        /**
         * Get the current Polylang admin language slug.
         * Returns empty string if Polylang is not active or no language is set.
         */
        private function get_pll_lang()
        {
            if (!$this->pll_active) {
                return '';
            }
            // In admin context, check for the 'lang' query parameter first (Polylang admin filter)
            if (is_admin() && isset($_GET['lang']) && $_GET['lang'] !== 'all') {
                return sanitize_text_field($_GET['lang']);
            }
            // For save actions, check POST data for language
            if (is_admin() && isset($_POST['post_lang_choice'])) {
                return sanitize_text_field($_POST['post_lang_choice']);
            }
            // Check the current language from Polylang
            $lang = pll_current_language('slug');
            if ($lang) {
                return $lang;
            }
            // Fallback to default language
            return pll_default_language('slug');
        }

        /**
         * Get language suffix for storage keys.
         * Returns a string like '_en' or '' if Polylang is not active.
         */
        private function get_pll_suffix()
        {
            $lang = $this->get_pll_lang();
            if ($lang) {
                // If Polylang is active and we are on the default language, 
                // we should use empty suffix to keep using standard meta keys.
                $default_lang = function_exists('pll_default_language') ? pll_default_language('slug') : '';
                if ($lang === $default_lang) {
                    return '';
                }
                return '_' . $lang;
            }
            return '';
        }

        /**
         * Get the language-aware option name for options pages.
         */
        private function get_option_name()
        {
            return $this->config['id'] . '_opts' . $this->get_pll_suffix();
        }

        /**
         * Get the language-aware meta key for post/term/user meta.
         */
        private function get_meta_key($key)
        {
            $suffix = $this->get_pll_suffix();
            return $key . $suffix;
        }

        /**
         * Render the Polylang language badge notice in admin.
         */
        private function render_pll_badge()
        {
            if (!$this->pll_active) {
                return;
            }
            $lang = $this->get_pll_lang();
            if (!$lang) {
                return;
            }
            $languages = pll_the_languages(['raw' => 1]);
            $lang_name = $lang;
            $flag_url = '';
            if (is_array($languages)) {
                foreach ($languages as $l) {
                    if (isset($l['slug']) && $l['slug'] === $lang) {
                        $lang_name = $l['name'] ?? $lang;
                        $flag_url = $l['flag'] ?? '';
                        break;
                    }
                }
            }
            echo '<div class="wrf-pll-badge">';
            if ($flag_url) {
            //echo '<span class="wrf-pll-flag">' . $flag_url . '</span> ';
            }
            echo '<span>' . sprintf(
                /* translators: %s is the language name */
                esc_html__('Editing: %s', 'tqb'),
                '<strong>' . esc_html($lang_name) . '</strong>'
            ) . '</span>';
            echo '</div>';
        }

        public function enqueue_assets()
        {
            static $assets_loaded = false;
            if ($assets_loaded)
                return;
            $assets_loaded = true;

            $screen = get_current_screen();
            if (!$screen)
                return;

            wp_enqueue_media();
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');

            add_action('admin_head', function () { ?>
                <style>
                    .wrf-wrap {
                        background: #fff;
                        border: 1px solid #ccd0d4;
                        margin-top: 20px;
                        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
                    }

                    .wrf-nav {
                        display: flex;
                        background: #f3f4f5;
                        border-bottom: 1px solid #ccd0d4;
                        margin: 0;
                        padding: 0;
                        list-style: none;
                    }

                    .wrf-nav a {
                        display: block;
                        padding: 12px 15px;
                        text-decoration: none;
                        color: #555;
                        border-right: 1px solid #ccd0d4;
                        font-weight: 600;
                        font-size: 13px;
                    }

                    .wrf-nav a.active {
                        background: #fff;
                        border-bottom: 1px solid #fff;
                        margin-bottom: -1px;
                        color: #0073aa;
                    }

                    .wrf-content {
                        padding: 20px;
                    }

                    .wrf-tab {
                        display: block;
                        visibility: hidden;
                        height: 0;
                        overflow: hidden;
                    }

                    .wrf-tab.active {
                        visibility: visible;
                        height: auto;
                        overflow: visible;
                    }

                    .wrf-field {
                        margin-bottom: 20px;
                        padding-bottom: 20px;
                        border-bottom: 1px solid #eee;
                    }

                    .wrf-label {
                        display: block;
                        font-weight: 700;
                        margin-bottom: 8px;
                        color: #23282d;
                    }

                    .wrf-desc {
                        margin-top: 6px;
                        color: #666;
                        font-style: italic;
                        font-size: 12px;
                    }

                    .wrf-section-title {
                        margin: 20px 0 15px;
                        padding: 10px;
                        background: #f9f9f9;
                        border-left: 4px solid #0073aa;
                        font-weight: 700;
                    }

                    .wrf-flex {
                        display: flex;
                        gap: 10px;
                        align-items: center;
                    }

                    .wrf-img-preview {
                        max-width: 100px;
                        margin-top: 10px;
                        border: 1px solid #ddd;
                        padding: 4px;
                        display: none;
                    }

                    .wrf-gallery-thumbs {
                        display: flex;
                        gap: 10px;
                        flex-wrap: wrap;
                        margin-bottom: 10px;
                    }

                    .wrf-thumb {
                        position: relative;
                        width: 80px;
                        height: 80px;
                        border: 1px solid #ddd;
                        background: #eee;
                    }

                    .wrf-thumb img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                    }

                    .wrf-remove-img {
                        position: absolute;
                        top: -5px;
                        right: -5px;
                        background: #d63638;
                        color: #fff;
                        border-radius: 50%;
                        width: 20px;
                        height: 20px;
                        text-align: center;
                        line-height: 18px;
                        cursor: pointer;
                        font-weight: bold;
                    }

                    .wrf-group-wrapper {
                        background: #f9f9f9;
                        padding: 15px;
                        border: 1px solid #e5e5e5;
                    }

                    /* --- GROUP COLLAPSE STYLES --- */
                    .wrf-group-row {
                        background: #fff;
                        border: 1px solid #ccd0d4;
                        margin-bottom: 10px;
                        position: relative;
                    }

                    .wrf-group-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 10px 15px;
                        background: #eee;
                        cursor: pointer;
                        border-bottom: 1px solid #ddd;
                        user-select: none;
                    }

                    .wrf-group-header:hover {
                        background: #e5e5e5;
                    }

                    .wrf-group-title {
                        font-weight: 600;
                        color: #555;
                    }

                    .wrf-group-controls {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                    }

                    .wrf-toggle-icon {
                        transition: transform 0.2s;
                        font-size: 10px;
                        color: #777;
                    }

                    .wrf-group-row.closed .wrf-group-content {
                        display: none;
                    }

                    .wrf-group-row.closed .wrf-group-header {
                        border-bottom: none;
                    }

                    .wrf-group-row.closed .wrf-toggle-icon {
                        transform: rotate(-90deg);
                    }

                    .wrf-group-content {
                        padding: 15px;
                    }

                    .wrf-remove-row {
                        color: #a00;
                        text-decoration: none;
                        font-size: 12px;
                        cursor: pointer;
                        border: 1px solid #d63638;
                        border-radius: 3px;
                        padding: 2px 6px;
                        background: #fff;
                    }

                    .wrf-remove-row:hover {
                        background: #d63638;
                        color: #fff;
                    }

                    /* --- SEARCHABLE POST SELECT STYLES --- */
                    .wrf-searchable-select {
                        position: relative;
                        width: 100%;
                    }

                    .wrf-searchable-select .wrf-ss-input {
                        width: 100%;
                        padding: 6px 30px 6px 10px;
                        border: 1px solid #8c8f94;
                        border-radius: 4px;
                        font-size: 14px;
                        line-height: 2;
                        box-sizing: border-box;
                        background: #fff url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20d%3D%22M5%206l5%205%205-5%202%201-7%207-7-7z%22%20fill%3D%22%2350575e%22%2F%3E%3C%2Fsvg%3E') no-repeat right 8px center;
                        background-size: 16px 16px;
                        cursor: text;
                    }

                    .wrf-searchable-select .wrf-ss-input:focus {
                        border-color: #2271b1;
                        box-shadow: 0 0 0 1px #2271b1;
                        outline: none;
                    }

                    .wrf-searchable-select .wrf-ss-dropdown {
                        display: none;
                        position: absolute;
                        top: 100%;
                        left: 0;
                        right: 0;
                        max-height: 200px;
                        overflow-y: auto;
                        background: #fff;
                        border: 1px solid #8c8f94;
                        border-top: none;
                        border-radius: 0 0 4px 4px;
                        z-index: 100000;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
                    }

                    .wrf-searchable-select.open .wrf-ss-dropdown {
                        display: block;
                    }

                    .wrf-searchable-select.open .wrf-ss-input {
                        border-radius: 4px 4px 0 0;
                    }

                    .wrf-searchable-select .wrf-ss-option {
                        padding: 8px 12px;
                        cursor: pointer;
                        font-size: 13px;
                        border-bottom: 1px solid #f0f0f1;
                    }

                    .wrf-searchable-select .wrf-ss-option:last-child {
                        border-bottom: none;
                    }

                    .wrf-searchable-select .wrf-ss-option:hover,
                    .wrf-searchable-select .wrf-ss-option.highlighted {
                        background: #2271b1;
                        color: #fff;
                    }

                    .wrf-searchable-select .wrf-ss-option.selected {
                        background: #f0f0f1;
                        font-weight: 600;
                    }

                    .wrf-searchable-select .wrf-ss-option.selected:hover,
                    .wrf-searchable-select .wrf-ss-option.selected.highlighted {
                        background: #2271b1;
                        color: #fff;
                    }

                    .wrf-searchable-select .wrf-ss-no-results {
                        padding: 8px 12px;
                        color: #757575;
                        font-style: italic;
                        font-size: 13px;
                    }

                    /* Multiple select tags */
                    .wrf-searchable-select .wrf-ss-tags {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 4px;
                        margin-bottom: 4px;
                    }

                    .wrf-searchable-select .wrf-ss-tag {
                        display: inline-flex;
                        align-items: center;
                        background: #2271b1;
                        color: #fff;
                        padding: 2px 8px;
                        border-radius: 3px;
                        font-size: 12px;
                        gap: 4px;
                    }

                    .wrf-searchable-select .wrf-ss-tag-remove {
                        cursor: pointer;
                        font-weight: bold;
                        font-size: 14px;
                        line-height: 1;
                        opacity: 0.8;
                    }

                    .wrf-searchable-select .wrf-ss-tag-remove:hover {
                        opacity: 1;
                    }

                    /* --- SET FIELD STYLES --- */
                    .wrf-set-wrapper {
                        background: #f9f9f9;
                        padding: 15px;
                        border: 1px solid #e5e5e5;
                    }

                    .wrf-set-item {
                        display: flex;
                        gap: 8px;
                        align-items: center;
                        margin-bottom: 8px;
                    }

                    .wrf-set-item input {
                        flex: 1;
                    }

                    .wrf-set-remove {
                        flex-shrink: 0;
                        color: #a00;
                        text-decoration: none;
                        font-size: 18px;
                        cursor: pointer;
                        border: 1px solid #d63638;
                        border-radius: 3px;
                        width: 28px;
                        height: 28px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: #fff;
                        line-height: 1;
                    }

                    .wrf-set-remove:hover {
                        background: #d63638;
                        color: #fff;
                    }
                    /* --- POLYLANG LANGUAGE BADGE --- */
                    .wrf-pll-badge {
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        background: linear-gradient(135deg, #2271b1, #135e96);
                        color: #fff;
                        padding: 8px 16px;
                        border-radius: 6px;
                        font-size: 13px;
                        font-weight: 500;
                        margin-bottom: 15px;
                        box-shadow: 0 2px 8px rgba(34, 113, 177, 0.3);
                    }

                    .wrf-pll-badge .wrf-pll-flag img {
                        vertical-align: middle;
                        margin-right: 4px;
                    }

                    .wrf-pll-lang-switcher {
                        margin-bottom: 15px;
                    }

                    .wrf-pll-lang-switcher a {
                        display: inline-flex;
                        align-items: center;
                        gap: 4px;
                        padding: 6px 12px;
                        margin-right: 6px;
                        background: #f0f0f1;
                        border: 1px solid #ccd0d4;
                        border-radius: 4px;
                        text-decoration: none;
                        color: #555;
                        font-size: 12px;
                        transition: all 0.2s;
                    }

                    .wrf-pll-lang-switcher a:hover {
                        background: #e5e5e5;
                        color: #0073aa;
                    }

                    .wrf-pll-lang-switcher a.active {
                        background: #2271b1;
                        color: #fff;
                        border-color: #2271b1;
                    }
                </style>
                <script>
                    jQuery(document).ready(function ($) {
                        function initFields(scope) { scope.find('.wrf-color').wpColorPicker(); }
                        initFields($('body'));
                        var boxId = '<?php echo esc_js($this->config['id']); ?>';

                        // Function to reinitialize TinyMCE editors in visible tab
                        function reinitWpEditors(container) {
                            if (typeof tinymce === 'undefined' || typeof tinyMCEPreInit === 'undefined') return;
                            container.find('.wp-editor-area').each(function () {
                                var editorId = $(this).attr('id');
                                if (!editorId || !tinyMCEPreInit.mceInit[editorId]) return;
                                var editor = tinymce.get(editorId);
                                if (editor) {
                                    var content = editor.getContent();
                                    editor.remove();
                                    tinymce.init(tinyMCEPreInit.mceInit[editorId]);
                                    setTimeout(function () {
                                        var newEditor = tinymce.get(editorId);
                                        if (newEditor) {
                                            newEditor.setContent(content);
                                        }
                                    }, 150);
                                }
                            });
                        }

                        var active = localStorage.getItem('wrf_tab_' + boxId);
                        // Delay initial tab activation to allow TinyMCE to initialize first
                        setTimeout(function () {
                            if (active && $('.wrf-nav a[href="' + active + '"]').length) {
                                $('.wrf-nav a[href="' + active + '"]').click();
                            } else {
                                $('.wrf-nav a').first().click();
                            }
                            // Reinit editors in active tab after page load
                            var activeTab = $('.wrf-tab.active');
                            if (activeTab.length) {
                                reinitWpEditors(activeTab);
                            }
                        }, 300);

                        // NAVIGATION with TinyMCE fix
                        $(document).on('click', '.wrf-nav a', function (e) {
                            e.preventDefault();
                            var wrap = $(this).closest('.wrf-wrap');
                            wrap.find('.wrf-nav a').removeClass('active');
                            $(this).addClass('active');
                            wrap.find('.wrf-tab').removeClass('active');
                            var target = $($(this).attr('href'));
                            target.addClass('active');
                            localStorage.setItem('wrf_tab_' + boxId, $(this).attr('href'));

                            // Reinitialize TinyMCE editors in the newly visible tab
                            if (typeof tinymce !== 'undefined') {
                                target.find('.wp-editor-area').each(function () {
                                    var editorId = $(this).attr('id');
                                    var editor = tinymce.get(editorId);
                                    if (editor) {
                                        // Save content, remove and reinit
                                        var content = editor.getContent();
                                        editor.remove();
                                        tinymce.init(tinyMCEPreInit.mceInit[editorId]);
                                        // Wait for reinit then restore content
                                        setTimeout(function () {
                                            var newEditor = tinymce.get(editorId);
                                            if (newEditor) {
                                                newEditor.setContent(content);
                                            }
                                        }, 100);
                                    }
                                });
                            }
                        });

                        // IMAGE / FILE UPLOAD
                        $(document).on('click', '.wrf-upload', function (e) {
                            e.preventDefault();
                            var btn = $(this), input = btn.prev('input'), img = btn.parent().next('.wrf-img-preview');
                            var returnType = input.data('return');
                            var frame = wp.media({ title: 'Select Image', multiple: false, button: { text: 'Use Image' } });
                            frame.on('select', function () {
                                var att = frame.state().get('selection').first().toJSON();
                                var valToSave = (returnType === 'id') ? att.id : att.url;
                                var previewUrl = att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;
                                input.val(valToSave);
                                img.attr('src', previewUrl).show();
                            });
                            frame.open();
                        });

                        // GALLERY
                        $(document).on('click', '.wrf-gallery-add', function (e) { e.preventDefault(); var btn = $(this), wrap = btn.closest('.wrf-gallery-wrap'), input = wrap.find('input[type="hidden"]'), list = wrap.find('.wrf-gallery-thumbs'); var frame = wp.media({ title: 'Add Images', multiple: true, button: { text: 'Add to Gallery' } }); frame.on('select', function () { var selection = frame.state().get('selection'); var ids = input.val() ? input.val().split(',') : []; selection.map(function (att) { att = att.toJSON(); if ($.inArray(att.id.toString(), ids) === -1) { ids.push(att.id); var thumb = att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url; list.append('<div class="wrf-thumb" data-id="' + att.id + '"><img src="' + thumb + '"><span class="wrf-remove-img">x</span></div>'); } }); input.val(ids.join(',')); }); frame.open(); });
                        $(document).on('click', '.wrf-remove-img', function () { var item = $(this).parent(), id = item.data('id'), wrap = item.closest('.wrf-gallery-wrap'), input = wrap.find('input[type="hidden"]'); var ids = input.val().split(','); ids = $.grep(ids, function (val) { return val != id; }); input.val(ids.join(',')); item.remove(); });

                        // --- REPEATER GROUP LOGIC ---

                        // Toggle Collapse
                        $(document).on('click', '.wrf-group-header', function (e) {
                            if ($(e.target).hasClass('wrf-remove-row')) return; // Ignore if clicking remove button
                            $(this).closest('.wrf-group-row').toggleClass('closed');
                        });

                        // Add Row
                        $(document).on('click', '.wrf-add-row', function (e) {
                            e.preventDefault();
                            var wrap = $(this).closest('.wrf-group-wrapper');
                            var tpl = wrap.find('.wrf-group-tpl').html();
                            var uid = new Date().getTime();
                            var rowHtml = tpl.replace(/{{i}}/g, uid);
                            rowHtml = rowHtml.replace(/data-name="/g, 'name="');

                            // Insert new row
                            var newRow = $(rowHtml);

                            // Make sure the new row is OPEN by removing 'closed' class
                            newRow.removeClass('closed');

                            newRow.insertBefore(wrap.find('.wrf-group-tpl'));
                            initFields(newRow);
                        });

                        // Remove Row
                        $(document).on('click', '.wrf-remove-row', function (e) {
                            e.preventDefault();
                            e.stopPropagation(); // Stop bubble to header click
                            if (confirm('Remove row?')) $(this).closest('.wrf-group-row').remove();
                        });

                        // --- SET FIELD LOGIC ---
                        // Add Set Item
                        $(document).on('click', '.wrf-add-set-item', function (e) {
                            e.preventDefault();
                            var wrap = $(this).closest('.wrf-set-wrapper');
                            var baseName = wrap.data('name');
                            var uid = new Date().getTime();
                            var ph = wrap.data('placeholder') || '';
                            var html = '<div class="wrf-set-item">' +
                                '<input type="text" name="' + baseName + '[]" value="" class="widefat" placeholder="' + ph + '">' +
                                '<span class="wrf-set-remove">&times;</span>' +
                                '</div>';
                            $(html).insertBefore(wrap.find('.wrf-add-set-item'));
                        });

                        // Remove Set Item
                        $(document).on('click', '.wrf-set-remove', function (e) {
                            e.preventDefault();
                            $(this).closest('.wrf-set-item').remove();
                        });

                        // --- SEARCHABLE POST SELECT LOGIC ---
                        // Open dropdown on input focus/click
                        $(document).on('focus click', '.wrf-ss-input', function (e) {
                            var wrap = $(this).closest('.wrf-searchable-select');
                            // Close all other dropdowns first
                            $('.wrf-searchable-select').not(wrap).removeClass('open');
                            wrap.addClass('open');
                            filterOptions(wrap, $(this).val());
                        });

                        // Filter on typing
                        $(document).on('input', '.wrf-ss-input', function () {
                            var wrap = $(this).closest('.wrf-searchable-select');
                            wrap.addClass('open');
                            filterOptions(wrap, $(this).val());
                        });

                        // Keyboard navigation
                        $(document).on('keydown', '.wrf-ss-input', function (e) {
                            var wrap = $(this).closest('.wrf-searchable-select');
                            var dropdown = wrap.find('.wrf-ss-dropdown');
                            var visible = dropdown.find('.wrf-ss-option:visible');
                            var highlighted = dropdown.find('.wrf-ss-option.highlighted');
                            var isMultiple = wrap.data('multiple');

                            if (e.keyCode === 40) { // Down
                                e.preventDefault();
                                wrap.addClass('open');
                                if (highlighted.length && highlighted.nextAll('.wrf-ss-option:visible').length) {
                                    highlighted.removeClass('highlighted');
                                    highlighted.nextAll('.wrf-ss-option:visible').first().addClass('highlighted');
                                } else if (!highlighted.length) {
                                    visible.first().addClass('highlighted');
                                }
                                scrollToHighlighted(dropdown);
                            } else if (e.keyCode === 38) { // Up
                                e.preventDefault();
                                if (highlighted.length && highlighted.prevAll('.wrf-ss-option:visible').length) {
                                    highlighted.removeClass('highlighted');
                                    highlighted.prevAll('.wrf-ss-option:visible').first().addClass('highlighted');
                                }
                                scrollToHighlighted(dropdown);
                            } else if (e.keyCode === 13) { // Enter
                                e.preventDefault();
                                if (highlighted.length) {
                                    highlighted.trigger('click');
                                }
                            } else if (e.keyCode === 27) { // Escape
                                wrap.removeClass('open');
                                $(this).blur();
                            }
                        });

                        // Select option click (single)
                        $(document).on('click', '.wrf-searchable-select:not([data-multiple]) .wrf-ss-option', function () {
                            var wrap = $(this).closest('.wrf-searchable-select');
                            var val = $(this).data('value');
                            var label = $(this).text();
                            wrap.find('.wrf-ss-hidden').val(val);
                            wrap.find('.wrf-ss-input').val(label);
                            wrap.find('.wrf-ss-option').removeClass('selected highlighted');
                            $(this).addClass('selected');
                            wrap.removeClass('open');
                        });

                        // Select option click (multiple)
                        $(document).on('click', '.wrf-searchable-select[data-multiple] .wrf-ss-option', function () {
                            var wrap = $(this).closest('.wrf-searchable-select');
                            var val = String($(this).data('value'));
                            var label = $(this).text();
                            var hiddenInputs = wrap.find('.wrf-ss-hidden');
                            var baseName = wrap.data('name');
                            var tags = wrap.find('.wrf-ss-tags');

                            // Check if already selected
                            var existing = wrap.find('.wrf-ss-hidden[value="' + val + '"]');
                            if (existing.length) {
                                // Deselect it
                                existing.remove();
                                $(this).removeClass('selected');
                                tags.find('.wrf-ss-tag[data-value="' + val + '"]').remove();
                            } else {
                                // Select it
                                wrap.append('<input type="hidden" class="wrf-ss-hidden" name="' + baseName + '[]" value="' + val + '">');
                                $(this).addClass('selected');
                                tags.append('<span class="wrf-ss-tag" data-value="' + val + '">' + label + ' <span class="wrf-ss-tag-remove">&times;</span></span>');
                            }
                            // Clear search and keep dropdown open
                            wrap.find('.wrf-ss-input').val('').focus();
                            filterOptions(wrap, '');
                        });

                        // Remove tag (multiple)
                        $(document).on('click', '.wrf-ss-tag-remove', function (e) {
                            e.stopPropagation();
                            var tag = $(this).closest('.wrf-ss-tag');
                            var wrap = tag.closest('.wrf-searchable-select');
                            var val = String(tag.data('value'));
                            wrap.find('.wrf-ss-hidden[value="' + val + '"]').remove();
                            wrap.find('.wrf-ss-option[data-value="' + val + '"]').removeClass('selected');
                            tag.remove();
                        });

                        // Close dropdown when clicking outside
                        $(document).on('mousedown', function (e) {
                            if (!$(e.target).closest('.wrf-searchable-select').length) {
                                $('.wrf-searchable-select').removeClass('open');
                                // Restore display text for single selects
                                $('.wrf-searchable-select:not([data-multiple])').each(function () {
                                    var w = $(this);
                                    var selected = w.find('.wrf-ss-option.selected');
                                    if (selected.length) {
                                        w.find('.wrf-ss-input').val(selected.text());
                                    } else {
                                        w.find('.wrf-ss-input').val('');
                                    }
                                });
                            }
                        });

                        function filterOptions(wrap, query) {
                            var dropdown = wrap.find('.wrf-ss-dropdown');
                            var options = dropdown.find('.wrf-ss-option');
                            var noResults = dropdown.find('.wrf-ss-no-results');
                            var q = query.toLowerCase();
                            var hasVisible = false;

                            options.each(function () {
                                var text = $(this).text().toLowerCase();
                                if (q === '' || text.indexOf(q) > -1) {
                                    $(this).show();
                                    hasVisible = true;
                                } else {
                                    $(this).hide();
                                }
                            });

                            options.removeClass('highlighted');

                            if (hasVisible) {
                                noResults.hide();
                            } else {
                                noResults.show();
                            }
                        }

                        function scrollToHighlighted(dropdown) {
                            var highlighted = dropdown.find('.wrf-ss-option.highlighted');
                            if (highlighted.length) {
                                var dropdownTop = dropdown.scrollTop();
                                var optionTop = highlighted.position().top + dropdownTop;
                                var dropdownHeight = dropdown.innerHeight();
                                var optionHeight = highlighted.outerHeight();
                                if (optionTop < dropdownTop) {
                                    dropdown.scrollTop(optionTop);
                                } else if (optionTop + optionHeight > dropdownTop + dropdownHeight) {
                                    dropdown.scrollTop(optionTop + optionHeight - dropdownHeight);
                                }
                            }
                        }
                    });
                </script>
            <?php
            });
        }

        // --- RENDERERS ---
        public function init_meta_box()
        {
            add_meta_box($this->config['id'], $this->config['title'], [$this, 'render_box'], $this->config['post_types'] ?? 'page', 'normal', 'high');
        }
        public function add_admin_menu()
        {
            add_menu_page($this->config['title'], $this->config['title'], 'manage_options', $this->config['id'], [$this, 'render_options']);
        }

        public function register_settings()
        {
            $option_name = $this->get_option_name();
            register_setting(
                $this->config['id'] . '_grp',
                $option_name,
            ['sanitize_callback' => [$this, 'sanitize_options_data']]
            );
        }

        public function sanitize_options_data($input)
        {
            if (!is_array($input)) {
                $input = [];
            }
            $cleaned_input = [];
            foreach ($this->flat_fields as $field) {
                if (!isset($field['id']))
                    continue;
                $id = $field['id'];

                if (isset($field['type']) && $field['type'] === 'checkbox' && !isset($input[$id])) {
                    $value = 0;
                }
                else {
                    $value = isset($input[$id]) ? $input[$id] : '';
                }

                if (isset($field['type']) && $field['type'] === 'raw_html') {
                    if (current_user_can('unfiltered_html')) {
                        $cleaned_input[$id] = $value;
                    }
                    else {
                        $cleaned_input[$id] = wp_kses_post($value);
                    }
                    continue;
                }
                $cleaned_input[$id] = $this->recursive_sanitize_and_clean($value);
            }
            return $cleaned_input;
        }

        public function render_term_fields_edit($term)
        {
            wp_nonce_field($this->config['id'] . '_ax', $this->config['id'] . '_nc');
            echo '<tr class="form-field"><th colspan="2"><h3>' . esc_html($this->config['title']) . '</h3>';
            $this->render_pll_badge();
            echo '</th></tr>';
            foreach ($this->tabs as $tab) {
                if (isset($tab['label']))
                    echo '<tr class="form-field"><th colspan="2"><h4 style="border-bottom:1px solid #ccc; margin:0;">' . esc_html($tab['label']) . '</h4></th></tr>';
                foreach ($tab['fields'] as $field) {
                    if (isset($field['type']) && $field['type'] === 'heading')
                        continue;
                    if (!isset($field['id']))
                        continue;
                    $meta_key = $this->get_meta_key($field['id']);
                    $val = metadata_exists('term', $term->term_id, $meta_key) ? get_term_meta($term->term_id, $meta_key, true) : false;

                    // Fallback for default language: if base key not found, check suffixed key
                    if ($val === false && $this->pll_active && $meta_key === $field['id']) {
                        $current_lang = $this->get_pll_lang();
                        if ($current_lang && $current_lang === (function_exists('pll_default_language') ? pll_default_language('slug') : '')) {
                            $suffixed_key = $meta_key . '_' . $current_lang;
                            if (metadata_exists('term', $term->term_id, $suffixed_key)) {
                                $val = get_term_meta($term->term_id, $suffixed_key, true);
                            }
                        }
                    }

                    echo '<tr class="form-field"><th scope="row">' . esc_html($field['label']) . '</th><td>';
                    $this->render_field_control($field, $val);
                    echo '</td></tr>';
                }
            }
        }

        public function render_term_fields_add($taxonomy)
        {
            wp_nonce_field($this->config['id'] . '_ax', $this->config['id'] . '_nc');
            echo '<h3>' . esc_html($this->config['title']) . '</h3>';
            $this->render_pll_badge();
            foreach ($this->tabs as $tab) {
                foreach ($tab['fields'] as $field) {
                    if (isset($field['type']) && $field['type'] === 'heading')
                        continue;
                    if (!isset($field['id']))
                        continue;
                    echo '<div class="form-field">';
                    echo '<label for="' . esc_attr($field['id']) . '">' . esc_html($field['label']) . '</label>';
                    $this->render_field_control($field, false);
                    echo '</div>';
                }
            }
        }

        public function render_user_fields($user)
        {
            wp_nonce_field($this->config['id'] . '_ax', $this->config['id'] . '_nc');
            echo '<h3>' . esc_html($this->config['title']) . '</h3>';
            $this->render_pll_badge();
            $this->render_pll_lang_switcher('user');
            foreach ($this->tabs as $tab) {
                echo '<table class="form-table">';
                if (isset($tab['label']))
                    echo '<tr><th colspan="2"><h4 style="margin:0;">' . esc_html($tab['label']) . '</h4></th></tr>';
                foreach ($tab['fields'] as $field) {
                    if (isset($field['type']) && $field['type'] === 'heading')
                        continue;
                    if (!isset($field['id']))
                        continue;
                    $meta_key = $this->get_meta_key($field['id']);
                    $val = metadata_exists('user', $user->ID, $meta_key) ? get_user_meta($user->ID, $meta_key, true) : false;

                    // Fallback for default language: if base key not found, check suffixed key
                    if ($val === false && $this->pll_active && $meta_key === $field['id']) {
                        $current_lang = $this->get_pll_lang();
                        if ($current_lang && $current_lang === (function_exists('pll_default_language') ? pll_default_language('slug') : '')) {
                            $suffixed_key = $meta_key . '_' . $current_lang;
                            if (metadata_exists('user', $user->ID, $suffixed_key)) {
                                $val = get_user_meta($user->ID, $suffixed_key, true);
                            }
                        }
                    }

                    echo '<tr><th><label for="' . esc_attr($field['id']) . '">' . esc_html($field['label']) . '</label></th><td>';
                    $this->render_field_control($field, $val);
                    echo '</td></tr>';
                }
                echo '</table>';
            }
        }

        public function render_options()
        {
            echo '<div class="wrap"><h1>' . esc_html($this->config['title']) . '</h1>';
            $this->render_pll_lang_switcher('option');
            echo '<form method="post" action="options.php">';
            settings_fields($this->config['id'] . '_grp');
            $this->render_box();
            settings_errors();
            submit_button();
            echo '</form></div>';
        }

        /**
         * Render language switcher links for options/user pages.
         */
        private function render_pll_lang_switcher($context = 'option')
        {
            if (!$this->pll_active) {
                return;
            }
            $languages = pll_the_languages(['raw' => 1]);
            if (!is_array($languages) || count($languages) < 2) {
                return;
            }
            $current_lang = $this->get_pll_lang();

            echo '<div class="wrf-pll-lang-switcher">';
            foreach ($languages as $l) {
                $slug = $l['slug'] ?? '';
                $name = $l['name'] ?? $slug;
                $flag = $l['flag'] ?? '';
                $active_class = ($slug === $current_lang) ? ' active' : '';

                if ($context === 'option') {
                    $url = admin_url('admin.php?page=' . $this->config['id'] . '&lang=' . $slug);
                }
                elseif ($context === 'user') {
                    $url = add_query_arg('lang', $slug);
                }
                else {
                    $url = add_query_arg('lang', $slug);
                }

                echo '<a href="' . esc_url($url) . '" class="' . esc_attr(trim($active_class)) . '">';
                if ($flag) {
                //echo '<span class="wrf-pll-flag">' . $flag . '</span> ';
                }
                echo esc_html($name);
                echo '</a>';
            }
            echo '</div>';
        }

        public function render_box($post = null)
        {
            $is_option = ($this->config['context'] === 'option');
            $option_name = $this->get_option_name();
            $opt_val = $is_option ? get_option($option_name, []) : [];
            if (!is_array($opt_val))
                $opt_val = [];
            if (!$is_option) {
                wp_nonce_field($this->config['id'] . '_ax', $this->config['id'] . '_nc');
            }
            $count = 1;
?>
            <div class="wrf-wrap" id="<?php echo esc_attr($this->config['id']); ?>">
                <?php if ($is_option) {
                $this->render_pll_badge();
            }?>
                <?php if (!$is_option && $this->config['context'] === 'post') {
                $this->render_pll_badge();
            }?>
                <ul class="wrf-nav">
                    <?php foreach ($this->tabs as $key => $tab):
                $class = ($count === 1) ? 'class="active"' : ''; ?>
                        <li><a <?php echo $class; ?>
                                href="#tab-<?php echo esc_attr($key); ?>"><?php echo esc_html($tab['label']); ?></a></li>
                        <?php $count++;
            endforeach; ?>
                </ul>
                <div class="wrf-content">
                    <?php $i = 1;
            foreach ($this->tabs as $key => $tab):
                $active_class = ($i === 1) ? ' active' : ''; ?>
                        <div id="tab-<?php echo esc_attr($key); ?>" class="wrf-tab <?php echo $active_class; ?>">
                            <?php if (!empty($tab['fields'])):
                    foreach ($tab['fields'] as $field):
                        if (isset($field['type']) && $field['type'] === 'heading') {
                            echo '<h3 class="wrf-section-title">' . esc_html($field['label']) . '</h3>';
                            continue;
                        }
                        if (!isset($field['id']))
                            continue;
                        if ($is_option) {
                            $val = isset($opt_val[$field['id']]) ? $opt_val[$field['id']] : false;

                            // Fallback for default language options
                            if ($val === false && $this->pll_active) {
                                $current_lang = $this->get_pll_lang();
                                if ($current_lang && $current_lang === (function_exists('pll_default_language') ? pll_default_language('slug') : '') && $option_name === $this->config['id'] . '_opts') {
                                    $suffixed_opt_name = $this->config['id'] . '_opts_' . $current_lang;
                                    $suffixed_opts = get_option($suffixed_opt_name, []);
                                    if (isset($suffixed_opts[$field['id']]) && $suffixed_opts[$field['id']] !== '') {
                                        $val = $suffixed_opts[$field['id']];
                                    }
                                }
                            }

                            $field['name'] = $option_name . '[' . $field['id'] . ']';
                        }
                        else {
                            $meta_key = $this->get_meta_key($field['id']);
                            $val = metadata_exists('post', $post->ID, $meta_key) ? get_post_meta($post->ID, $meta_key, true) : false;

                            // Fallback for default language: if base key not found, check suffixed key
                            if ($val === false && $this->pll_active && $meta_key === $field['id']) {
                                $current_lang = $this->get_pll_lang();
                                if ($current_lang && $current_lang === (function_exists('pll_default_language') ? pll_default_language('slug') : '')) {
                                    $suffixed_key = $meta_key . '_' . $current_lang;
                                    if (metadata_exists('post', $post->ID, $suffixed_key)) {
                                        $val = get_post_meta($post->ID, $suffixed_key, true);
                                    }
                                }
                            }

                            $field['name'] = $meta_key;
                        }
?>
                                    <div class="wrf-field"><label class="wrf-label"><?php echo esc_html($field['label']); ?></label>
                                        <?php $this->render_field_control($field, $val); ?>
                                    </div>
                                <?php
                    endforeach;
                endif; ?>
                        </div>
                        <?php $i++;
            endforeach; ?>
                </div>
            </div>
            <?php
        }

        /** --- FIELD CONTROLS --- */
        private function render_field_control($field, $val, $name_override = null)
        {
            $type = $field['type'] ?? 'text';
            $id = $field['id'];
            $name = $name_override ? $name_override : ($field['name'] ?? $id);
            $ph = $field['placeholder'] ?? '';
            $desc = $field['desc'] ?? '';

            // Use 'default' value if no saved value exists
            if ($val === false && isset($field['default'])) {
                $val = $field['default'];
            }
            if ($val === false) {
                $val = '';
            }

            switch ($type) {
                case 'text':
                case 'url':
                case 'email':
                case 'number':
                case 'date':
                case 'password':
                    echo '<input type="' . esc_attr($type) . '" name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" value="' . esc_attr($val) . '" class="widefat" placeholder="' . esc_attr($ph) . '">';
                    break;
                case 'textarea':
                    echo '<textarea name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" rows="4" class="widefat">' . esc_textarea($val) . '</textarea>';
                    break;
                case 'raw_html':
                    echo '<textarea name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" rows="8" class="widefat" style="font-family:monospace; background:#fafafa; border:1px solid #999;">' . esc_textarea($val) . '</textarea>';
                    echo '<p class="description" style="color:#d63638;">⚠️ Caution: HTML and Scripts allowed. Only Admins can save this.</p>';
                    break;
                case 'wp_editor':
                    if ($name_override) {
                        echo '<textarea name="' . esc_attr($name) . '" rows="5" class="widefat">' . esc_textarea($val) . '</textarea>';
                    }
                    else {
                        // TinyMCE IDs must be lowercase and have no hyphens to prevent JS errors
                        $editor_id = str_replace(['-', ' '], '_', strtolower($id));
                        wp_editor($val, $editor_id, ['textarea_name' => $name, 'media_buttons' => true, 'textarea_rows' => 5, 'teeny' => true]);
                    }
                    break;
                case 'select':
                    $multiple = !empty($field['multiple']) ? ' multiple size="5" style="height:auto;"' : '';
                    $name_attr = $name . (!empty($field['multiple']) ? '[]' : '');
                    echo '<select name="' . esc_attr($name_attr) . '" id="' . esc_attr($id) . '" class="widefat"' . $multiple . '>';
                    if (empty($field['multiple'])) {
                        echo '<option value="">' . __('Select...', 'tqb') . '</option>';
                    }
                    foreach ($field['options'] as $k => $v) {
                        $is_selected = is_array($val) ? in_array($k, $val) : ((string)$val === (string)$k);
                        echo '<option value="' . esc_attr($k) . '" ' . ($is_selected ? 'selected="selected"' : '') . '>' . esc_html($v) . '</option>';
                    }
                    echo '</select>';
                    break;
                case 'radio':
                    foreach ($field['options'] as $k => $v)
                        echo '<label style="margin-right:15px"><input type="radio" name="' . esc_attr($name) . '" value="' . $k . '" ' . checked($val, $k, false) . '> ' . esc_html($v) . '</label>';
                    break;
                case 'checkbox':
                    echo '<label><input type="checkbox" name="' . esc_attr($name) . '" value="1" ' . checked($val, 1, false) . '> ' . esc_html($desc) . '</label>';
                    $desc = '';
                    break;
                case 'color':
                    echo '<input type="text" name="' . esc_attr($name) . '" value="' . esc_attr($val) . '" class="wrf-color">';
                    break;
                case 'gallery':
                    $ids = $val ? explode(',', $val) : [];
                    echo '<div class="wrf-gallery-wrap">';
                    echo '<input type="hidden" name="' . esc_attr($name) . '" value="' . esc_attr($val) . '">';
                    echo '<div class="wrf-gallery-thumbs">';
                    foreach ($ids as $img_id) {
                        if ($url = wp_get_attachment_thumb_url($img_id))
                            echo '<div class="wrf-thumb" data-id="' . $img_id . '"><img src="' . $url . '"><span class="wrf-remove-img">x</span></div>';
                    }
                    echo '</div><button type="button" class="button wrf-gallery-add">Add Images</button></div>';
                    break;
                case 'post_select':
                    $post_type = $field['post_type'] ?? 'post';
                    $is_multiple = !empty($field['multiple']);
                    $name_attr = $name . ($is_multiple ? '[]' : '');
                    $posts = get_posts([
                        'post_type' => $post_type,
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC'
                    ]);

                    // Build selected value(s) and label for single
                    $selected_label = '';
                    $selected_vals = is_array($val) ? array_map('strval', $val) : ($val ? [strval($val)] : []);
                    if (!$is_multiple && $val) {
                        foreach ($posts as $p) {
                            if ((string)$val === (string)$p->ID) {
                                $selected_label = $p->post_title;
                                break;
                            }
                        }
                    }

                    $multiple_attr = $is_multiple ? ' data-multiple="1"' : '';
                    echo '<div class="wrf-searchable-select" data-name="' . esc_attr($name) . '"' . $multiple_attr . '>';

                    // Tags area for multiple
                    if ($is_multiple) {
                        echo '<div class="wrf-ss-tags">';
                        foreach ($posts as $p) {
                            if (in_array((string)$p->ID, $selected_vals)) {
                                echo '<span class="wrf-ss-tag" data-value="' . esc_attr($p->ID) . '">' . esc_html($p->post_title) . ' <span class="wrf-ss-tag-remove">&times;</span></span>';
                            }
                        }
                        echo '</div>';
                    }

                    // Search input
                    echo '<input type="text" class="wrf-ss-input" autocomplete="off" placeholder="' . esc_attr(__('Type to search', 'tqb') . ' ' . $post_type . '...') . '" value="' . esc_attr($selected_label) . '">';

                    // Hidden input(s) for actual value
                    if ($is_multiple) {
                        foreach ($selected_vals as $sv) {
                            echo '<input type="hidden" class="wrf-ss-hidden" name="' . esc_attr($name_attr) . '" value="' . esc_attr($sv) . '">';
                        }
                    }
                    else {
                        echo '<input type="hidden" class="wrf-ss-hidden" name="' . esc_attr($name_attr) . '" value="' . esc_attr($val) . '">';
                    }

                    // Dropdown options
                    echo '<div class="wrf-ss-dropdown">';
                    foreach ($posts as $p) {
                        $is_selected = in_array((string)$p->ID, $selected_vals);
                        echo '<div class="wrf-ss-option' . ($is_selected ? ' selected' : '') . '" data-value="' . esc_attr($p->ID) . '">' . esc_html($p->post_title) . '</div>';
                    }
                    echo '<div class="wrf-ss-no-results" style="display:none;">' . __('No results found', 'tqb') . '</div>';
                    echo '</div>'; // .wrf-ss-dropdown
                    echo '</div>'; // .wrf-searchable-select
                    break;

                // --- GROUP FIELD UPDATED FOR COLLAPSE ---
                case 'group':
                    $rows = is_array($val) ? $val : [];
                    if (isset($rows['{{i}}']))
                        unset($rows['{{i}}']);
                    echo '<div class="wrf-group-wrapper">';

                    // Existing Rows
                    foreach ($rows as $i => $row_data) {
                        if ($i === '{{i}}')
                            continue;
                        // Added class 'closed' by default
                        echo '<div class="wrf-group-row closed">';

                        // Header
                        echo '<div class="wrf-group-header">';
                        echo '<span class="wrf-group-title">Item</span>';
                        echo '<div class="wrf-group-controls"><span class="wrf-remove-row">Remove</span> <span class="wrf-toggle-icon">�?/span></div>';
                        echo '</div>';

                        // Content
                        echo '<div class="wrf-group-content">';
                        foreach ($field['sub_fields'] as $sub) {
                            $sub_val = isset($row_data[$sub['id']]) ? $row_data[$sub['id']] : false;
                            echo '<div style="margin-bottom:10px"><label><strong>' . esc_html($sub['label']) . '</strong></label>';
                            $this->render_field_control($sub, $sub_val, $name . '[' . $i . '][' . $sub['id'] . ']');
                            echo '</div>';
                        }
                        echo '</div>'; // End Content
                        echo '</div>'; // End Row
                    }

                    // Template for JS
                    ob_start();
                    foreach ($field['sub_fields'] as $sub) {
                        echo '<div style="margin-bottom:10px"><label><strong>' . esc_html($sub['label']) . '</strong></label>';
                        $this->render_field_control($sub, false, $name . '[{{i}}][' . $sub['id'] . ']');
                        echo '</div>';
                    }
                    $tpl_html = ob_get_clean();
                    $tpl_html = str_replace('name="', 'data-name="', $tpl_html);

                    echo '<div class="wrf-group-tpl" style="display:none">';
                    // Added class 'closed' to template too
                    echo '<div class="wrf-group-row closed">';
                    echo '<div class="wrf-group-header"><span class="wrf-group-title">Item</span><div class="wrf-group-controls"><span class="wrf-remove-row">Remove</span> <span class="wrf-toggle-icon">�?/span></div></div>';
                    echo '<div class="wrf-group-content">';
                    echo $tpl_html;
                    echo '</div></div></div>'; // End Content, End Row, End Tpl

                    echo '<button type="button" class="button button-primary wrf-add-row">' . ($field['button'] ?? 'Add Row') . '</button></div>';
                    break;

                // --- SET FIELD (repeatable text input) ---
                case 'set':
                    $items = is_array($val) ? array_values($val) : [];
                    $set_ph = $field['placeholder'] ?? '';
                    echo '<div class="wrf-set-wrapper" data-name="' . esc_attr($name) . '" data-placeholder="' . esc_attr($set_ph) . '">';

                    // Existing items
                    foreach ($items as $item_val) {
                        echo '<div class="wrf-set-item">';
                        echo '<input type="text" name="' . esc_attr($name) . '[]" value="' . esc_attr($item_val) . '" class="widefat" placeholder="' . esc_attr($set_ph) . '">';
                        echo '<span class="wrf-set-remove">&times;</span>';
                        echo '</div>';
                    }

                    echo '<button type="button" class="button button-primary wrf-add-set-item">' . esc_html($field['button'] ?? 'Add Item') . '</button>';
                    echo '</div>';
                    break;

                case 'image':
                case 'file':
                    $return_type = $field['return'] ?? 'url';
                    $preview_src = '';
                    $display_preview = 'none';

                    if ($val) {
                        if ($type === 'image' && $return_type === 'id' && is_numeric($val)) {
                            $preview_src = wp_get_attachment_image_url((int)$val, 'thumbnail');
                            if (!$preview_src) {
                                $preview_src = '';
                            }
                        }
                        else {
                            $preview_src = $val;
                        }
                        if ($preview_src) {
                            $display_preview = 'block';
                        }
                    }

                    echo '<div class="wrf-flex">';
                    echo '<input type="text" name="' . esc_attr($name) . '" value="' . esc_attr($val) . '" class="widefat" data-return="' . esc_attr($return_type) . '">';
                    echo '<button type="button" class="button wrf-upload">Upload</button>';
                    echo '</div>';

                    if ($type == 'image') {
                        echo '<img src="' . esc_url($preview_src) . '" class="wrf-img-preview" style="display:' . esc_attr($display_preview) . '">';
                    }
                    break;
            }
            if ($desc)
                echo '<div class="wrf-desc">' . esc_html($desc) . '</div>';
        }

        /** --- SAVE LOGIC --- */

        public function save_post_meta($post_id)
        {
            if (!isset($_POST[$this->config['id'] . '_nc']) || !wp_verify_nonce($_POST[$this->config['id'] . '_nc'], $this->config['id'] . '_ax'))
                return;
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return;
            if (!current_user_can('edit_post', $post_id))
                return;
            // Refresh Polylang language from POST data for save context
            if ($this->pll_active && isset($_POST['post_lang_choice'])) {
                $this->pll_lang = sanitize_text_field($_POST['post_lang_choice']);
            }
            $this->save_logic($post_id, 'post');
        }

        public function save_term_meta($term_id)
        {
            if (!isset($_POST[$this->config['id'] . '_nc']) || !wp_verify_nonce($_POST[$this->config['id'] . '_nc'], $this->config['id'] . '_ax'))
                return;
            $this->save_logic($term_id, 'term');
        }

        public function save_user_meta($user_id)
        {
            if (!isset($_POST[$this->config['id'] . '_nc']) || !wp_verify_nonce($_POST[$this->config['id'] . '_nc'], $this->config['id'] . '_ax'))
                return;
            if (!current_user_can('edit_user', $user_id))
                return;
            $this->save_logic($user_id, 'user');
        }

        private function save_logic($id, $type)
        {
            $current_lang = $this->pll_active ? $this->get_pll_lang() : '';
            $default_lang = ($this->pll_active && function_exists('pll_default_language')) ? pll_default_language('slug') : '';
            $is_default = ($current_lang && $current_lang === $default_lang);

            foreach ($this->flat_fields as $field) {
                if (!isset($field['id']))
                    continue;
                $original_key = $field['id'];
                $k = $this->get_meta_key($original_key);

                // For post meta, POSTed field name uses the meta key (with suffix)
                // For term/user meta, POSTed field name uses the original key
                $post_key = ($type === 'post') ? $k : $original_key;

                if ($field['type'] === 'checkbox' && !isset($_POST[$post_key]))
                    $v = 0;
                else
                    $v = isset($_POST[$post_key]) ? $_POST[$post_key] : '';

                if (isset($field['type']) && $field['type'] === 'raw_html') {
                    if (current_user_can('unfiltered_html')) {
                    // Keep as is
                    }
                    else {
                        $v = wp_kses_post($v);
                    }
                }
                else {
                    $v = $this->recursive_sanitize_and_clean($v);
                }

                if ($type === 'post')
                    update_post_meta($id, $k, $v);
                if ($type === 'term')
                    update_term_meta($id, $k, $v);
                if ($type === 'user')
                    update_user_meta($id, $k, $v);

                // Sync to suffixed key if this is the default language (using the base key as $k)
                if ($is_default && $k === $original_key) {
                    $suffixed_key = $original_key . '_' . $current_lang;
                    if ($type === 'post')
                        update_post_meta($id, $suffixed_key, $v);
                    if ($type === 'term')
                        update_term_meta($id, $suffixed_key, $v);
                    if ($type === 'user')
                        update_user_meta($id, $suffixed_key, $v);
                }
            }
        }

        private function recursive_sanitize_and_clean($data)
        {
            if (is_array($data)) {
                if (isset($data['{{i}}']))
                    unset($data['{{i}}']);
                foreach ($data as $key => $value) {
                    $data[$key] = $this->recursive_sanitize_and_clean($value);
                }
                return $data;
            }
            if (current_user_can('unfiltered_html')) {
                return $data;
            }
            return wp_kses_post($data);
        }
    }
}
?>
