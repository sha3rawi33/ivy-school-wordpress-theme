<?php
/**
 * Custom Functions
 */

/**
 * Check a plugin active
 *
 * @param $plugin_dir
 * @param $plugin_file
 *
 * @return bool
 */


function thim_plugin_active( $plugin_dir, $plugin_file = null ) {
	$plugin_file            = $plugin_file ? $plugin_file : ( $plugin_dir . '.php' );
	$plugin                 = $plugin_dir . '/' . $plugin_file;
	$active_plugins_network = get_site_option( 'active_sitewide_plugins' );

	if ( isset( $active_plugins_network[ $plugin ] ) ) {
		return true;
	}

	$active_plugins = get_option( 'active_plugins' );

	if ( in_array( $plugin, $active_plugins ) ) {
		return true;
	}

	return false;
}

/**
 * Get header layouts
 *
 * @return string CLASS for header layouts
 */
function thim_header_layout_class() {
	if ( get_theme_mod( 'header_position', 'overlay' ) === 'default' ) {
		echo ' header-default';
	} else {
		echo ' header-overlay';
	}

	if ( get_theme_mod( 'show_sticky_menu', true ) ) {
		echo ' sticky-header';
	}

	if ( get_theme_mod( 'sticky_menu_style', 'same' ) === 'custom' ) {
		echo ' custom-sticky';
	} else {
		echo '';
	}

	if ( get_theme_mod( 'header_retina_logo', false ) ) {
		echo ' has-retina-logo';
	}

	echo ' ' . get_theme_mod( 'header_style', 'header_v1' );

	// Check header background color has opacity 0
	preg_match_all( '/\d+/', get_theme_mod( 'header_background_color', '#ffffff' ), $header_bg_color );
	$header_color_arr = array_filter( $header_bg_color[0] );

	if ( $header_color_arr && 3 == count( $header_color_arr ) ) {
		echo ' transparent';
	}
}

/**
 * Get Header Logo
 *
 * @return string
 */
if ( ! function_exists( 'thim_header_logo' ) ) {
	function thim_header_logo() {
		$thim_options         = get_theme_mods();
		$thim_logo_src        = THIM_URI . "assets/images/retina-logo.png";
		$thim_mobile_logo_src = THIM_URI . "assets/images/retina-logo.png";
		$thim_retina_logo_src = THIM_URI . "assets/images/retina-logo.png";

		if ( isset( $thim_options['header_logo'] ) && $thim_options['header_logo'] <> '' ) {
			$thim_logo_src = get_theme_mod( 'header_logo' );
			if ( is_numeric( $thim_logo_src ) ) {
				$logo_attachment = wp_get_attachment_image_src( $thim_logo_src, 'full' );
				$thim_logo_src   = $logo_attachment[0];
			}
		}

		if ( isset( $thim_options['mobile_logo'] ) && $thim_options['mobile_logo'] <> '' ) {
			$thim_mobile_logo_src = get_theme_mod( 'mobile_logo' );
			if ( is_numeric( $thim_mobile_logo_src ) ) {
				$logo_attachment      = wp_get_attachment_image_src( $thim_mobile_logo_src, 'full' );
				$thim_mobile_logo_src = $logo_attachment[0];
			}
		}
		echo '<a class="no-sticky-logo" href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ) . '" rel="home">';
		echo '<img class="logo" src="' . esc_url( $thim_logo_src ) . '" alt="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" />';


        if ( get_theme_mod( 'header_retina_logo', false ) ) {
            $thim_retina_logo_src = get_theme_mod( 'header_retina_logo' );
            if ( is_numeric( $thim_retina_logo_src ) ) {
                $logo_attachment      = wp_get_attachment_image_src( $thim_retina_logo_src, 'full' );
                $thim_retina_logo_src = $logo_attachment[0];
            }

        }
        if($thim_retina_logo_src) {
            echo '<img class="retina-logo" src="' . esc_url( $thim_retina_logo_src ) . '" alt="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" />';
        }


		echo '<img class="mobile-logo" src="' . esc_url( $thim_mobile_logo_src ) . '" alt="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" />';
		echo '</a>';
	}
}
add_action( 'thim_header_logo', 'thim_header_logo' );

/**
 * Get Header Sticky logo
 *
 * @return string
 */
if ( ! function_exists( 'thim_header_sticky_logo' ) ) {
	function thim_header_sticky_logo() {
        $thim_logo_stick_logo_src        = THIM_URI . "assets/images/retina-logo.png";
		if ( get_theme_mod( 'header_sticky_logo' ) != '' ) {
			$thim_logo_stick_logo     = get_theme_mod( 'header_sticky_logo' );
			$thim_logo_stick_logo_src = $thim_logo_stick_logo; // For the default value
			if ( is_numeric( $thim_logo_stick_logo ) ) {
				$logo_attachment = wp_get_attachment_image_src( $thim_logo_stick_logo, 'full' );
				if ( $logo_attachment ) {
					$thim_logo_stick_logo_src = $logo_attachment[0];
				} else {
					$thim_logo_stick_logo_src = THIM_URI . 'assets/images/sticky-logo.png';
				}
			}
			$thim_logo_size = @getimagesize( $thim_logo_stick_logo_src );
			$logo_size      = $thim_logo_size[3];
			$site_title     = esc_attr( get_bloginfo( 'name', 'display' ) );
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ) . '" rel="home" class="sticky-logo">
					<img src="' . $thim_logo_stick_logo_src . '" alt="' . $site_title . '" ' . $logo_size . ' /></a>';
		} else {
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ) . '" rel="home" class="sticky-logo">
			        <img src="' . $thim_logo_stick_logo_src . '" alt="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" /></a>';
		}
	}
}
add_action( 'thim_header_sticky_logo', 'thim_header_sticky_logo' );

/**
 * Get Page Title Content For Single
 *
 * @return string HTML for Page title bar
 */
function thim_get_single_page_title_content() {
	$post_id = get_the_ID();

	if ( get_post_type( $post_id ) == 'post' ) {
		$categories = get_the_category();
	} elseif ( get_post_type( $post_id ) == 'attachment' ) {
		echo '<h2 class="title">' . esc_html__( 'Attachment', 'ivy-school' ) . '</h2>';

		return;
	} else {// Custom post type
		$categories = get_the_terms( $post_id, 'taxonomy' );
	}
	if ( ! empty( $categories ) ) {
		echo '<h2 class="title">' . esc_html( $categories[0]->name ) . '</h2>';
	}
}

/**
 * Get Page Title Content For Date Format
 *
 * @return string HTML for Page title bar
 */
function thim_get_page_title_date() {
	if ( is_year() ) {
		echo '<h2 class="title">' . esc_html__( 'Year', 'ivy-school' ) . '</h2>';
	} elseif ( is_month() ) {
		echo '<h2 class="title">' . esc_html__( 'Month', 'ivy-school' ) . '</h2>';
	} elseif ( is_day() ) {
		echo '<h2 class="title">' . esc_html__( 'Day', 'ivy-school' ) . '</h2>';
	}

	$date  = '';
	$day   = intval( get_query_var( 'day' ) );
	$month = intval( get_query_var( 'monthnum' ) );
	$year  = intval( get_query_var( 'year' ) );
	$m     = get_query_var( 'm' );

	if ( ! empty( $m ) ) {
		$year  = intval( substr( $m, 0, 4 ) );
		$month = intval( substr( $m, 4, 2 ) );
		$day   = substr( $m, 6, 2 );

		if ( strlen( $day ) > 1 ) {
			$day = intval( $day );
		} else {
			$day = 0;
		}
	}

	if ( $day > 0 ) {
		$date .= $day . ' ';
	}
	if ( $month > 0 ) {
		global $wp_locale;
		$date .= $wp_locale->get_month( $month ) . ' ';
	}
	$date .= $year;
	echo '<div class="description">' . esc_attr( $date ) . '</div>';
}

