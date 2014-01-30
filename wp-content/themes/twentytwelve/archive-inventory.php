<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Twelve already
 * has tag.php for Tag archives, category.php for Category archives, and
 * author.php for Author archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php
					if ( is_day() ) :
						printf( __( 'Daily Archives: %s', 'twentytwelve' ), '<span>' . get_the_date() . '</span>' );
					elseif ( is_month() ) :
						printf( __( 'Monthly Archives: %s', 'twentytwelve' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'twentytwelve' ) ) . '</span>' );
					elseif ( is_year() ) :
						printf( __( 'Yearly Archives: %s', 'twentytwelve' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'twentytwelve' ) ) . '</span>' );
					else :
						_e( 'Archives', 'twentytwelve' );
					endif;
				?></h1>
			</header><!-- .archive-header -->

			<?php
			/* Start the Loop */
			//while ( have_posts() ) : the_post();

				/* Include the post format-specific template for the content. If you want to
				 * this in a child theme then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
				// get_template_part( 'content', get_post_format() );
				?>
					<ul>
						<style>
							.inventory-list-item {
								padding: 10px;
								border: 1px solid #000;
								width: 47%;
								float: left;
								min-height: 200px;
								margin: 0 5px 5px 0;
							}
							.inventory-list-item-title {
								text-decoration: none;
								font-size: 1.5em;

							}
						</style>
					    <?php

						$query = new WP_Query( array( 'post_type' => 'inventory', 'posts_per_page' => -1 ) );

						while ( $query->have_posts() ) : $query->the_post(); ?>

						<?php 
							 
							$desc = get_post_meta($post->ID, 'item_desc', true);
							print_r($meta_values);
						?>
							<li class="inventory-list-item">
								<h2 class="inventory-list-item-title"><?php the_title(); ?></h2>
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
								<p><?php echo $desc[0]; ?></p>
							</li>
						<?php
						endwhile;

						wp_reset_postdata();
						?>
			
					</ul>
			<?php
			//twentytwelve_content_nav( 'nav-below' );
			?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>