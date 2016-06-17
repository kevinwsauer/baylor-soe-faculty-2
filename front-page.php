<?php
/**
 * The custom template for the front-page. Kicks in automatically.
 *
 */

get_header(); ?>

<div id="primary" class="content-area front-page">
    <main id="main" class="site-main" role="main">
        
        <div id="call-to-action">
            <div class="indent clear">
                    <?php 
                    $query = new WP_Query( 'pagename=welcome' );
                    // The Loop
                    if ( $query->have_posts() ) {
                        echo '<h2 class="section-title">Meet Our Faculty</h2>';
                        while ( $query->have_posts() ) {
                            $query->the_post();
                            echo '<div class="entry-content">';
                            the_content();
                            echo '</div>';
                        }
                    }
                    
                    /* Restore original Post Data */
                    wp_reset_postdata();
                    ?>
            </div><!-- .indent -->
        </div><!-- #call-to-action -->
        
        
        <div id="tabs">
            <div class="indent clear faculty-nav">
                <ul>
                    <li><a href="#all-faculty">Faculty A-Z</a></li>
                    <li><a href="#ci-faculty">Curriculum & Instruction</a></li>
                    <li><a href="#ea-faculty">Educational Administration</a></li>
                    <li><a href="#ep-faculty">Educational Psychology</a></li>
                </ul>  
            </div>   
            <?php 
            
            $args = array(
                'post_type' => 'faculty-profiles',
                'post_status' => 'publish',
                'posts_per_page' => 100, 
                'meta_key'		=> 'last_name',
                'orderby'		=> 'meta_value',
                'order' => 'ASC'
            );
                                        
            $query = new WP_Query( $args );
                
            // The Loop
            if ( $query->have_posts()) {
                // All Faculty
                echo '<div id="all-faculty">';
                    echo '<div class="indent clear">';
                        echo '<ul class="all-faculty">';
                            while ( $query->have_posts() ) {
                                $query->the_post();
                                echo '<li class="clear">';
                                echo '<a href="';
                                echo get_post_meta($post->ID, 'faculty_page', true);
                                echo '">';
                                echo '<figure class="all-faculty-thumb">';
                                the_post_thumbnail('all-faculty-mug');
                                echo '</figure>';
                                echo '<aside class="all-faculty-text">';
                                echo '<h2 class="all-faculty-name">';
                                echo get_post_meta($post->ID, 'first_name', true);
                                if(get_post_meta($post->ID, 'middle_name', true)) : 
                                    echo ' ' . get_post_meta($post->ID, 'middle_name', true); 
                                endif;
                                echo ' ' . get_post_meta($post->ID, 'last_name', true);
                                if(get_post_meta($post->ID, 'title', true)) : 
                                    echo ', ' . get_post_meta($post->ID, 'title', true);  
                                endif; 
                                echo '</h2>';
                                echo '<h3 class="all-faculty-department">';
								if ( get_post_meta($post->ID, 'department', true) == 'Curriculum and Instruction' ) :
									echo 'Curriculum &amp; Instruction';
								else:
									echo get_post_meta($post->ID, 'department', true);
								endif;
                                echo '</h3>';
                                echo '<h4 class="all-faculty-title">';
                                echo nl2br(get_post_meta($post->ID, 'positions', true));
                                echo '</h4>';
                                echo '</aside>';
                                echo '</a>';
                                echo '</li>';
                            }
                        echo '</ul>';
                    echo '</div>';
                echo '</div>';
                // Curriculum & Instruction
                echo '<div id="ci-faculty">';
                    echo '<div class="indent clear">';
                        echo '<ul class="all-faculty">';
                            while ( $query->have_posts() ) {
                                $query->the_post();
                                if ( get_post_meta($post->ID, 'department', true) == 'Curriculum and Instruction' ) :
                                    echo '<li class="clear">';
                                    echo '<a href="';
                                    echo get_post_meta($post->ID, 'faculty_page', true);
                                    echo '">';
                                    echo '<figure class="all-faculty-thumb">';
                                    the_post_thumbnail('all-faculty-mug');
                                    echo '</figure>';
                                    echo '<aside class="all-faculty-text">';
                                    echo '<h2 class="all-faculty-name">';
                                    echo get_post_meta($post->ID, 'first_name', true);
                                    if(get_post_meta($post->ID, 'middle_name', true)) : 
                                        echo ' ' . get_post_meta($post->ID, 'middle_name', true); 
                                    endif;
                                    echo ' ' . get_post_meta($post->ID, 'last_name', true);
                                    if(get_post_meta($post->ID, 'title', true)) : 
                                        echo ', ' . get_post_meta($post->ID, 'title', true);  
                                    endif; 
                                    echo '</h2>';
                                    echo '<h3 class="all-faculty-department">';
                                    echo 'Curriculum &amp; Instruction';
                                    echo '</h3>';
                                    echo '<h4 class="all-faculty-title">';
                                    echo nl2br(get_post_meta($post->ID, 'positions', true));
                                    echo '</h4>';
                                    echo '</aside>';
                                    echo '</a>';
                                    echo '</li>';
                                endif;
                            }
                        echo '</ul>';
                    echo '</div>';
                echo '</div>';
                // Educational Administration
                echo '<div id="ea-faculty">';
                    echo'<div class="indent clear">';
                        echo '<ul class="all-faculty">';
							while ( $query->have_posts() ) {
								$query->the_post();
								if ( get_post_meta($post->ID, 'department', true) == 'Educational Administration' ) :
                                    echo '<li class="clear">';
                                    echo '<a href="';
                                    echo get_post_meta($post->ID, 'faculty_page', true);
                                    echo '">';
                                    echo '<figure class="all-faculty-thumb">';
                                    the_post_thumbnail('all-faculty-mug');
                                    echo '</figure>';
                                    echo '<aside class="all-faculty-text">';
                                    echo '<h2 class="all-faculty-name">';
                                    echo get_post_meta($post->ID, 'first_name', true);
                                    if(get_post_meta($post->ID, 'middle_name', true)) : 
                                        echo ' ' . get_post_meta($post->ID, 'middle_name', true); 
                                    endif;
                                    echo ' ' . get_post_meta($post->ID, 'last_name', true);
                                    if(get_post_meta($post->ID, 'title', true)) : 
                                        echo ', ' . get_post_meta($post->ID, 'title', true);  
                                    endif; 
                                    echo '</h2>';
                                    echo '<h3 class="all-faculty-department">';
                                    echo get_post_meta($post->ID, 'department', true);
                                    echo '</h3>';
                                    echo '<h4 class="all-faculty-title">';
                                    echo nl2br(get_post_meta($post->ID, 'positions', true));
                                    echo '</h4>';
                                    echo '</aside>';
                                    echo '</a>';
                                    echo '</li>';
								endif;
                         	}
                        echo '</ul>';
                    echo '</div>';
                echo '</div>';
                // Educational Psychology
                echo '<div id="ep-faculty">';
                    echo '<div class="indent clear">';
                        echo '<ul class="all-faculty">';
							while ( $query->have_posts() ) {
								$query->the_post();
								if ( get_post_meta($post->ID, 'department', true) == 'Educational Psychology' ) :
                                    echo '<li class="clear">';
                                    echo '<a href="';
                                    echo get_post_meta($post->ID, 'faculty_page', true);
                                    echo '">';
                                    echo '<figure class="all-faculty-thumb">';
                                    the_post_thumbnail('all-faculty-mug');
                                    echo '</figure>';
                                    echo '<aside class="all-faculty-text">';
                                    echo '<h2 class="all-faculty-name">';
                                    echo get_post_meta($post->ID, 'first_name', true);
                                    if(get_post_meta($post->ID, 'middle_name', true)) : 
                                        echo ' ' . get_post_meta($post->ID, 'middle_name', true); 
                                    endif;
                                    echo ' ' . get_post_meta($post->ID, 'last_name', true);
                                    if(get_post_meta($post->ID, 'title', true)) : 
                                        echo ', ' . get_post_meta($post->ID, 'title', true);  
                                    endif; 
                                    echo '</h2>';
                                    echo '<h3 class="all-faculty-department">';
                                    echo get_post_meta($post->ID, 'department', true);
                                    echo '</h3>';
                                    echo '<h4 class="all-faculty-title">';
                                    echo nl2br(get_post_meta($post->ID, 'positions', true));
                                    echo '</h4>';
                                    echo '</aside>';
                                    echo '</a>';
                                    echo '</li>';
								endif;
                         	}
                        echo '</ul>';
                    echo '</div>';
                echo '</div>';
			}
			/* Restore original Post Data */
			wp_reset_postdata();
			?>
		</div><!-- #tabs -->
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>