/**
 * Get Page Title Content
 *
 * @return string HTML for Page title bar
 */
if ( ! function_exists( 'thim_page_title_content' ) ) {
	function thim_page_title_content() {
		if ( is_front_page() ) {// Front page
			echo '<h2 class="title">' . get_bloginfo( 'name' ) . '</h2>';
			echo '<div class="description">' . get_bloginfo( 'description' ) . '</div>';
		} elseif ( is_home() ) {// Post page
			echo '<h2 class="title">' . esc_html__( 'Blog', 'ivy-school' ) . '</h2>';
			echo '<div class="description">' . get_bloginfo( 'description' ) . '</div>';
		} elseif ( is_page() ) {// Page
			echo '<h2 class="title">' . get_the_title() . '</h2>';
		} elseif ( is_single() ) {// Single
			thim_get_single_page_title_content();
		} elseif ( is_author() ) {// Author
			echo '<h2 class="title">' . esc_html__( 'Author', 'ivy-school' ) . '</h2>';
			echo '<div class="description">' . get_the_author() . '</div>';
		} elseif ( is_search() ) {// Search
			echo '<h2 class="title">' . esc_html__( 'Search', 'ivy-school' ) . '</h2>';
			echo '<div class="description">' . get_search_query() . '</div>';
		} elseif ( is_tag() ) {// Tag
			echo '<h2 class="title">' . esc_html__( 'Tag', 'ivy-school' ) . '</h2>';
			echo '<div class="description">' . single_tag_title( '', false ) . '</div>';
		} elseif ( is_category() ) {// Archive
			echo '<h2 class="title">' . esc_html__( 'Category', 'ivy-school' ) . '</h2>';
			echo '<div class="description">' . single_cat_title( '', false ) . '</div>';
		} elseif ( is_404() ) {
			echo '<h2 class="title">' . esc_html__( 'Page Not Found!', 'ivy-school' ) . '</h2>';
		} elseif ( is_date() ) {
			thim_get_page_title_date();
		}
	}
}
add_action( 'thim_page_title_content', 'thim_page_title_content' );

/**
 * Get breadcrumb for page
 *
 * @return string
 */
function thim_get_breadcrumb_items_other() {
	global $author;
	$userdata   = get_userdata( $author );
	$categories = get_the_category();
	if ( is_front_page() ) { // Do not display on the homepage
		return;
	}
	if ( is_home() ) {
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '">' . esc_html__( 'Blog', 'ivy-school' ) . '</span></li>';
	} else if ( is_category() ) { // Category page
        $current_category = get_queried_object();
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">' . esc_html( $current_category->cat_name ) . '</span></li>';
	} else if ( is_tag() ) {
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( single_term_title( '', false ) ) . '">' . esc_html( single_term_title( '', false ) ) . '</span></li>';
	} else if ( is_year() ) {
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_time( 'Y' ) ) . '">' . esc_html( get_the_time( 'Y' ) ) . ' ' . esc_html__( 'Archives', 'ivy-school' ) . '</span></li>';
	} else if ( is_author() ) { // Auhor archive
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( $userdata->display_name ) . '">' . esc_attr__( 'Author', 'ivy-school' ) . ' ' . esc_html( $userdata->display_name ) . '</span></li>';
	} else if ( get_query_var( 'paged' ) ) {
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Page', 'ivy-school' ) . ' ' . get_query_var( 'paged' ) . '">' . esc_html__( 'Page', 'ivy-school' ) . ' ' . esc_html( get_query_var( 'paged' ) ) . '</span></li>';
	} else if ( is_search() ) {
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Search results for:', 'ivy-school' ) . ' ' . esc_attr( get_search_query() ) . '">' . esc_html__( 'Search results for:', 'ivy-school' ) . ' ' . esc_html( get_search_query() ) . '</span></li>';
	} elseif ( is_404() ) {
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( '404 Page', 'ivy-school' ) . '">' . esc_html__( '404 Page', 'ivy-school' ) . '</span></li>';
	}
}

/**
 * Get content breadcrumbs
 *
 * @return string
 */
if ( ! function_exists( 'thim_breadcrumbs' ) ) {
	function thim_breadcrumbs() {
		global $post;
		if ( is_front_page() ) { // Do not display on the homepage
			return;
		}
		$categories   = get_the_category();
		$thim_options = get_theme_mods();
		$icon         = '/';
		if ( isset( $thim_options['breadcrumb_icon'] ) ) {
			$icon = html_entity_decode( get_theme_mod( 'breadcrumb_icon' ) );
		}
		// Build the breadcrums
		echo '<ul itemprop="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs" class="breadcrumbs">';
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr__( 'Home', 'ivy-school' ) . '"><span itemprop="name">' . esc_html__( 'Home', 'ivy-school' ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
		if ( is_single() ) { // Single post (Only display the first category)
            if ( get_post_type() == 'tp_event' ) {
                echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'tp_event' ) ) . '" title="' . esc_attr__( 'Events', 'ivy-school' ) . '"><span itemprop="name">' . esc_html__( 'Events', 'ivy-school' ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
            }
            if ( get_post_type() == 'our_team' ) {
                echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'our_team' ) ) . '" title="' . esc_attr__( 'Our Team', 'ivy-school' ) . '"><span itemprop="name">' . esc_html__( 'Our Team', 'ivy-school' ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
            }
            if ( get_post_type() == "lpr_course" || get_post_type() == "lpr_quiz" || get_post_type() == "lp_course" || get_post_type() == "lp_quiz" || thim_check_is_course() || thim_check_is_course_taxonomy() ) {
                echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_course' ) ) . '" title="' . esc_attr__( 'Courses', 'ivy-school' ) . '"><span itemprop="name">' . esc_html__( 'Courses', 'ivy-school' ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
                $term_course   = get_the_terms($post,'course_category');
                if ( isset( $term_course[0] ) ) {
                    echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_term_link( $term_course[0],'course_category' ) ) . '" title="' . esc_attr( $term_course[0]->name ) . '"><span itemprop="name">' . esc_html( $term_course[0]->name ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
                }
            }
            if ( get_post_type() == "lp_collection" ) {
                echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_collection' ) ) . '" title="' . esc_attr__( 'Collections', 'ivy-school' ) . '"><span itemprop="name">' . esc_html__( 'Collections', 'ivy-school' ) . '</span></a></li>';
            }
            if ( isset( $categories[0] ) ) {
                echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" title="' . esc_attr( $categories[0]->cat_name ) . '"><span itemprop="name">' . esc_html( $categories[0]->cat_name ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
            }
            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</span></li>';
		} else if ( is_page() ) {
			// Standard page
			if ( $post->post_parent ) {
				$anc = get_post_ancestors( $post->ID );
				$anc = array_reverse( $anc );
				// Parent page loop
				foreach ( $anc as $ancestor ) {
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( $ancestor ) ) . '" title="' . esc_attr( get_the_title( $ancestor ) ) . '"><span itemprop="name">' . esc_html( get_the_title( $ancestor ) ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
				}
			}
			// Current page
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '"> ' . esc_html( get_the_title() ) . '</span></li>';
		} elseif ( is_day() ) {// Day archive
			// Year link
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '" title="' . esc_attr( get_the_time( 'Y' ) ) . '"><span itemprop="name">' . esc_html( get_the_time( 'Y' ) ) . ' ' . esc_html__( 'Archives', 'ivy-school' ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
			// Month link
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ) . '" title="' . esc_attr( get_the_time( 'M' ) ) . '"><span itemprop="name">' . esc_html( get_the_time( 'M' ) ) . ' ' . esc_html__( 'Archives', 'ivy-school' ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
			// Day display
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_time( 'jS' ) ) . '"> ' . esc_html( get_the_time( 'jS' ) ) . ' ' . esc_html( get_the_time( 'M' ) ) . ' ' . esc_html__( 'Archives', 'ivy-school' ) . '</span></li>';

		} else if ( is_month() ) {
			// Year link
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '" title="' . esc_attr( get_the_time( 'Y' ) ) . '"><span itemprop="name">' . esc_html( get_the_time( 'Y' ) ) . ' ' . esc_html__( 'Archives', 'ivy-school' ) . '</span></a><span class="breadcrum-icon">' . ent2ncr( $icon ) . '</span></li>';
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_time( 'M' ) ) . '">' . esc_html( get_the_time( 'M' ) ) . ' ' . esc_html__( 'Archives', 'ivy-school' ) . '</span></li>';
		} elseif ( is_archive() ) {
            if ( get_post_type() == "tp_event" ) {
                if ( get_query_var( 'taxonomy' ) ) {
                    echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">' . esc_html( get_queried_object()->name ) . '</span></li>';
                } else {
                    echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Events', 'ivy-school' ) . '">' . esc_html__( 'Events', 'ivy-school' ) . '</span></li>';
                }

            }
            if ( get_post_type() == "lp_collection" ) {
                if ( get_query_var( 'taxonomy' ) ) {
                    echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">' . esc_html( get_queried_object()->name ) . '</span></li>';
                } else {
                    echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Collections', 'ivy-school' ) . '">' . esc_html__( 'Collections', 'ivy-school' ) . '</span></li>';
                }

            }
            if ( get_post_type() == "lpr_course" || get_post_type() == "lpr_quiz" || get_post_type() == "lp_course" || get_post_type() == "lp_quiz" || thim_check_is_course() || thim_check_is_course_taxonomy() ) {
                if ( get_query_var( 'taxonomy' ) ) {
                    echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">' . esc_html( get_queried_object()->name ) . '</span></li>';
                } else {
                    echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Courses', 'ivy-school' ) . '">' . esc_html__( 'Courses', 'ivy-school' ) . '</span></li>';
                }

            }
            if ( get_post_type() == "testimonials" ) {
                echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Testimonials', 'ivy-school' ) . '">' . esc_html__( 'Testimonials', 'ivy-school' ) . '</span></li>';
            }
            if ( get_post_type() == "our_team" ) {
                echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Our Team', 'ivy-school' ) . '">' . esc_html__( 'Our Team', 'ivy-school' ) . '</span></li>';
            }
        }
		thim_get_breadcrumb_items_other();
		echo '</ul>';
	}
}

