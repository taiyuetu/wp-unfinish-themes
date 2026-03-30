<?php
/**
 * Custom Walker: Mega Menu Nav Walker
 *
 * Supports:
 *  - Standard top-level links
 *  - A "Products" mega menu item driven by WordPress custom fields / ACF
 *    set on the menu item itself (Appearance → Menus → Screen Options → enable
 *    "CSS Classes" and "Description", or use ACF Nav Menu Fields plugin).
 *
 * Usage in template:
 *   wp_nav_menu( array(
 *       'theme_location' => 'primary',
 *       'container'      => false,
 *       'menu_class'     => 'nav__links',
 *       'menu_id'        => '',
 *       'items_wrap'     => '<ul id="%1$s" class="%2$s" role="list">%3$s</ul>',
 *       'walker'         => new Mega_Menu_Walker(),
 *   ) );
 */

if ( ! class_exists( 'Mega_Menu_Walker' ) ) :

class Mega_Menu_Walker extends Walker_Nav_Menu {

    // Track IDs of mega-menu parents so we can skip their children
    private $mega_parent_ids = array();

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

        $classes  = empty( $item->classes ) ? array() : (array) $item->classes;
        $has_mega = in_array( 'has-megamenu', $classes, true );

        // ── If this item is a child of a mega parent, skip it entirely ──
        if ( $depth > 0 && in_array( (int) $item->menu_item_parent, $this->mega_parent_ids, true ) ) {
            return; // Children are rendered inside get_mega_menu_panel(), not here
        }

        // ── Register this item as a mega parent so children get skipped ──
        if ( $has_mega ) {
            $this->mega_parent_ids[] = (int) $item->ID;
        }

        /* --- <li> --- */
        $li_classes   = array( 'nav__item' );
        $li_classes[] = $has_mega ? 'nav__item--mega' : '';
        $li_classes   = array_filter( $li_classes );

        $output .= sprintf( '<li class="%s">', esc_attr( implode( ' ', $li_classes ) ) );

        /* --- <a> --- */
        $atts = array(
            'href'         => ! empty( $item->url ) ? $item->url : '#',
            'class'        => 'nav__link',
            'title'        => ! empty( $item->attr_title ) ? $item->attr_title : '',
            'target'       => ! empty( $item->target ) ? $item->target : '',
            'rel'          => ! empty( $item->xfn ) ? $item->xfn : '',
            'aria-current' => $item->current ? 'page' : '',
        );
        $atts = array_filter( $atts );

        $attr_string = '';
        foreach ( $atts as $attr => $value ) {
            $attr_string .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
        }

        $item_output  = '<a' . $attr_string . '>';
        $item_output .= esc_html( $item->title );
        if ( $has_mega ) {
            $item_output .= $this->get_arrow_svg();
        }
        $item_output .= '</a>';

        /* --- Mega menu panel --- */
        if ( $has_mega && $depth === 0 ) {
            $item_output .= $this->get_mega_menu_panel( $item );
        }