/**
 * Get list sidebars
 */

if ( ! function_exists( 'thim_get_list_sidebar' ) ) {
	function thim_get_list_sidebar() {
		global $wp_registered_sidebars;

		$sidebar_array = array();
		$dp_sidebars   = $wp_registered_sidebars;

		$sidebar_array[''] = esc_attr__( '-- Select Sidebar --', 'ivy-school' );

		foreach ( $dp_sidebars as $sidebar ) {
			$sidebar_array[ $sidebar['name'] ] = $sidebar['name'];
		}

		return $sidebar_array;
	}
}

/**
 * Turn on and get the back to top
 *
 * @return string HTML for the back to top
 */
if ( ! class_exists( 'thim_back_to_top' ) ) {
	function thim_back_to_top() {
		if ( get_theme_mod( 'feature_backtotop', true ) ) {
			?>
			<div id="back-to-top" class="<?php echo get_theme_mod('position_back_to_top','default') ?>">
				<?php
				get_template_part( 'templates/footer/back-to-top' );
				?>
			</div>
			<?php
		}
	}
}
add_action( 'thim_space_body', 'thim_back_to_top', 10 );

/**
 * Switch footer layout
 *
 * @return string HTML footer layout
 */
if ( ! function_exists( 'thim_footer_layout' ) ) {
	function thim_footer_layout() {
		$template_name = 'templates/footer/' . get_theme_mod( 'footer_template', 'default' );
		get_template_part( $template_name );
	}
}

/**
 * Footer Widgets
 *
 * @return bool
 * @return string
 */
if ( ! function_exists( 'thim_footer_widgets' ) ) {
	function thim_footer_widgets() {
		if ( get_theme_mod( 'footer_widgets', true ) ) : ?>
			<div class="footer-sidebars columns-<?php echo get_theme_mod( 'footer_columns', 4 );?> row">
				<?php
				$col = 12 / get_theme_mod( 'footer_columns', 4 );
				if ( get_theme_mod( 'footer_columns' ) == 5 ) {
					$col = '';
				}
				for ( $i = 1; $i <= get_theme_mod( 'footer_columns', 4 ); $i ++ ): ?>
					<div class="col-xs-12 col-sm-6 col-md-<?php echo esc_attr( $col ); ?>">
						<?php dynamic_sidebar( 'footer-sidebar-' . $i ); ?>
					</div>
				<?php endfor; ?>
			</div>
		<?php endif;
	}
}


/**
 * Footer Copyright bar
 *
 * @return bool
 * @return string
 */
if ( ! function_exists( 'thim_copyright_bar' ) ) {
	function thim_copyright_bar() {
		if ( get_theme_mod( 'copyright_bar', true ) ) : ?>
			<div class="copyright-text">
				<?php
				$copyright_text = get_theme_mod( 'copyright_text', 'Designed by <a href="https://thimpress.com">ThimPress</a>. Powered by WordPress.' );
				echo ent2ncr( $copyright_text );
				?>
			</div>
		<?php endif;
	}
}

/**
 * Footer menu
 *
 * @return bool
 * @return array
 */
/*if ( ! function_exists( 'thim_copyright_menu' ) ) {
	function thim_copyright_menu() {
		if ( get_theme_mod( 'copyright_menu', true ) ) :
			if ( has_nav_menu( 'copyright_menu' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'copyright_menu',
					'container'      => false,
					'items_wrap'     => '<ul id="copyright-menu" class="list-inline">%3$s</ul>',
				) );
			}
		endif;
	}
}*/

/**
 * Theme Feature: RTL Support.
 *
 * @return @string
 */
if ( ! function_exists( 'thim_feature_rtl_support' ) ) {
	function thim_feature_rtl_support() {
		if ( get_theme_mod( 'feature_rtl_support', false ) ) {
			echo " dir=\"rtl\"";
		}
	}

	add_filter( 'language_attributes', 'thim_feature_rtl_support', 10 );
}


/**
 * Theme Feature: Open Graph insert doctype
 *
 * @param $output
 */
if ( ! function_exists( 'thim_doctype_opengraph' ) ) {
	function thim_doctype_opengraph( $output ) {
		if ( get_theme_mod( 'feature_open_graph_meta', true ) ) {
			return $output . ' prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#"';
		}
	}

	add_filter( 'language_attributes', 'thim_doctype_opengraph' );
}

/**
 * Theme Feature: Preload
 *
 * @return bool
 * @return string HTML for preload
 */
if ( ! function_exists( 'thim_preloading' ) ) {
	function thim_preloading() {
		$preloading = get_theme_mod( 'theme_feature_preloading', 'off' );
		if ( $preloading != 'off' ) {

			echo '<div id="thim-preloading">';

			switch ( $preloading ) {
				case 'custom-image':
					$preloading_image = get_theme_mod( 'theme_feature_preloading_custom_image', false );
					if ( $preloading_image ) {
						if ( locate_template( 'templates/features/preloading/' . $preloading . '.php' ) ) {
							include locate_template( 'templates/features/preloading/' . $preloading . '.php' );
						}
					}
					break;
				default:
					if ( locate_template( 'templates/features/preloading/' . $preloading . '.php' ) ) {
						include locate_template( 'templates/features/preloading/' . $preloading . '.php' );
					}
					break;
			}

			echo '</div>';

		}
	}

	add_action( 'thim_before_body', 'thim_preloading', 10 );
}

/**
 * Theme Feature: Open Graph meta tag
 *
 * @param string
 */
if ( ! function_exists( 'thim_add_opengraph' ) ) {
	function thim_add_opengraph() {
		global $post;

		if ( get_theme_mod( 'feature_open_graph_meta', true ) ) {
			if ( is_single() ) {
				if ( has_post_thumbnail( $post->ID ) ) {
					$img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
					$img_src = esc_attr( $img_src[0] );
				} else {
					$img_src = THIM_URI . 'assets/images/opengraph.png';
				}
				if ( $excerpt = $post->post_excerpt ) {
					$excerpt = strip_tags( $post->post_excerpt );
					$excerpt = str_replace( "", "'", $excerpt );
				} else {
					$excerpt = get_bloginfo( 'description' );
				}
				?>

				<meta property="og:title" content="<?php echo the_title(); ?>" />
				<meta property="og:description" content="<?php echo esc_attr( $excerpt ); ?>" />
				<meta property="og:type" content="article" />
				<meta property="og:url" content="<?php echo the_permalink(); ?>" />
				<meta property="og:site_name" content="<?php echo get_bloginfo(); ?>" />
				<meta property="og:image" content="<?php echo esc_attr( $img_src ); ?>" />

				<?php
			} else {
				return;
			}
		}
	}

	add_action( 'wp_head', 'thim_add_opengraph', 10 );
}


/**
 * Theme Feature: Google theme color
 */
if ( ! function_exists( 'thim_google_theme_color' ) ) {
	function thim_google_theme_color() {
		if ( get_theme_mod( 'feature_google_theme', false ) ) { ?>
			<meta name="theme-color" content="<?php echo esc_attr( get_theme_mod( 'feature_google_theme_color', '#333333' ) ) ?>">
			<?php
		}
	}

	add_action( 'wp_head', 'thim_google_theme_color', 10 );
}

/**
 * Responsive: enable or disable responsive
 *
 * @return string
 * @return bool
 */
if ( ! function_exists( 'thim_enable_responsive' ) ) {
	function thim_enable_responsive() {
		if ( get_theme_mod( 'enable_responsive', true ) ) {
			echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
		}
	}

	add_action( 'wp_head', 'thim_enable_responsive', 1 );
}


/**
 *
 * Display Topbar
 *
 * @return void
 *
 */
if ( ! function_exists( 'thim_topbar' ) ) {
	function thim_topbar() {
		$display = get_theme_mod( 'header_topbar_display', false );
		$style   = get_theme_mod( 'header_position', 'default' );
		$size_topbar = get_theme_mod('size_topbar','default');
		if ( $display ) {
			echo '<div id="thimHeaderTopBar" class="thim-topbar '. $size_topbar . ' style-' . $style .' ">';
			get_template_part( 'templates/header/topbar' );
			echo '</div>';
		}
	}

	add_action( 'thim_topbar', 'thim_topbar', 10 );
}


/**
 * Override ajax-loader contact form
 *
 * $return mixed
 */

function thim_wpcf7_ajax_loader() {
	return THIM_URI . 'assets/images/icons/ajax-loader.gif';
}

add_filter( 'wpcf7_ajax_loader', 'thim_wpcf7_ajax_loader' );


/**
 * aq_resize function fake.
 * Aq_Resize
 */
if ( ! function_exists( 'thim_aq_resize' ) ) {
	function thim_aq_resize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {
        /* WPML Fix */
        if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
            global $sitepress;
            $url = $sitepress->convert_url( $url, $sitepress->get_default_language() );
        }
        /* WPML Fix */
        $aq_resize = Aq_Resize::getInstance();

        return $aq_resize->process( $url, $width, $height, $crop, $single, $upscale );
	}
}


/**
 * Get feature image
 *
 * @param int  $width
 * @param int  $height
 * @param bool $link
 *
 * @return string
 */
function thim_feature_image( $attachment_id = 0, $width = 1024, $height = 768, $link = true ) {
	$thumbnail_full = wp_get_attachment_image_src( $attachment_id, 'full' );
	if ( isset( $thumbnail_full[0] ) ) {
		$image_crop = thim_aq_resize( $thumbnail_full[0], $width, $height, true );

		if ( $image_crop ) {
			if ( $link ) {
				echo '<div class="thumbnail"><a href="' . esc_url( get_permalink() ) . '" title = "' . get_the_title() . '">';
				echo '<img src="' . $image_crop . '" alt= "' . get_the_title() . '" title = "' . get_the_title() . '" />';
				echo '</a></div>';
			} else {
				echo '<div class="thumbnail">';
				echo '<img src="' . $image_crop . '" alt= "' . get_the_title() . '" title = "' . get_the_title() . '" />';
				echo '</div>';
			}
		}else {
            if ( $link ) {
                echo '<div class="thumbnail"><a href="' . esc_url( get_permalink() ) . '" title = "' . get_the_title() . '">';
                echo '<img src="' . $thumbnail_full[0] . '" alt= "' . get_the_title() . '" title = "' . get_the_title() . '" />';
                echo '</a></div>';
            } else {
                echo '<div class="thumbnail">';
                echo '<img src="' . $thumbnail_full[0] . '" alt= "' . get_the_title() . '" title = "' . get_the_title() . '" />';
                echo '</div>';
            }
        }
	}

}


/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @see   wcpt_locate_template()
 *
 * @param string $template_name Template to load.
 * @param array  $args          Args passed for the template file.
 * @param string $string        $template_path    Path to templates.
 * @param string $default_path  Default path to template files.
 */
function thim_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;

	$template_name = $template_name . '.php';
	$posts         = isset( $args['posts'] ) ? $args['posts'] : array();
	$params        = isset( $args['params'] ) ? $args['params'] : array();

	$template_file = thim_locate_template( $template_name, $tempate_path, $default_path );

	if ( ! file_exists( $template_file ) ) :
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );

		return;
	endif;

	include $template_file;
}