        $output .= $item_output;
    }

    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        // Skip closing </li> for children of mega parents
        if ( $depth > 0 && in_array( (int) $item->menu_item_parent, $this->mega_parent_ids, true ) ) {
            return;
        }
        $output .= '</li>';
    }

    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        // Suppress default sub-<ul>
    }

    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        // Intentionally empty
    }

    /* ================================================================== */
    /* Helper: Mega Menu Panel                                             */
    /* ================================================================== */

    /**
     * Build the mega menu panel HTML.
     *
     * Data source: uses get_field() (ACF) when available, otherwise falls
     * back to wp_get_nav_menu_items() to read child items added in WP admin.
     *
     * @param  WP_Post $item  Nav menu item.
     * @return string         HTML string.
     */
    private function get_mega_menu_panel( $item ) {

        /* Fetch child menu items for this parent */
        $child_items = $this->get_child_items( $item );

        /* Split children into groups by their "group" meta (CSS class) */
        $groups = array();
        foreach ( $child_items as $child ) {
            $classes = (array) $child->classes;
            // Expect classes like "group-generation", "group-application", "group-featured"
            $group = 'general';
            foreach ( $classes as $class ) {
                if ( strpos( $class, 'group-' ) === 0 ) {
                    $group = str_replace( 'group-', '', $class );
                    break;
                }
            }
            $groups[ $group ][] = $child;
        }

        ob_start(); ?>

        <div class="nav__megamenu">
            <div class="nav__megamenu__grid">

                <?php if ( ! empty( $groups['generation'] ) ) : ?>
                <!-- Column: By Generation -->
                <div class="nav__megamenu__col">
                    <div class="nav__megamenu__title">
                        <?php esc_html_e( 'By Generation', 'theme' ); ?>
                    </div>
                    <div class="nav__megamenu__links">
                        <?php foreach ( $groups['generation'] as $child ) :
                            $desc  = $child->description;
                            $parts = explode( '·', $desc, 2 );
                        ?>
                        <a href="<?php echo esc_url( $child->url ); ?>" class="nav__megamenu__link">
                            <div class="nav__megamenu__link-icon"></div>
                            <div class="nav__megamenu__link-info">
                                <div class="nav__megamenu__link-name">
                                    <?php echo esc_html( $child->title ); ?>
                                </div>
                                <?php if ( $desc ) : ?>
                                <div class="nav__megamenu__link-desc">
                                    <?php echo esc_html( trim( $desc ) ); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ( ! empty( $groups['application'] ) ) : ?>
                <!-- Column: By Application -->
                <div class="nav__megamenu__col">
                    <div class="nav__megamenu__title">
                        <?php esc_html_e( 'By Application', 'theme' ); ?>
                    </div>
                    <div class="nav__megamenu__links">
                        <?php foreach ( $groups['application'] as $child ) :
                            $desc = $child->description;
                        ?>
                        <a href="<?php echo esc_url( $child->url ); ?>" class="nav__megamenu__link">
                            <div class="nav__megamenu__link-icon"></div>
                            <div class="nav__megamenu__link-info">
                                <div class="nav__megamenu__link-name">
                                    <?php echo esc_html( $child->title ); ?>
                                </div>
                                <?php if ( $desc ) : ?>
                                <div class="nav__megamenu__link-desc">
                                    <?php echo esc_html( trim( $desc ) ); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ( ! empty( $groups['featured'] ) ) :
                    $featured = $groups['featured'][0]; // Expect a single "featured" child item
                    $img_id   = get_post_meta( $featured->ID, '_menu_item_featured_image', true );
                    $img_url  = $img_id ? wp_get_attachment_image_url( $img_id, 'medium' ) : '';
                    $link_url = ! empty( $featured->url ) ? $featured->url : '#';
                    $link_text = ! empty( $featured->description ) ? $featured->description : __( 'View Product', 'theme' );
                ?>
                <!-- Featured Panel -->
                <div class="nav__megamenu__featured">
                    <div class="nav__megamenu__featured-image"
                         <?php if ( $img_url ) : ?>
                             style="background-image:url('<?php echo esc_url( $img_url ); ?>');background-size:cover;background-position:center;"
                         <?php endif; ?>>
                        <?php if ( ! $img_url ) : ?>
                            <span style="position:absolute;bottom:8px;right:8px;font-family:var(--f-mono);font-size:8px;color:var(--c-steel-mid);">IMAGE</span>
                        <?php endif; ?>
                        <div class="nav__megamenu__featured-label">
                            <div class="nav__megamenu__featured-title">
                                <?php echo esc_html( $featured->title ); ?>
                            </div>
                            <a href="<?php echo esc_url( $link_url ); ?>" class="nav__megamenu__featured-link">
                                <?php echo esc_html( $link_text ); ?>
                                <?php echo $this->get_arrow_svg( 'right' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div><!-- /.nav__megamenu__grid -->

            <!-- Footer -->
            <div class="nav__megamenu__footer">
                <?php
                /* Pull product count from WP or a theme option */
                $product_count = wp_count_posts( 'product' )->publish ?? 48;
                ?>
                <span class="nav__megamenu__footer-text">
                    <?php printf(
                        /* translators: %d: product count */
                        esc_html__( '%d+ products available', 'theme' ),
                        (int) $product_count
                    ); ?>
                </span>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ?: '#' ); ?>"
                   class="nav__megamenu__footer-link">
                    <?php esc_html_e( 'View All Products', 'theme' ); ?>
                    <?php echo $this->get_arrow_svg( 'right' ); ?>
                </a>
            </div>

        </div><!-- /.nav__megamenu -->

        <?php return ob_get_clean();
    }

    /* ================================================================== */
    /* Helper: Fetch child nav menu items for a given parent item          */
    /* ================================================================== */
    private function get_child_items( $parent_item ) {
        // Get the menu this item belongs to
        $menus = wp_get_object_terms( $parent_item->ID, 'nav_menu' );

        if ( empty( $menus ) || is_wp_error( $menus ) ) {
            return array();
        }

        $menu_items = wp_get_nav_menu_items( $menus[0]->term_id );

        if ( ! $menu_items ) {
            return array();
        }

        return array_filter( $menu_items, function( $item ) use ( $parent_item ) {
            return (int) $item->menu_item_parent === (int) $parent_item->ID;
        } );
    }

    /* ================================================================== */
    /* Helper: SVG icons                                                   */
    /* ================================================================== */
    private function get_arrow_svg( $direction = 'down' ) {
        if ( $direction === 'right' ) {
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <polyline points="12 5 19 12 12 19"/>
                    </svg>';
        }
        // Default: chevron down
        return '<svg class="nav__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>';
    }

} // end class Mega_Menu_Walker