if ( ! function_exists( 'thim_entry_top_archive' ) ) {
	function thim_entry_top_archive() {
		$size = 0;
		$html = '';
		switch ( get_post_format() ) {
			case 'image':
				$image = thim_get_image( array(
					'size'     => $size,
					'format'   => 'src',
					'meta_key' => 'thim_image',
					'echo'     => false,
				) );
				if ( ! $image ) {
					break;
				}

				$html = sprintf(
					'<a class="post-image" href="%1$s" title="%2$s"><img src="%3$s" alt="%2$s"></a>',
					esc_url( get_permalink() ),
					esc_attr( the_title_attribute( 'echo=0' ) ),
					$image
				);
				break;
			case 'gallery':
				$images = thim_meta( 'thim_gallery', "type=image&single=false&size=$size" );
				$thumbs = thim_meta( 'thim_gallery', "type=image&single=false&size=thumbnail" );
				if ( empty( $images ) ) {
					break;
				}
				wp_enqueue_script( 'thim-flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array( 'jquery' ), '', false );
				$html .= '<div class="flexslider">';
				$html .= '<ul class="slides">';
				foreach ( $images as $key => $image ) {

					if ( ! empty( $image['url'] ) ) {
						$html .= sprintf(
							'<li data-thumb="%s"><a href="%s" class="hover-gradient"><img src="%s" alt="' . esc_attr__( 'Gallery', 'ivy-school' ) . '"></a></li>',
							$thumbs[ $key ]['url'],
							esc_url( get_permalink() ),
							esc_url( $image['url'] )
						);
					}
				}
				$html .= '</ul>';
				$html .= '</div>';
				break;
			case 'audio':
				$audio = thim_meta( 'thim_audio' );
				if ( ! $audio ) {
					break;
				}
				// If URL: show oEmbed HTML or jPlayer
				if ( filter_var( $audio, FILTER_VALIDATE_URL ) ) {
					wp_enqueue_style( 'thim-pixel-industry' );
					wp_enqueue_script( 'thim-jplayer' );
					// Try oEmbed first
					if ( $oembed = @wp_oembed_get( $audio ) ) {
						$html .= $oembed;
					} // Use jPlayer
					else {
						$id   = uniqid();
						$html .= "<div data-player='$id' class='jp-jplayer' data-audio='$audio'></div>";
						$html .= thim_jplayer( $id );
					}
				} // If embed code: just display
				else {
					$html .= $audio;
				}
				break;
			case 'video':

				$video = thim_meta( 'thim_video' );
				if ( ! $video ) {
					break;
				}
				// If URL: show oEmbed HTML
				if ( filter_var( $video, FILTER_VALIDATE_URL ) ) {
					if ( $oembed = @wp_oembed_get( $video ) ) {
						$html .= $oembed;
					}
				} // If embed code: just display
				else {
					$html .= $video;
				}
				break;
			default:
				$thumb = get_the_post_thumbnail( get_the_ID(), $size );
				if ( empty( $thumb ) ) {
					return;
				}
				$html .= '<a class="post-image" href="' . esc_url( get_permalink() ) . '">';
				$html .= $thumb;
				$html .= '</a>';
		}
		if ( $html ) {
			echo "<div class='post-formats-wrapper'>$html</div>";
		}
	}
}
/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 *
 * @since 1.0.0
 *
 * @param    string $template_name Template to load.
 * @param    string $string        $template_path    Path to templates.
 * @param    string $default_path  Default path to template files.
 *
 * @return    string                            Path to the template file.
 */
function thim_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) :
		$template_path = 'templates/';
	endif;

	// Set default plugin templates path.
	if ( ! $default_path ) :
		$default_path = THIM_MAGWP_PATH . $template_path; // Path to the template folder
	endif;

	// Search template file in theme folder.
	$template = locate_template( array(
		$template_path . $template_name,
		$template_name
	) );

	// Get plugins template file.
	if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;

	return apply_filters( 'thim_locate_template', $template, $template_name, $template_path, $default_path );
}

//Edit Widget Categories
add_filter( 'wp_list_categories', 'thim_add_span_cat_count' );
function thim_add_span_cat_count( $links ) {
	$links = str_replace( '<span class="count">(', '<span class="count">', $links );
	$links = str_replace( ')</span>', '</span>', $links );


	$links = str_replace( '(', '<span class="count">', $links );
	$links = str_replace( ')', '</span>', $links );

	return $links;
}

/**
 * @param      $id
 * @param      $size
 */
if ( ! function_exists( 'thim_thumbnail' ) ) {
	function thim_thumbnail( $id, $size, $type = 'post', $link = true, $classes = '' ) {
		echo thim_get_thumbnail( $id, $size, $type, $link, $classes );
	}
}

/**
 * @param $id
 * @param $size
 * @param $type : default is post
 *
 * @return string
 */
if ( ! function_exists( 'thim_get_thumbnail' ) ) {
	function thim_get_thumbnail( $id, $size = 'thumbnail', $type = 'post', $link = true, $classes = '' ) {
		$width         = 0;
		$height        = 0;
		$attachment_id = $id;

		if ( $type === 'post' ) {
			$attachment_id = get_post_thumbnail_id( $id );
		}
		$src = wp_get_attachment_image_src( $attachment_id, 'full' );

		if ( $size != 'full' && ! in_array( $size, get_intermediate_image_sizes() ) ) {
			//custom size
			$thumbnail_size = explode( 'x', $size );
			$width          = $thumbnail_size[0];
			$height         = $thumbnail_size[1];
			$img_src        = thim_aq_resize( $src[0], $width, $height, true );
		} else if ( $size == 'full' ) {
			$img_src = $src[0];
			$width   = $src[1];
			$height  = $src[2];
		} else {
			$image_size = wp_get_attachment_image_src( $attachment_id, $size );
			$width      = $image_size[1];
			$height     = $image_size[2];
		}

		if ( empty( $img_src ) ) {
			$img_src = $src[0];
		}

		$html = '';
		if ( $link ) {
			$html .= '<a href="' . esc_url( get_permalink( $id ) ) . '" class="img-link" target="_self">';
		}
		$html .= '<img ' . image_hwstring( $width, $height ) . ' src="' . esc_attr( $img_src ) . '" alt="' . get_the_title( $id ) . '" class="' . $classes . '">';
		if ( $link ) {
			$html .= '</a>';
		}

		return $html;
	}
}

/**
 * Add background overlay to VC Row
 */
if ( class_exists( 'Vc_Manager' ) ) {
	$thim_row_bg_overlay_attributes = array(
		'type'       => 'colorpicker',
		'heading'    => "Background Overlay",
		'param_name' => 'overlay_color',
		'value'      => '',
		'group'      => 'Advance Options',
	);
	vc_add_param( 'vc_row', $thim_row_bg_overlay_attributes );
}

/**
 * Support excerpt feature for page
 */
add_action( 'wp_enqueue_scripts', 'thim_frontend_dashicons',20 );
function thim_frontend_dashicons() {
    wp_enqueue_style( 'dashicons' );
}

/* custom length excerpt*/
function custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/**/
add_post_type_support( 'page', 'excerpt' );

/**
 * Get User meta
 */
if ( ! function_exists( 'thim_get_user_meta' ) ) {
    function thim_get_user_meta( $a ) {
        return $a[0];
    }
}


/**
 * Get Event Upcoming
 */
//Filter post_status tp_event
if ( ! function_exists( 'thim_get_upcoming_events' ) ) {
    function thim_get_upcoming_events($limit = 1, $args = array())
    {
        if ( is_tax( 'tp_event_category' ) ) {
            $args = wp_parse_args(
                $args,
                array(
                    'post_type'  => 'tp_event',
                    'posts_per_page' => $limit,
                    'meta_query' => array(
                        array(
                            'key'     => 'tp_event_status',
                            'value'   => 'upcoming',
                            'compare' => '=',
                        ),
                    ),
                    'tax_query'  => array(
                        array(
                            'taxonomy' => 'tp_event_category',
                            'field'    => 'slug',
                            'terms'    => get_query_var( 'term' ),
                        )
                    ),
                )
            );
        } else {
            $args = wp_parse_args(
                $args,
                array(
                    'post_type'  => 'tp_event',
                    'posts_per_page' => $limit,
                    'meta_query' => array(
                        array(
                            'key'     => 'tp_event_status',
                            'value'   => 'upcoming',
                            'compare' => '=',
                        ),
                    ),
                )
            );
        }
        return new WP_Query( $args );
    }
}

/**
 * Filter Event
 */
remove_action( 'tp_event_after_single_event', 'wpems_single_event_register' );
add_action( 'thim_event_loop_event_contact', 'wpems_single_event_register' );
// Filter height google map
add_filter( 'tp_event_filter_event_location_map', 'thim_tp_event_filter_event_location_map', 1, 5 );
if( !function_exists( 'thim_tp_event_filter_event_location_map' ) ) {
    function thim_tp_event_filter_event_location_map($arg) {
        $arg['height'] = '450px';
        return $arg;
    }
}

add_action( 'tp_event_after_single_event', 'func_event_loop_event_author' );
if ( ! function_exists( 'func_event_loop_event_author' ) ) {
    function func_event_loop_event_author() {
        wpems_get_template( 'loop/author.php' );
    }

}

/**
 * Get url account page
 */
if( !function_exists('thim_get_login_page_url') ) {
    function thim_get_login_page_url( $redirect_url = '' ) {
        $page = get_page_by_path( 'account' );
        if( $page ) {
            return ! empty( $redirect_url ) ? add_query_arg( 'redirect_to', urlencode( $redirect_url ), get_permalink( $page[0] ) ) : get_permalink( $page->ID );
        }
        return wp_login_url();
    }
}

/**
 * Footer Bottom
 */
if ( ! function_exists( 'thim_footer_top' ) ) {
    function thim_footer_top() {
        $thim_hidden_footer_top = thim_meta('thim_footer_top');
        if ( is_active_sidebar( 'footer-top' ) && $thim_hidden_footer_top == 0 ) {
            ?>
            <div class="footer-bottom">

                <div class="container">
                    <?php dynamic_sidebar( 'footer-top' ); ?>
                </div>

            </div>
        <?php }
    }
}
add_action( 'thim_above_footer_area', 'thim_footer_top' );

/**
 * Remove hook tp-event-auth
 */
if ( class_exists( 'WPEMS_User_Process' ) ) {


    //remove_action( 'login_form_login', array( $auth, 'redirect_to_login_page' ) );
    //remove_action( 'login_form_register', array( $auth, 'login_form_register' ) );
    //remove_action( 'login_form_lostpassword', array( $auth, 'redirect_to_lostpassword' ) );
    //remove_action( 'login_form_rp', array( $auth, 'resetpass' ) );
    //remove_action( 'login_form_resetpass', array( $auth, 'resetpass' ) );

    remove_filter( 'logout_redirect', array( 'WPEMS_User_Process', 'logout_redirect' ) );
    //remove_filter( 'login_url', array( $auth, 'login_url' ) );
    //remove_filter( 'login_redirect', array( $auth, 'login_redirect' ) );

}

/**
 * Add filter login redirect
 */
add_filter( 'login_redirect', 'thim_login_redirect', 1000 );
if ( ! function_exists( 'thim_login_redirect' ) ) {
	function thim_login_redirect() {
		if ( empty( $_REQUEST['redirect_to'] ) ) {
			$redirect_url = get_theme_mod( 'theme_feature_login_redirect' );
			if ( ! empty( $redirect_url ) ) {
				return $redirect_url;
			} else {
				return home_url( '/' );
			}
		} else {
			return $_REQUEST['redirect_to'];
		}
	}
}

/**
 * Add filter login redirect
 */
add_filter( 'logout_redirect', 'thim_logout_redirect', 1000 );
if ( ! function_exists( 'thim_logout_redirect' ) ) {
    function thim_logout_redirect() {
        if ( empty( $_REQUEST['redirect_to'] ) ) {
            $redirect_url = get_theme_mod( 'theme_feature_logout_redirect' );
            if ( ! empty( $redirect_url ) ) {
                return $redirect_url;
            } else {
                return home_url( '/' );
            }
        } else {
            return $_REQUEST['redirect_to'];
        }
    }
}

// Fix A Cookies Blocked Error
setcookie( TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN );
if ( SITECOOKIEPATH != COOKIEPATH ) {
    setcookie( TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN );
}

/**
 * Change link reset password in the email
 */
if ( ! function_exists( 'thim_replace_retrieve_password_message' ) ) {
    function thim_replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {

        $reset_link = add_query_arg(
            array(
                'action' => 'rp',
                'key'    => $key,
                'login'  => rawurlencode( $user_login )
            ), thim_get_login_page_url()
        );

        // Create new message
        $message = esc_html__( 'Someone has requested a password reset for the following account:', 'ivy-school' ) . "\r\n\r\n";
        $message .= network_home_url( '/' ) . "\r\n\r\n";
        $message .= sprintf( esc_html__( 'Username: %s', 'ivy-school' ), $user_login ) . "\r\n\r\n";
        $message .= esc_html__( 'If this was a mistake, just ignore this email and nothing will happen.', 'ivy-school' ) . "\r\n\r\n";
        $message .= esc_html__( 'To reset your password, visit the following address:', 'ivy-school' ) . "\r\n\r\n";
        $message .= $reset_link . "\r\n";

        return $message;
    }
}

/**
 * Determining engine environment
 */
if ( ! function_exists( 'is_wpe' ) && ! function_exists( 'is_wpe_snapshot' ) ) {
    add_filter( 'retrieve_password_message', 'thim_replace_retrieve_password_message', 10, 4 );
}

/**
 * Filters Paid Membership pro login redirect & register redirect
 */
add_filter( 'pmpro_register_redirect', '__return_false' );

/**
 * Redirect to custom register page in case multi sites
 *
 * @param $url
 *
 * @return mixed
 */
if ( ! function_exists( 'thim_multisite_register_redirect' ) ) {
    function thim_multisite_register_redirect( $url ) {

        if ( is_multisite() ) {
            $url = add_query_arg( 'action', 'register', thim_get_login_page_url() );
        }

        $user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : '';
        $user_email = isset( $_POST['user_email'] ) ? $_POST['user_email'] : '';
        $errors     = register_new_user( $user_login, $user_email );
        if ( ! is_wp_error( $errors ) ) {
            $redirect_to = ! empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : 'wp-login.php?checkemail=registered';
            wp_safe_redirect( $redirect_to );
            exit();
        }

        return $url;
    }
}
add_filter( 'wp_signup_location', 'thim_multisite_register_redirect' );

/**
 * Check is course
 */