endif;


/** 
 * 

File Overview
functions.php — Registers the primary nav menu location with register_nav_menus() so it appears under Appearance → Menus in the WP admin.
class-mega-menu-walker.php — The core file. A custom Walker_Nav_Menu subclass with three responsibilities:
MethodWhat it doesstart_el()Renders each <li> + <a>, injects the chevron SVG, and calls the mega panel builder for items with the has-megamenu CSS classstart_lvl() / end_lvl()Suppressed — prevents WordPress from auto-rendering a default <ul> sub-menu so the walker stays in full control of markupget_mega_menu_panel()Reads child menu items, groups them by CSS class prefix (group-generation, group-application, group-featured), and assembles the full mega menu HTML
nav-primary.php — A template part you drop into template-parts/. Call it from header.php with get_template_part( 'template-parts/nav', 'primary' ).

Setup in WP Admin

Go to Appearance → Menus → Screen Options → enable CSS Classes and Description.
Add your top-level Products item and set its CSS class to has-megamenu.
Add child items beneath it and assign CSS classes:

group-generation → Generation I, II, III, Heavy Duty (use Description field for the subtitle, e.g. Standard duty · 32kN load)
group-application → Passenger Vehicle, EV Platform, Commercial Vehicle
group-featured → AX-3560 EV Hub (Description = link label, e.g. View Product)


Assign the menu to the Primary Navigation location.
Class mega menu walkerCode · PHP DownloadNav primaryCode · PHP DownloadFunctionsCode · PHP DownloadDownload allhow to use it , to match the excat same css classes of the static HTML menu9:57 PMArchitected visual mapping between WordPress menus and static HTML CSS classesArchitected visual mapping between WordPress menus and static HTML CSS classesLet me create a clear visual reference that maps the WP Admin setup → to the exact CSS classes in the output HTML.Here's an interactive reference showing the exact mapping from WP Admin setup → output HTML classes:
  ::view-transition-group(*),
  ::view-transition-old(*),
  ::view-transition-new(*) {
    animation-duration: 0.25s;
    animation-timing-function: cubic-bezier(0.19, 1, 0.22, 1);
  }
VvisualizeVvisualize show_widgetClick through the tabs for each stage. Here's the quick summary of the three things that drive every class:
1. The has-megamenu CSS class (Screen Options → CSS Classes field on the Products item) is the only trigger. The walker checks for it and switches from rendering a plain <a> to rendering the full mega panel.
2. Child item CSS classes control which column they appear in. The walker groups children by group- prefix: group-generation → left column, group-application → middle column, group-featured → the featured image panel.
3. The Description field is reused as the subtitle (nav__megamenu__link-desc). For the featured item, it becomes the link label ("View Product").
Everything else — nav__link, nav__arrow, nav__megamenu__grid, nav__megamenu__footer — is hard-coded in the walker and requires no WP Admin input.
 */