if ( ! function_exists( 'thim_check_is_course' ) ) {
    function thim_check_is_course() {
        if ( function_exists( 'learn_press_is_courses' ) && learn_press_is_courses() ) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Check is course taxonomy
 */
if ( ! function_exists( 'thim_check_is_course_taxonomy' ) ) {
    function thim_check_is_course_taxonomy() {
        if ( function_exists( 'learn_press_is_course_taxonomy' ) && learn_press_is_course_taxonomy() ) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Get post meta
 *
 * @param $key
 * @param $args
 * @param $post_id
 *
 * @return string
 * @return bool
 */
if ( ! function_exists( 'thim_meta' ) ) {
    function thim_meta( $key, $args = array(), $post_id = null ) {
        $post_id = empty( $post_id ) ? get_the_ID() : $post_id;

        $args = wp_parse_args( $args, array(
            'type' => 'text',
        ) );

        // Image
        if ( in_array( $args['type'], array( 'image' ) ) ) {
            if ( isset( $args['single'] ) && $args['single'] == "false" ) {
                // Gallery
                $temp          = array();
                $data          = array();
                $attachment_id = get_post_meta( $post_id, $key, false );
                if ( ! $attachment_id ) {
                    return $data;
                }

                if ( empty( $attachment_id ) ) {
                    return $data;
                }
                foreach ( $attachment_id as $k => $v ) {
                    $image_attributes = wp_get_attachment_image_src( $v, $args['size'] );
                    $temp['url']      = $image_attributes[0];
                    $data[]           = $temp;
                }

                return $data;
            } else {
                // Single Image
                $attachment_id    = get_post_meta( $post_id, $key, true );
                $image_attributes = wp_get_attachment_image_src( $attachment_id, $args['size'] );

                return $image_attributes;
            }
        }

        return get_post_meta( $post_id, $key, $args );
    }
}

/**
 * Show entry format images, video, gallery, audio, etc.
 *
 * @return void
 */
if ( ! function_exists( 'thim_post_formats' ) ):
    function thim_post_formats( $size ) {
        $html = '';
        switch ( get_post_format() ) {
            case 'image':
                $image = thim_get_image( array(
                    'size'     => $size,
                    'format'   => 'src',
                    'meta_key' => 'thim_image',
                    'echo'     => false,
                ) );
                if ( ! $image ) {
                    break;
                }

                $html = sprintf( '<a class="post-image" href="%1$s" title="%2$s"><img src="%3$s" alt="%2$s"></a>', esc_url( get_permalink() ), esc_attr( the_title_attribute( 'echo=0' ) ), $image );
                break;
            case 'gallery':
                $images = thim_meta( 'thim_gallery', "type=image&single=false&size=$size" );
                $thumbs = thim_meta( 'thim_gallery', "type=image&single=false&size=thumbnail" );
                if ( empty( $images ) ) {
                    break;
                }
                $html .= '<div class="flexslider">';
                $html .= '<ul class="slides">';
                foreach ( $images as $key => $image ) {
                    if ( ! empty( $image['url'] ) ) {
                        $html .= sprintf( '<li data-thumb="%s"><a href="%s" class="hover-gradient"><img src="%s" alt="gallery"></a></li>', $thumbs[ $key ]['url'], esc_url( get_permalink() ), esc_url( $image['url'] ) );
                    }
                }
                $html .= '</ul>';
                $html .= '</div>';
                break;
            case 'audio':
                $audio = thim_meta( 'thim_audio' );
                if ( ! $audio ) {
                    break;
                }
                // If URL: show oEmbed HTML or jPlayer
                if ( filter_var( $audio, FILTER_VALIDATE_URL ) ) {
                    //jsplayer
                    wp_enqueue_style( 'thim-pixel-industry', THIM_CORE_ADMIN_URI . '/assets/js/jplayer/skin/pixel-industry/pixel-industry.css' );
                    wp_enqueue_script( 'thim-jplayer', THIM_CORE_ADMIN_URI . '/assets/js/jplayer/jquery.jplayer.min.js', array( 'jquery' ), '', true );

                    // Try oEmbed first
                    if ( $oembed = @wp_oembed_get( $audio ) ) {
                        $html .= $oembed;
                    } // Use jPlayer
                    else {
                        $id   = uniqid();
                        $html .= "<div data-player='$id' class='jp-jplayer' data-audio='$audio'></div>";
                        $html .= thim_jplayer( $id );
                    }
                } // If embed code: just display
                else {
                    $html .= $audio;
                }
                break;
            case 'video':

                $video = thim_meta( 'thim_video' );
                if ( ! $video ) {
                    break;
                }
                // If URL: show oEmbed HTML
                if ( filter_var( $video, FILTER_VALIDATE_URL ) ) {
                    if ( $oembed = @wp_oembed_get( $video ) ) {
                        $html .= $oembed;
                    }
                } // If embed code: just display
                else {
                    $html .= $video;
                }
                break;
            default:
                $thumb = get_the_post_thumbnail( get_the_ID(), $size );
                if ( empty( $thumb ) ) {
                    return;
                }
                $html .= '<a class="post-image" href="' . esc_url( get_permalink() ) . '">';
                $html .= $thumb;
                $html .= '</a>';
        }
        if ( $html ) {
            echo "<div class='post-formats-wrapper'>$html</div>";
        }
    }
endif;
add_action( 'thim_entry_top', 'thim_post_formats' );

/**
 * Get image features
 *
 * @param $args
 *
 * @return array|void
 */
if ( ! function_exists( 'thim_get_image' ) ) {
    function thim_get_image( $args = array() ) {
        $default = apply_filters( 'thim_get_image_default_args', array(
            'post_id'  => get_the_ID(),
            'size'     => 'thumbnail',
            'format'   => 'html', // html or src
            'attr'     => '',
            'meta_key' => '',
            'scan'     => true,
            'default'  => '',
            'echo'     => true,
        ) );

        $args = wp_parse_args( $args, $default );

        if ( ! $args['post_id'] ) {
            $args['post_id'] = get_the_ID();
        }

        // Get image from cache
        $key         = md5( serialize( $args ) );
        $image_cache = wp_cache_get( $args['post_id'], 'thim_get_image' );

        if ( ! is_array( $image_cache ) ) {
            $image_cache = array();
        }

        if ( empty( $image_cache[ $key ] ) ) {
            // Get post thumbnail
            if ( has_post_thumbnail( $args['post_id'] ) ) {
                $id   = get_post_thumbnail_id();
                $html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
                list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
            }

            // Get the first image in the custom field
            if ( ! isset( $html, $src ) && $args['meta_key'] ) {
                $id = get_post_meta( $args['post_id'], $args['meta_key'], true );

                // Check if this post has attached images
                if ( $id ) {
                    $html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
                    list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
                }
            }

            // Get the first attached image
            if ( ! isset( $html, $src ) ) {
                $image_ids = array_keys( get_children( array(
                    'post_parent'    => $args['post_id'],
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ) ) );

                // Check if this post has attached images
                if ( ! empty( $image_ids ) ) {
                    $id   = $image_ids[0];
                    $html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
                    list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
                }
            }

            // Get the first image in the post content
            if ( ! isset( $html, $src ) && ( $args['scan'] ) ) {
                preg_match( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', get_post_field( 'post_content', $args['post_id'] ), $matches );

                if ( ! empty( $matches ) ) {
                    $html = $matches[0];
                    $src  = $matches[1];
                }
            }

            // Use default when nothing found
            if ( ! isset( $html, $src ) && ! empty( $args['default'] ) ) {
                if ( is_array( $args['default'] ) ) {
                    $html = $args['html'];
                    $src  = $args['src'];
                } else {
                    $html = $src = $args['default'];
                }
            }

            // Still no images found?
            if ( ! isset( $html, $src ) ) {
                return false;
            }

            $output = 'html' === strtolower( $args['format'] ) ? $html : $src;

            $image_cache[ $key ] = $output;
            wp_cache_set( $args['post_id'], $image_cache, 'thim_get_image' );
        } // If image already cached
        else {
            $output = $image_cache[ $key ];
        }

        $output = apply_filters( 'thim_get_image', $output, $args );

        if ( ! $args['echo'] ) {
            return $output;
        }

        echo ent2ncr( $output );
    }
}

/**
 * Action add menu account
 */
function thim_login_menu_account(){
    ?>
    <ul class="user-info">
        <?php
        if ( thim_plugin_active( 'learnpress' ) ) :
            $profile = LP_Profile::instance();
            ?>
            <li>
                <a class="profile" href="<?php echo esc_url( learn_press_user_profile_link() ); ?>"><?php esc_html_e( 'Profile', 'ivy-school' ); ?></a>
            </li>
            <li>
                <a class="courses" href="<?php echo esc_url( $profile->get_tab_link( 'courses', true ) ); ?>"><?php esc_html_e( 'Courses', 'ivy-school' ); ?></a>
            </li>
            <li>
                <a class="orders" href="<?php echo esc_url( $profile->get_tab_link( 'orders', true ) ); ?>"><?php esc_html_e( 'Orders', 'ivy-school' ); ?></a>
            </li>
            <li>
                <a class="become_a_teacher" href="<?php echo learn_press_get_page_link( 'become_a_teacher' ); ?>"><?php esc_html_e( 'Become An Instructor', 'ivy-school' ); ?></a>
            </li>
            <li>
                <a class="settings" href="<?php echo esc_url( $profile->get_tab_link( 'settings', true ) ); ?>"><?php esc_html_e( 'Edit Profile', 'ivy-school' ); ?></a>
            </li>
        <?php endif; ?>
        <li>
            <a class="logout" title="<?php echo esc_attr__( 'Logout', 'ivy-school' ) ?>"
               href="<?php echo esc_url( wp_logout_url() ); ?>">
        <span class="text-logout">
            <?php echo esc_html__('Logout', 'ivy-school');?>
        </span>

            </a>
        </li>
    </ul>

    <?php
}
add_action('thim_menu_account','thim_login_menu_account');

/**
 * Add google analytics & facebook pixel code
 */
if ( ! function_exists( 'thim_add_marketing_code' ) ) {
    function thim_add_marketing_code() {

        $theme_options_data = get_theme_mods();
        if ( ! empty( $theme_options_data['theme_feature_analytics'] ) ) {
            ?>
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_html( $theme_options_data['theme_feature_analytics'] ); ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '<?php echo esc_html( $theme_options_data['theme_feature_analytics'] ); ?>');
            </script>
            <?php
        }
        if ( ! empty( $theme_options_data['theme_feature_facebook_pixel'] ) ) {
            ?>
            <script>
                !function (f, b, e, v, n, t, s) {
                    if (f.fbq) return;
                    n = f.fbq = function () {
                        n.callMethod ?
                            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                    };
                    if (!f._fbq) f._fbq = n;
                    n.push = n;
                    n.loaded = !0;
                    n.version = '2.0';
                    n.queue = [];
                    t = b.createElement(e);
                    t.async = !0;
                    t.src = v;
                    s = b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t, s)
                }(window, document, 'script',
                    'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '<?php echo esc_html( $theme_options_data['theme_feature_facebook_pixel'] ); ?>');
                fbq('track', 'PageView');
            </script>
            <noscript>
                <img height="1" width="1" style="display:none"
                     src="https://www.facebook.com/tr?id=<?php echo esc_attr( $theme_options_data['theme_feature_facebook_pixel'] ); ?>&ev=PageView&noscript=1"/>
            </noscript>
            <?php
        }
        ?>
        <?php
    }
}
add_action( 'wp_head', 'thim_add_marketing_code' );

/**
 * Define ajaxurl if not exist
 */
if ( ! function_exists( 'thim_define_ajaxurl' ) ) {
    function thim_define_ajaxurl() {
        ?>
        <script type="text/javascript">
            if (typeof ajaxurl === 'undefined') {
                /* <![CDATA[ */
                var ajaxurl = "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>";
                /* ]]> */
            }
        </script>
        <?php
    }
}
add_action( 'wp_head', 'thim_define_ajaxurl', 1000 );

/**
 * Check import demo data page-builder
 */
add_action( 'wp_ajax_thim_update_theme_mods', 'thim_import_demo_page_builder' );
if ( ! function_exists( 'thim_import_demo_page_builder' ) ) {
    function thim_import_demo_page_builder() {
        $thim_key   = sanitize_text_field( $_POST["thim_key"] );
        $thim_value = sanitize_text_field( $_POST["thim_value"] );
        if ( ! is_multisite() ) {
            $active_plugins = get_option( 'active_plugins' );

            if ( $thim_value == 'visual_composer' ) {
                if ( ( $key = array_search( 'siteorigin-panels/siteorigin-panels.php', $active_plugins ) ) !== false ) {
                    unset( $active_plugins[ $key ] );
                }
                if ( ( $key = array_search( 'elementor/elementor.php', $active_plugins ) ) !== false ) {
                    unset( $active_plugins[ $key ] );
                }
                if ( ( $key = array_search( 'anywhere-elementor/anywhere-elementor.php', $active_plugins ) ) !== false ) {
                    unset( $active_plugins[ $key ] );
                }
                if ( ! in_array( 'js_composer/js_composer.php', $active_plugins ) ) {
                    $active_plugins[] = 'js_composer/js_composer.php';
                }

            } else if ( $thim_value == 'site_origin' ) {
                if ( ( $key = array_search( 'js_composer/js_composer.php', $active_plugins ) ) !== false ) {
                    unset( $active_plugins[ $key ] );
                }
                if ( ( $key = array_search( 'elementor/elementor.php', $active_plugins ) ) !== false ) {
                    unset( $active_plugins[ $key ] );
                }
                if ( ( $key = array_search( 'anywhere-elementor/anywhere-elementor.php', $active_plugins ) ) !== false ) {
                    unset( $active_plugins[ $key ] );
                }
                if ( ! in_array( 'siteorigin-panels/siteorigin-panels.php', $active_plugins ) ) {
                    $active_plugins[] = 'siteorigin-panels/siteorigin-panels.php';
                }

            } else if ( $thim_value == 'elementor' ) {
                if ( ( $key = array_search( 'js_composer/js_composer.php', $active_plugins ) ) !== false ) {
                    unset( $active_plugins[ $key ] );
                }
                if ( ( $key = array_search( 'siteorigin-panels/siteorigin-panels.php', $active_plugins ) ) !== false ) {
                    unset( $active_plugins[ $key ] );
                }
                if ( ! in_array( 'elementor/elementor.php', $active_plugins ) ) {
                    $active_plugins[] = 'elementor/elementor.php';
                }
                if ( ! in_array( 'anywhere-elementor/anywhere-elementor.php', $active_plugins ) ) {
                    $active_plugins[] = 'anywhere-elementor/anywhere-elementor.php';
                }

            }
            update_option( 'active_plugins', $active_plugins );
        }

        if ( empty( $thim_key ) || empty( $thim_value ) ) {
            $output = 'update fail';
        } else {
            set_theme_mod( $thim_key, $thim_value );
            $output = 'update success';
        }

        echo ent2ncr( $output );
        die();
    }
}

/**
 * Filter redirect plugin tp chameleon
 */
if ( ! function_exists( 'thim_tp_chameleon_redirect' ) ) {
    function thim_tp_chameleon_redirect( $option ) {
        if ( ( ! is_admin() && ! is_home() && ! is_front_page() ) || is_customize_preview() ) {
            return false;
        } else {
            return $option;
        }
    }
}
add_filter( 'tp_chameleon_redirect_iframe', 'thim_tp_chameleon_redirect' );

/**
 * Filter image all-demo tp-chameleon
 */
if ( ! function_exists( 'thim_override_demo_image_tp_chameleon' ) ) {
    function thim_override_demo_image_tp_chameleon() {
        return THIM_URI . 'assets/images/Ivy_chameleon_new_artschool.png';
    }
}
add_filter( 'tp_chameleon_get_image_sprite_demos', 'thim_override_demo_image_tp_chameleon' );

/**
 * Filter demos path
 */
function thim_filter_site_demos( $demo_datas ) {
    $demo_data_file_path = get_template_directory() . '/inc/data/demos.php';
    if ( is_file( $demo_data_file_path ) ) {
        require $demo_data_file_path;
    }

    return $demo_datas;
}

add_filter( 'tp_chameleon_get_site_demos', 'thim_filter_site_demos' );

/**
 * List child themes.
 *
 * @return array
 */
function thim_ivy_list_child_themes() {
    return array(
        'ivy-school-child'              => array(
            'name'       => 'Ivy School Child',
            'slug'       => 'ivy-school-child',
            'screenshot' => 'https://thimpresswp.github.io/demo-data/ivy/child-themes/ivy-school-child.jpg',
            'source'     => 'https://thimpresswp.github.io/demo-data/ivy/child-themes/ivy-school-child.zip',
            'version'    => '1.0.0'
        ),
    );
}
add_filter( 'thim_core_list_child_themes', 'thim_ivy_list_child_themes' );

//Add info for Dashboard Admin
if ( ! function_exists( 'thim_links_guide_user' ) ) {
    function thim_links_guide_user() {
        return array(
            'docs'      => 'http://docspress.thimpress.com/ivy-school/',
            'support'   => 'https://thimpress.com/forums/forum/ivy-school/',
            'knowledge' => 'https://thimpress.com/knowledge-base/',
        );
    }
}
add_filter( 'thim_theme_links_guide_user', 'thim_links_guide_user' );

/**
 * Link purchase theme.
 */
if ( ! function_exists( 'thim_link_purchase' ) ) {
    function thim_link_purchase() {
        return 'https://themeforest.net/item/ivy-school-education-university-wordpress-theme/22773871?ref=thimpress&utm_source=thimcoredashboard&utm_medium=dashboard';
    }
}
add_filter( 'thim_envato_link_purchase', 'thim_link_purchase' );

/**
 * Envato id.
 */
if ( ! function_exists( 'thim_envato_item_id' ) ) {
    function thim_envato_item_id() {
        return '22773871';
    }
}
add_filter( 'thim_envato_item_id', 'thim_envato_item_id' );
